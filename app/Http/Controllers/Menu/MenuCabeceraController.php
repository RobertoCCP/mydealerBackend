<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Menu\StoreMenuCabeceraRequest;
use App\Http\Requests\Menu\UpdateMenuCabeceraRequest;
use App\Models\MenuCabecera;
use App\Traits\HttpResponses;
use Illuminate\Http\Response;

class MenuCabeceraController extends Controller {

    use HttpResponses;
    
    /**
     * @OA\Get(
     * path="/api/menu/cabecera",
     * tags={"Web - Menu Cabecera"},
     * summary="Buscar todos los menu cabecera",
     * description="Devuelve una lista con todos los menu cabecera",
     * @OA\Response(
     *         response=200,
     *         description="successful operation",     
     *     ),
     * @OA\Response(
     *     response=500,
     *     description="Database error"
     * ),
     * )  
     */ 
    public function index() {
        try {
            return $this->success(MenuCabecera::all(), 'Listado de menú cabeceras obtenido correctamente');
        } catch (\Exception $e) {
            return $this->error('Error al obtener los menu cabecera.', $e->getMessage(), 500);
        }        
    }



    /**
     * @OA\Post(
     * path="/api/menu/cabecera",
     * tags={"Web - Menu Cabecera"},
     * summary="Crear un menu cabecera",
     * description="Devuelve el menu cabecera creado",
     * @OA\RequestBody(
     * description="Datos del menu a crear",
     *    required=true,
     *    @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="nombre", type="string", maxLength=20, description="nombre del menu"),
     *        @OA\Property(property="orden", type="integer", description="Orden de presentación"),     
     *        @OA\Property(property="sitio", type="string", maxLength=10, description="sitio del menu"),
     *    )
     * ),
     * @OA\Response(
     *         response=200,
     *         description="successful operation",     
     *     ),
     * @OA\Response(
     *     response=400,
     *     description="Bad request"
     * ),
     * @OA\Response(
     *     response=409,
     *     description="Dato duplicado"
     * ),
     * @OA\Response(
     *     response=500,
     *     description="Database error"
     * ),
     * )  
     */
    public function store(StoreMenuCabeceraRequest $request) {
        $request->validated();

        $menuCabecera =  MenuCabecera::create($request->all());

        return $this->success($menuCabecera, 'Menu cabecera creado correctamente', Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     * path="/api/menu/cabecera/{codmenucabecera}",
     * tags={"Web - Menu Cabecera"},
     * summary="Buscar menu cabecera específico",
     * description="Devuelve el menu cabecera por el codmenucabecera",
     * 
     * @OA\Parameter(
     *      description="Necesita el codmenucabecera",
     *      in="path",
     *      name="codmenucabecera",
     *      required=true,
     * ),
     * 
     * @OA\Response(
     *         response=200,
     *         description="successful operation",     
     * ),
     * @OA\Response(
     *     response=404,
     *     description="Dato no encontrado"
     * ),
     * @OA\Response(
     *     response=500,
     *     description="Database error"
     * ),
     * )
     */
    public function show(MenuCabecera $menuCabecera) {
        return $this->success($menuCabecera, 'Menu cabecera obtenido correctamente');
    }

    /**
    * @OA\Put(
    * path="/api/menu/cabecera/{codmenucabecera}",
    * tags={"Web - Menu Cabecera"},
    * summary="Actualizar menu cabecera",
    * description="Actualiza un menu cabecera por codmenucabecera",
    *     @OA\Parameter(
    *      description="Necesita el codmenucabecera",
    *      in="path",
    *      name="codmenucabecera",
    *      required=true,
    *     ),
    *     @OA\RequestBody(
    *         required=true,
    *         description="Datos del parámetro a actualizar",
    *         @OA\JsonContent(
    *             type="object",
    *           @OA\Property(property="nombre", type="string", maxLength=20, description="nombre del menu"),
     *          @OA\Property(property="orden", type="integer", description="Orden de presentación"),     
     *          @OA\Property(property="sitio", type="string", maxLength=10, description="sitio del menu"),
    *         )
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Operación exitosa. Devuelve el tipo de novedad actualizado.",
    *     ),
    *     @OA\Response(
    *         response=400,
    *         description="Solicitud incorrecta. El formato de la solicitud es inválido."
    *     ),
    *     @OA\Response(
    *         response=404,
    *         description="No encontrado. El tipo de novedad especificado no existe."
    *     ),
    *     @OA\Response(
    *         response=409,
    *         description="Conflicto. Ya existe un tipo de novedad con el nuevo código proporcionado."
    *     ),
    *     @OA\Response(
    *         response=500,
    *         description="Error del servidor. Error interno al procesar la solicitud."
    *     )
    * )
    */
    public function update(UpdateMenuCabeceraRequest $request, MenuCabecera $menuCabecera) {
        try {
            // $this->authorize('update', $menuCabecera);
            $request->validated();

            $menuCabecera->update($request->all());

            return $this->success($menuCabecera, 'Menu cabecera actualizado correctamente');
        } catch (\Exception $e) {
            return $this->error('Error al actualizar el menu cabecera.', $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    
    /**
     * @OA\Delete(
     * path="/api/menu/cabecera/{codmenucabecera}",
     * tags={"Web - Menu Cabecera"},
     * summary="Eliminar una menu cabecera específica",
     * description="Devuelve un estado 204 si se eliminó",
     * 
     * @OA\Parameter(
     *      description="Necesita el codmenucabecera",
     *      in="path",
     *      name="codmenucabecera",
     *      required=true,
     * ),
     * 
     * @OA\Response(
     *         response=204,     
     *     description="Eliminado correctamente"
     * ),
     * @OA\Response(
     *     response=404,
     *     description="Dato no encontrado"
     * ),
     * @OA\Response(
     *     response=500,
     *     description="Database error"
     * ),
     * )    
     */
    public function destroy(MenuCabecera $menuCabecera) {

        try {
            // $this->authorize('delete', $menuCabecera);

            $menuCabecera->delete();

            return $this->success($menuCabecera, 'Menu cabecera eliminado correctamente');
        } catch (\Exception $e) {
            return $this->error('Error al eliminar el menu cabecera.', $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
