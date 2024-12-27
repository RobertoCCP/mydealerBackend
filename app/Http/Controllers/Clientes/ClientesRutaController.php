<?php

namespace App\Http\Controllers\Clientes;

use App\Models\Clientes\RutaDet;
use App\Traits\HttpResponses;
use App\Http\Controllers\Controller;

class ClientesRutaController extends Controller
{
    use HttpResponses;
/**
 * @OA\Get(
 *     path="/api/vendedor/clientes/{codvendedor}/{codruta}",
 *     tags={"Vendedor - Cliente Rutas"},
 *     summary="Obtener clientes por vendedor y ruta",
 *     @OA\Parameter(
 *         name="codvendedor",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="string"),
 *         description="Código del vendedor"
 *     ),
 *     @OA\Parameter(
 *         name="codruta",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="string"),
 *         description="Código de la ruta"
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Operación exitosa",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="codrutadet", type="integer"),
 *                 @OA\Property(property="codcliente", type="string"),
 *                 @OA\Property(property="coddireccionenvio", type="integer"),
 *                 @OA\Property(property="cedularuc", type="string"),
 *                 @OA\Property(property="cliente_nombre", type="string"),
 *                 @OA\Property(property="direccion", type="string"),
 *                 @OA\Property(property="latitud", type="number", format="float"),
 *                 @OA\Property(property="longitud", type="number", format="float"),
 *                 @OA\Property(property="limitecredito", type="number", format="float"),
 *                 @OA\Property(property="saldopendiente", type="number", format="float"),
 *                 @OA\Property(property="vencido", type="number", format="float")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="No se encontraron clientes en esta ruta"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error al obtener los clientes"
 *     )
 * )
 */  
    public function ClientesPorVendedorRuta($codvendedor, $codruta)
    {
        try {
            $clientes = RutaDet::where('rutadet.codvendedor', $codvendedor)
                ->where('rutadet.codruta', $codruta)
                ->leftJoin('cliente as c', 'rutadet.codcliente', '=', 'c.codcliente')
                ->leftJoin('direccionenvio as de', function ($join) {
                    $join->on('rutadet.coddireccionenvio', '=', 'de.coddireccionenvio')
                        ->on('rutadet.codcliente', '=', 'de.codcliente');
                })
                ->leftJoin('direccionenviogps as dgp', function ($join) {
                    $join->on('de.coddireccionenvio', '=', 'dgp.coddireccionenvio')
                        ->on('de.codcliente', '=', 'dgp.codcliente');
                })
                ->orderBy('rutadet.orden')
                ->get([
                    'rutadet.codrutadet',
                    'rutadet.codcliente',
                    'rutadet.coddireccionenvio',
                    'c.cedularuc',
                    'c.nombre AS cliente_nombre',
                    'de.direccion',
                    'dgp.latitud',
                    'dgp.longitud',
                    'c.limitecredito',
                    'c.saldopendiente',
                    'c.vencido'
                ]);

            if ($clientes->isEmpty()) {
                return $this->error('No data', 'No se encontraron clientes en esta ruta.', 404);
            }
            $message = 'Clientes encontrados correctamente';
            return $this->success($clientes, $message);
        } catch (\Exception $e) {
            return $this->error('Error al obtener los clientes.', $e->getMessage(), 500);
        }
    }
/**
 * @OA\Get(
 *     path="/api/vendedor/clientes/{codvendedor}/{codruta}/{codcliente}",
 *     tags={"Vendedor - Cliente Rutas"},
 *     summary="Obtener clientes por vendedor, ruta y cliente",
 *     @OA\Parameter(
 *         name="codvendedor",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="string"),
 *         description="Código del vendedor"
 *     ),
 *     @OA\Parameter(
 *         name="codruta",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="string"),
 *         description="Código de la ruta"
 *     ),
 *     @OA\Parameter(
 *         name="codcliente",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="string"),
 *         description="Código del cliente"
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Operación exitosa",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="codrutadet", type="integer"),
 *                 @OA\Property(property="codcliente", type="string"),
 *                 @OA\Property(property="coddireccionenvio", type="integer"),
 *                 @OA\Property(property="cedularuc", type="string"),
 *                 @OA\Property(property="cliente_nombre", type="string"),
 *                 @OA\Property(property="direccion", type="string"),
 *                 @OA\Property(property="latitud", type="number", format="float"),
 *                 @OA\Property(property="longitud", type="number", format="float"),
 *                 @OA\Property(property="limitecredito", type="number", format="float"),
 *                 @OA\Property(property="saldopendiente", type="number", format="float"),
 *                 @OA\Property(property="vencido", type="number", format="float")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="No se encontraron clientes en esta ruta"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error al obtener los clientes"
 *     )
 * )
 */
    public function ClientesPorVendedorRutaCliente($codvendedor, $codruta, $codcliente)
    {
        try {
            $clientes = RutaDet::where('rutadet.codvendedor', $codvendedor)
                ->where('rutadet.codruta', $codruta)
                ->where('rutadet.codcliente', $codcliente)
                ->leftJoin('cliente as c', 'rutadet.codcliente', '=', 'c.codcliente')
                ->leftJoin('direccionenvio as de', function ($join) {
                    $join->on('rutadet.coddireccionenvio', '=', 'de.coddireccionenvio')
                        ->on('rutadet.codcliente', '=', 'de.codcliente');
                })
                ->leftJoin('direccionenviogps as dgp', function ($join) {
                    $join->on('de.coddireccionenvio', '=', 'dgp.coddireccionenvio')
                        ->on('de.codcliente', '=', 'dgp.codcliente');
                })
                ->orderBy('rutadet.orden')
                ->get([
                    'rutadet.codrutadet',
                    'rutadet.codcliente',
                    'rutadet.coddireccionenvio',
                    'c.cedularuc',
                    'c.nombre AS cliente_nombre',
                    'de.direccion',
                    'dgp.latitud',
                    'dgp.longitud',
                    'c.limitecredito',
                    'c.saldopendiente',
                    'c.vencido'
                ]);

            if ($clientes->isEmpty()) {
                return $this->error('No data', 'No se encontraron clientes en esta ruta.', 404);
            }
            $message = 'Clientes encontrados correctamente';
            return $this->success($clientes, $message);
        } catch (\Exception $e) {
            return $this->error('Error al obtener los clientes.', $e->getMessage(), 500);
        }
    }

/**
 * @OA\Get(
 *     path="/api/vendedor/clientesVendedor/{codvendedor}/{codcliente}",
 *     tags={"Vendedor - Cliente Rutas"},
 *     summary="Obtener clientes por vendedor y cliente",
 *     @OA\Parameter(
 *         name="codvendedor",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="string"),
 *         description="Código del vendedor"
 *     ),
 *     @OA\Parameter(
 *         name="codcliente",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="string"),
 *         description="Código del cliente"
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Operación exitosa",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="codrutadet", type="integer"),
 *                 @OA\Property(property="codcliente", type="string"),
 *                 @OA\Property(property="coddireccionenvio", type="integer"),
 *                 @OA\Property(property="cedularuc", type="string"),
 *                 @OA\Property(property="cliente_nombre", type="string"),
 *                 @OA\Property(property="direccion", type="string"),
 *                 @OA\Property(property="latitud", type="number", format="float"),
 *                 @OA\Property(property="longitud", type="number", format="float"),
 *                 @OA\Property(property="limitecredito", type="number", format="float"),
 *                 @OA\Property(property="saldopendiente", type="number", format="float"),
 *                 @OA\Property(property="vencido", type="number", format="float")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="No se encontraron clientes en esta ruta"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error al obtener los clientes"
 *     )
 * )
 */
    public function ClientesPorVendedor($codvendedor, $codcliente)
    {
        try {
            $clientes = RutaDet::where('rutadet.codvendedor', $codvendedor)
                ->where('rutadet.codcliente', $codcliente)
                ->leftJoin('cliente as c', 'rutadet.codcliente', '=', 'c.codcliente')
                ->leftJoin('direccionenvio as de', function ($join) {
                    $join->on('rutadet.coddireccionenvio', '=', 'de.coddireccionenvio')
                        ->on('rutadet.codcliente', '=', 'de.codcliente');
                })
                ->leftJoin('direccionenviogps as dgp', function ($join) {
                    $join->on('de.coddireccionenvio', '=', 'dgp.coddireccionenvio')
                        ->on('de.codcliente', '=', 'dgp.codcliente');
                })
                ->orderBy('rutadet.orden')
                ->get([
                    'rutadet.codrutadet',
                    'rutadet.codcliente',
                    'rutadet.coddireccionenvio',
                    'c.cedularuc',
                    'c.nombre AS cliente_nombre',
                    'de.direccion',
                    'dgp.latitud',
                    'dgp.longitud',
                    'c.limitecredito',
                    'c.saldopendiente',
                    'c.vencido'
                ]);

            if ($clientes->isEmpty()) {
                return $this->error('No data', 'No se encontraron clientes en esta ruta.', 404);
            }
            $message = 'Clientes encontrados correctamente';
            return $this->success($clientes, $message);
        } catch (\Exception $e) {
            return $this->error('Error al obtener los clientes.', $e->getMessage(), 500);
        }
    }
}