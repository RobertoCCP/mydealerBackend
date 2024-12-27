<?php

namespace Tests\Feature\Menu;

use App\Models\MenuCabecera;
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class MenuCabeceraTest extends TestCase {

    private $endpoint = 'api/menu/cabecera';
    /**
     * Test de obtener listado de menú cabeceras
     */
    public function test_obtener_listado_menu_cabeceras(): void {

        $response = $this->getJson($this->endpoint);

        $conteoMenuCabeceras = MenuCabecera::all()->count();

        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json
                    ->has('estado')
                    ->has('mensaje')
                    ->has('datos', $conteoMenuCabeceras)
                    ->etc()
            );
    }

    /**
     * Test de obtener un menú cabecera
     */
    public function test_obtener_menu_cabecera(): void {

        $response = $this->getJson($this->endpoint . '/1');

        $menuCabecera = MenuCabecera::find(1);

        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json
                    ->has('estado')
                    ->has('mensaje')
                    ->where('datos.codmenucabecera', $menuCabecera->codmenucabecera)
                    ->where('datos.nombre', $menuCabecera->nombre)
                    ->where('datos.orden', $menuCabecera->orden)
                    ->where('datos.sitio', $menuCabecera->sitio)
                    ->etc()
            );
    }


    /**
     * Test de crear un menú cabecera
     */
    public function test_crear_actualizar_y_eliminar_menu_cabecera(): void {

        $menuCabecera = [
            'nombre' => 'Test menú cabecera',
            'orden' => 1,
            'sitio' => 'sitio 1'
        ];

        $response = $this->postJson($this->endpoint, $menuCabecera);

        $response->assertStatus(Response::HTTP_CREATED);

        $menuCabeceraId = $response['datos']['codmenucabecera'];

        $this->assertDatabaseHas('menucabecera', [
            'codmenucabecera' => $menuCabeceraId, ...$menuCabecera
        ]);

        $endpoint = $this->endpoint . '/' . $menuCabeceraId;

        $response = $this->putJson($endpoint, [
            'nombre' => 'Actualizado',
        ]);

        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json
                    ->where('mensaje', 'Menu cabecera actualizado correctamente')
                    ->etc()
            );

        $this->assertDatabaseHas('menucabecera', [
            ...$response['datos'],
            'codmenucabecera' => $menuCabeceraId,
            'nombre' => 'Actualizado',
        ]);

        $response = $this->deleteJson($endpoint);

        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json
                    ->where('mensaje', 'Menu cabecera eliminado correctamente')
                    ->etc()
            );
    }
}
