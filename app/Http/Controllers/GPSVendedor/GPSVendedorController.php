<?php

namespace App\Http\Controllers\GPSVendedor;

use App\Http\Controllers\Controller;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GPSVendedorController extends Controller
{
    use HttpResponses;

    /**
     *
     *
     * @OA\Get(
     * path="/api/direccion/envio",
     * tags={"Vendedor - Dirección Envío GPS"},
     * summary="Muestra la información de entrega del producto de un cliente",
     * description="Devuelve los datos del cliente y su ubicacion: pais, provincia ciudad, en algunos casos longitud y latitud",
     * @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             default=1
     *         ),
     *         description="Número de la página para la paginación"
     *     ),
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
    public function direccionEnvio(Request $request)
    {
        try {
            $page = $request->query('page', 1);
            $perPage = 20;
            $result = DB::table('direccionenvio')
                ->whereNotNull('latitud')
                ->whereNotNull('longitud')
                ->paginate($perPage, ['*'], 'page', $page);
            return $this->success($result);
        } catch (\Exception $e) {
            return $this->error('Error al obtener los datos del envio.', $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     * path="/api/direccion/envio/gps",
     * tags={"Vendedor - Dirección Envío GPS"},
     * summary="Buscar la dirección de envio del cliente y vendedor",
     * description="Devuelve una lista con todos los clientes su longitud y latitud y el codigo del vendedor asignado para ello",
     * @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             default=1
     *         ),
     *         description="Número de la página para la paginación"
     *     ),
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
    public function direccionEnvioGPS(Request $request)
    {
        try {
            $page = $request->query('page', 1);
            $perPage = 20;
            $result = DB::table('direccionenviogps')
                ->whereNotNull('latitud')
                ->whereNotNull('longitud')
                ->paginate($perPage, ['*'], 'page', $page);
            return $this->success($result);
        } catch (\Exception $e) {
            return $this->error('Error al obtener los datos de la direccion GPS.', $e->getMessage(), 500);
        }
    }


    /**
     * @OA\Get(
     * path="/api/direccion/envio/gps/{codigo_vendedor}",
     * tags={"Vendedor - Dirección Envío GPS"},
     * summary="Buscar la dirección de envio del cliente y vendedor por codigo_vendedor",
     * description="Devuelve una lista con todos los clientes su longitud y latitud y su vendedor por codigo_vendedor",
     * @OA\Parameter(
     *      description="Necesita el codigo_vendedor",
     *      in="path",
     *      name="codigo_vendedor",
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
    public function direccionEnvioGPSporCodigoVendedor($codigo_vendedor)
    {
        try {
            $result = DB::table('direccionenviogps')
                ->where('direccionenviogps.codvendedor', '=', $codigo_vendedor)
                ->get();
            return $this->success($result);
        } catch (\Exception $e) {
            return $this->error('Error al obtener los datos de la direccion GPS.', $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     * path="/api/direccion/envio/gps/{codigo_vendedor}/{codigo_cliente}",
     * tags={"Vendedor - Dirección Envío GPS"},
     * summary="Buscar la dirección de envio del cliente y vendedor por codigo_vendedor y codigo_cliente",
     * description="Devuelve una lista con todos los clientes su longitud y latitud y su vendedor por codigo_vendedor y codigo_cliente",
     * @OA\Parameter(
     *      description="Necesita el codigo_vendedor",
     *      in="path",
     *      name="codigo_vendedor",
     *      required=true,
     * ),
     * @OA\Parameter(
     *      description="Necesita el codigo_cliente",
     *      in="path",
     *      name="codigo_cliente",
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
    public function direccionEnvioGPSporCodigoVendedoryCliente($codigo_vendedor, $codigo_cliente)
    {
        try {
            $result = DB::table('direccionenviogps')
                ->where('direccionenviogps.codvendedor', '=', $codigo_vendedor)
                ->where('direccionenviogps.codcliente', '=', $codigo_cliente)
                ->get();
            return $this->success($result);
        } catch (\Exception $e) {
            return $this->error('Error al obtener los datos de la direccion GPS.', $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     * path="/api/datos/usuario/cliente/{codigo_cliente}",
     * tags={"Vendedor - Dirección Envío GPS"},
     * summary="Buscar un cliente específico",
     * description="Devuelve datos del cliente por el codigo_cliente",
     *
     * @OA\Parameter(
     *      description="Necesita el codigo_cliente",
     *      in="path",
     *      name="codigo_cliente",
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
     *
     */
    public function buscarClientePorId($codigo_cliente)
    {
        try {
            $result = DB::table('cliente')
                ->where('cliente.codcliente', '=', $codigo_cliente)
                ->get();
            if (!$result) {
                return $this->error("No data", "Cliente no encontrado", 404);
            }
            return $this->success($result);
        } catch (\Exception $e) {
            return $this->error('Error al obtener los datos del cliente.', $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     * path="/api/datos/usuario/vendedor/{codigo_vendedor}",
     * tags={"Vendedor - Dirección Envío GPS"},
     * summary="Buscar un vendedor específico",
     * description="Devuelve datos del vendedor por el codigo_vendedor",
     *
     * @OA\Parameter(
     *      description="Necesita el codigo_vendedor",
     *      in="path",
     *      name="codigo_vendedor",
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
     *
     */
    public function buscarVendedorPorId($codigo_vendedor)
    {
        try {
            $result = DB::table('vendedor')
                ->where('codvendedor', '=', $codigo_vendedor)
                ->get();
            if (!$result) {
                return $this->error("No data", "Vendedor no encontrado", 404);
            }
            return $this->success($result);
        } catch (\Exception $e) {
            return $this->error('Error al obtener los datos del vendedor.', $e->getMessage(), 500);
        }
    }

    /**
     *
     *
     * @OA\Get(
     * path="/api/direccion/rutas",
     * tags={"Vendedor - Dirección Envío GPS"},
     * summary="Muestra las rutas disponibles",
     * description="Devuelve los datos de rutas para la entrega de productos",
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
    public function rutasVendedores()
    {
        try {
            $result = DB::table('rutas')->get();
            return $this->success($result);
        } catch (\Exception $e) {
            return $this->error('Error al obtener las rutas del vendedor.', $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     * path="/api/direccion/ruta/gestion",
     * tags={"Vendedor - Dirección Envío GPS"},
     * summary="Informa sobre la direccion del cliente y los datos del vendedor",
     * description="Devuelve una lista con los detalles del vendedor y cliente ",
     * @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             default=1
     *         ),
     *         description="Número de la página para la paginación"
     *     ),
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
    public function rutaGestion(Request $request)
    {
        try {
            $page = $request->query('page', 1);
            $perPage = 20;
            $result = DB::table('rutagestion')
                ->paginate($perPage, ['*'], 'page', $page);
            return $this->success($result);
        } catch (\Exception $e) {
            return $this->error('Error al obtener la ruta de gestion.', $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     * path="/api/direccion/ruta/gestion/{codigo_ruta_gestion}",
     * tags={"Vendedor - Dirección Envío GPS"},
     * summary="Informa sobre la direccion del cliente y los datos del vendedor por codigo_ruta_gestion",
     * description="Devuelve una lista con los detalles del vendedor y cliente  por codigo_ruta_gestion",
     * @OA\Parameter(
     *      description="Necesita el codigo_ruta_gestion",
     *      in="path",
     *      name="codigo_ruta_gestion",
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
    public function rutaGestionById($codigo_ruta_gestion)
    {
        try {
            $result = DB::table('rutagestion')
                ->where('rutagestion.codrutagestion', '=', $codigo_ruta_gestion)
                ->get();
            return $this->success($result);
        } catch (\Exception $e) {
            return $this->error('Error al obtener la ruta de gestion.', $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     * path="/api/direccion/rutas/detalle",
     * tags={"Vendedor - Dirección Envío GPS"},
     * summary="Informa sobre la relacion entre, cliente, vendedor, lugar de entrega y semanas",
     * description="Devuelve una lista con los detalles de la gestion de rutas",
     * @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             default=1
     *         ),
     *         description="Número de la página para la paginación"
     *     ),
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
    public function rutaDetalle(Request $request)
    {
        try {
            $page = $request->query('page', 1);
            $perPage = 20;
            $result = DB::table('rutadet')
                ->paginate($perPage, ['*'], 'page', $page);
            return $this->success($result);
        } catch (\Exception $e) {
            return $this->error('Error al obtener los detalles de la ruta.', $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     * path="/api/direccion/rutas/detalle/cliente/{codigo_cliente}",
     * tags={"Vendedor - Dirección Envío GPS"},
     * summary="Informa sobre la relacion entre, cliente, vendedor, lugar de entrega y semanas",
     * description="Devuelve una lista con los detalles de la gestion de rutas por codigo_cliente",
     * @OA\Parameter(
     *      description="Necesita el codigo_cliente",
     *      in="path",
     *      name="codigo_cliente",
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
    public function rutaDetallePorCodigoCliente($codigo_cliente)
    {
        try {
            $result = DB::table('rutadet')
                ->where('rutadet.codcliente', '=', $codigo_cliente)
                ->get();
            return $this->success($result);
        } catch (\Exception $e) {
            return $this->error('Error al obtener la ruta de gestion.', $e->getMessage(), 500);
        }
    }
}