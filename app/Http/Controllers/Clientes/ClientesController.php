<?php

namespace App\Http\Controllers\Clientes;

use App\Models\Clientes\RutaDet;
use App\Traits\HttpResponses;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ClientesController extends Controller
{
    use HttpResponses;

    /**     
     * 
     * @OA\Get(
     * path="/api/vendedor/clientes/ruta/{codvendedor}",
     * tags={"Vendedor - Vista Cliente"},
     * summary="Buscar info del cliente",
     * description="Devuelve una lista con los datos del cliente por vendedor",
     * @OA\Parameter(
     *      description="Necesita el codvendedor",
     *      in="path",
     *      name="codvendedor",
     *      required=true,
     * ),
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
    public function obtenerClientesPorRuta($codvendedor)
    {
        try {
            $clientes = RutaDet::where('rutadet.codvendedor', $codvendedor)
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
                    'c.saldopendiente'
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
     * path="/api/vendedor/clientes/cedula/{cedula}",
     * tags={"Vendedor - Vista Cliente"},
     * summary="Buscar info del cliente",
     * description="Devuelve una lista con los datos del cliente por cedula",
     * @OA\Parameter(
     *      description="Necesita la cedula",
     *      in="path",
     *      name="cedula",
     *      required=true,
     * ),
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
    public function obtenerClientesPorCedula($cedula)
    {
        try {
            $clientes = RutaDet::leftJoin('cliente as c', 'rutadet.codcliente', '=', 'c.codcliente')
                ->leftJoin('direccionenvio as de', function ($join) {
                    $join->on('rutadet.coddireccionenvio', '=', 'de.coddireccionenvio')
                        ->on('rutadet.codcliente', '=', 'de.codcliente');
                })
                ->leftJoin('direccionenviogps as dgp', function ($join) {
                    $join->on('de.coddireccionenvio', '=', 'dgp.coddireccionenvio')
                        ->on('de.codcliente', '=', 'dgp.codcliente');
                })
                ->whereExists(function ($query) use ($cedula) {
                    $query->select('*')
                        ->from('cliente')
                        ->whereColumn('rutadet.codcliente', 'cliente.codcliente')
                        ->where('cedularuc', $cedula);
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
                    'c.saldopendiente'
                ]);

            if ($clientes->isEmpty()) {
                return $this->error('No data', 'No se encontraron clientes con esta cédula en esta ruta.', 404);
            }
            $message = 'Clientes encontrados correctamente';
            return $this->success($clientes, $message);
        } catch (\Exception $e) {
            return $this->error('Error al obtener los clientes con esta cédula.', $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     * path="/api/vendedor/clientes/{codvendedor}",
     * tags={"Vendedor - Vista Cliente"},
     * summary="Obtener clientes por vendedor",
     * description="Devuelve una lista paginada de los clientes asignados a un vendedor específico",
     * @OA\Parameter(
     *      description="Código del vendedor",
     *      in="path",
     *      name="codvendedor",
     *      required=true,
     * ),
     * @OA\Parameter(
     *      description="Número de página",
     *      in="query",
     *      name="page",
     *      required=false,
     * ),
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
    public function obtenerClientePorVendedor($codvendedor)
    {
        try {

            $result = DB::table('cliente')
                ->where('codvendedor', $codvendedor)
                ->get();

            return $this->success($result);
        } catch (\Exception $e) {
            return $this->error('Error al obtener los clientes del vendedor.', $e->getMessage(), 500);
        }
    }
}
