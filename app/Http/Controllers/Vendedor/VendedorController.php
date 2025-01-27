<?php

namespace App\Http\Controllers\Vendedor;

use App\Models\Vendedor\Vendedor;
use App\Models\Vendedor\Orden;
use App\Models\Vendedor\Cobro;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class VendedorController extends Controller
{
    use HttpResponses;

    /**
     * @OA\Get(
     * path="/api/vendedor/informacion/{codvendedor}",
     * tags={"Vendedor - Dashboard"},
     * summary="Buscar info del vendedor",
     * description="Devuelve una lista con los datos del vendedor",
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
    public function obtenerInformacion($codvendedor)
    {
        try {
            $vendedor = Vendedor::where('codvendedor', $codvendedor)->first([
                'login', 'nombre', 'codruta', 'email',
                'estado', 'Estado_Autorizacion', 'Fecha_Autorizacion', 'mac_pend_asignacion', 'mac'
            ]);

            if (!$vendedor) {
                return $this->error('No data', 'No se encontró la información del vendedor.', 404);
            }

            $message = 'la información del vendedor se encontró correctamente';
            return $this->success($vendedor, $message);
        } catch (\Exception $e) {
            return $this->error('Error al obtener la información del vendedor.', $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     * path="/api/vendedor/numeroPedidos/{codvendedor}",
     * tags={"Vendedor - Dashboard"},
     * summary="Buscar info de pedidos",
     * description="Devuelve una lista con de pedidos del vendedor",
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
    public function obtenerNumeroPedidos($codvendedor)
    {
        try {
            $pedidos = Orden::where('codvendedor', $codvendedor)
                ->whereHas('ordenesLog', function ($query) {
                    $query->where('estado_AprCom', 'A');
                })->count();

            $pendientes = Orden::where('codvendedor', $codvendedor)
                ->whereHas('ordenesLog', function ($query) {
                    $query->where('estado_AprCom', 'P');
                })->count();

            $datos = ['realizados: ' => $pedidos, 'pendientes' => $pendientes];
            $message = 'Número de pedidos calculados exitosamente';

            return $this->success($datos, $message);
        } catch (\Exception $e) {
            return $this->error('Error al obtener los números de pedidos.', $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     * path="/api/vendedor/numeroCobros/{codvendedor}",
     * tags={"Vendedor - Dashboard"},
     * summary="Buscar info de numero de cobros",
     * description="Devuelve una lista el numero de cobros",
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
    public function obtenerNumeroCobros($codvendedor)
    {
        try {
            $cobros = Cobro::where('codvendedor', $codvendedor)
                ->where('estado', 'R')
                ->count();

            $pendientes = Cobro::where('codvendedor', $codvendedor)
                ->where('estado', 'P')
                ->count();

            $datos = ['realizados: ' => $cobros, 'pendientes' => $pendientes];
            $message = 'Número de cobros calculados exitosamente';

            return $this->success($datos, $message);
        } catch (\Exception $e) {
            return $this->error('Error al obtener los números de cobros.', $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     * path="/api/vendedor",
     * tags={"Web - Vendedor Socios"},
     * summary="Obtener información de todos los vendedores",
     * description="Devuelve una lista paginada con la información de todos los vendedores",
     * @OA\Parameter(
     *      name="page",
     *      in="query",
     *      description="Número de página",
     *      required=false,
     *      @OA\Schema(
     *          type="integer"
     *      )
     * ),
     * @OA\Response(
     *      response=200,
     *      description="successful operation",
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(property="success", type="boolean"),
     *          @OA\Property(property="data", type="object",
     *              @OA\Property(property="current_page", type="integer"),
     *              @OA\Property(property="data", type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="codvendedor", type="integer"),
     *                      @OA\Property(property="nombre", type="string"),
     *                      @OA\Property(property="email", type="string"),
     *                      @OA\Property(property="login", type="string"),
     *                      @OA\Property(property="password", type="string"),
     *                      @OA\Property(property="mac_pend_asignacion", type="string"),
     *                      @OA\Property(property="Estado_Autorizacion", type="string")
     *                  )
     *              ),
     *              @OA\Property(property="first_page_url", type="string"),
     *              @OA\Property(property="from", type="integer"),
     *              @OA\Property(property="last_page", type="integer"),
     *              @OA\Property(property="last_page_url", type="string"),
     *              @OA\Property(property="next_page_url", type="string"),
     *              @OA\Property(property="path", type="string"),
     *              @OA\Property(property="per_page", type="integer"),
     *              @OA\Property(property="prev_page_url", type="string"),
     *              @OA\Property(property="to", type="integer"),
     *              @OA\Property(property="total", type="integer")
     *          ),
     *          @OA\Property(property="message", type="string")
     *      )
     * ),
     * @OA\Response(
     *      response=500,
     *      description="Database error",
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(property="success", type="boolean"),
     *          @OA\Property(property="message", type="string"),
     *          @OA\Property(property="error", type="string")
     *      )
     * )
     * )
     */
    public function obtenerInfoVendedor(Request $request)
    {
        try {
            $page = $request->query('page', 1);
            $perPage = 20;
            $vendedores = Vendedor::select(
                'codvendedor',
                'nombre',
                'email',
                'login',
                'password',
                'mac_pend_asignacion',
                'Estado_Autorizacion'
            )->paginate($perPage, ['*'], 'page', $page);

            return $this->success($vendedores, 'Información de los vendedores encontrada correctamente');
        } catch (\Exception $e) {
            return $this->error('Error al obtener la información de los vendedor.', $e->getMessage(), 500);
        }
    }


    /**
     * @OA\Put(
     * path="/api/vendedor/{login}",
     * tags={"Web - Vendedor Socios"},
     * summary="Editar información del vendedor",
     * description="Actualiza la información de un vendedor existente por su login",
     * @OA\Parameter(
     *      description="Login del vendedor",
     *      in="path",
     *      name="login",
     *      required=true,
     * ),
     * @OA\RequestBody(
     *      required=true,
     *      @OA\MediaType(
     *          mediaType="application/json",
     *          @OA\Schema(
     *              @OA\Property(property="nombre", type="string"),
     *              @OA\Property(property="email", type="string"),
     *              @OA\Property(property="login", type="string"),
     *              @OA\Property(property="password", type="string"),
     *              @OA\Property(property="mac_pend_asignacion", type="string"),
     *              @OA\Property(property="Estado_Autorizacion", type="string"),
     *          )
     *      )
     * ),
     * @OA\Response(
     *      response=200,
     *      description="successful operation",
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(property="success", type="boolean"),
     *          @OA\Property(property="data", type="object",
     *              @OA\Property(property="codvendedor", type="integer"),
     *              @OA\Property(property="nombre", type="string"),
     *              @OA\Property(property="email", type="string"),
     *              @OA\Property(property="login", type="string"),
     *              @OA\Property(property="password", type="string"),
     *              @OA\Property(property="mac_pend_asignacion", type="string"),
     *              @OA\Property(property="Estado_Autorizacion", type="string"),
     *          ),
     *          @OA\Property(property="message", type="string")
     *      )
     * ),
     * @OA\Response(
     *      response=404,
     *      description="No data",
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(property="success", type="boolean"),
     *          @OA\Property(property="message", type="string")
     *      )
     * ),
     * @OA\Response(
     *      response=500,
     *      description="Database error",
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(property="success", type="boolean"),
     *          @OA\Property(property="message", type="string"),
     *          @OA\Property(property="error", type="string")
     *      )
     * )
     * )
     */
    public function editarInfoVendedor(Request $request, $login)
    {
        try {
            $vendedor = Vendedor::where('login', $login)->first();
            if (!$vendedor) {
                return $this->error('No data', 'No se encontró la información del vendedor.', 404);
            }

            $vendedor->nombre = $request->input('nombre');
            $vendedor->email = $request->input('email');
            $vendedor->login = $request->input('login');
            $vendedor->password = $request->input('password');
            $vendedor->mac_pend_asignacion = $request->input('mac_pend_asignacion');
            $vendedor->Estado_Autorizacion = $request->input('Estado_Autorizacion');

            $vendedor->save();
            return $this->success($vendedor, 'Información del vendedor actualizada correctamente');
        } catch (\Exception $e) {
            return $this->error('Error al actualizar la información del vendedor.', $e->getMessage(), 500);
        }
    }


    /**
     * @OA\Delete(
     * path="/api/vendedor/{login}",
     * tags={"Web - Vendedor Socios"},
     * summary="Eliminar vendedor",
     * description="Elimina un vendedor existente por su código",
     * @OA\Parameter(
     *      description="Login del vendedor",
     *      in="path",
     *      name="login",
     *      required=true,
     * ),
     * @OA\Response(
     *      response=200,
     *      description="successful operation",
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(property="success", type="boolean"),
     *          @OA\Property(property="message", type="string")
     *      )
     * ),
     * @OA\Response(
     *      response=404,
     *      description="No data",
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(property="success", type="boolean"),
     *          @OA\Property(property="message", type="string")
     *      )
     * ),
     * @OA\Response(
     *      response=500,
     *      description="Database error",
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(property="success", type="boolean"),
     *          @OA\Property(property="message", type="string"),
     *          @OA\Property(property="error", type="string")
     *      )
     * )
     * )
     */
    public function eliminarVendedor($login)
    {
        try {
            $vendedor = Vendedor::where('login', $login)->first();

            if (!$vendedor) {
                return $this->error('No data', 'No se encontró la información del vendedor.', 404);
            }

            $vendedor->delete();

            return $this->success(null, 'Vendedor eliminado correctamente');
        } catch (\Exception $e) {
            return $this->error('Error al eliminar el vendedor.', $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/vendedor/datosMobilVendedor/{codvendedor}",
     *     tags={"Web - Vendedor Socios"},
     *     summary="Obtener datos de móviles de un vendedor",
     *     description="Consulta los datos de móviles y aplicaciones asociados a un vendedor específico.",
     *     @OA\Parameter(
     *         name="codvendedor",
     *         in="path",
     *         required=true,
     *         description="Código del vendedor",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         description="Número de página (por defecto: 1)",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="current_page", type="integer"),
     *                 @OA\Property(property="data", type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer"),
     *                         @OA\Property(property="codvendedor", type="integer"),
     *                         @OA\Property(property="informacion_del_movil", type="object",
     *                             @OA\Property(property="Serial", type="string"),
     *                             @OA\Property(property="Modelo", type="string"),
     *                             @OA\Property(property="Id", type="string"),
     *                             @OA\Property(property="Fabricante", type="string"),
     *                             @OA\Property(property="Marca", type="string"),
     *                             @OA\Property(property="Hardware", type="string"),
     *                             @OA\Property(property="Tipo", type="string"),
     *                             @OA\Property(property="Usuario", type="string"),
     *                             @OA\Property(property="SDK", type="integer"),
     *                             @OA\Property(property="Board", type="string"),
     *                             @OA\Property(property="Host", type="string"),
     *                             @OA\Property(property="Huella", type="string"),
     *                             @OA\Property(property="Version", type="string"),
     *                             @OA\Property(property="RAM", type="string")
     *                         ),
     *                         @OA\Property(property="informacion_de_aplicaciones", type="object",
     *                             @OA\Property(property="Nombre", type="string"),
     *                             @OA\Property(property="Paquete", type="string"),
     *                             @OA\Property(property="Version", type="string"),
     *                             @OA\Property(property="TargetSdkVersion", type="string"),
     *                             @OA\Property(property="DirectorioSource", type="string"),
     *                             @OA\Property(property="DirectorioData", type="string"),
     *                             @OA\Property(property="PermisosOtorgados", type="string"),
     *                             @OA\Property(property="UltimaFechaInstall", type="string"),
     *                             @OA\Property(property="UltimaFechaUpdate", type="string")
     *                         )
     *                     )
     *                 ),
     *                 @OA\Property(property="first_page_url", type="string"),
     *                 @OA\Property(property="from", type="integer"),
     *                 @OA\Property(property="last_page", type="integer"),
     *                 @OA\Property(property="last_page_url", type="string"),
     *                 @OA\Property(property="next_page_url", type="string"),
     *                 @OA\Property(property="path", type="string"),
     *                 @OA\Property(property="per_page", type="integer"),
     *                 @OA\Property(property="prev_page_url", type="string"),
     *                 @OA\Property(property="to", type="integer"),
     *                 @OA\Property(property="total", type="integer")
     *             ),
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontraron datos",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error de base de datos",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */
    public function obtenerDatosMobilVendedor(Request $request, $codvendedor)
    {
        try {
            $page = $request->query('page', 1);
            $perPage = 20;
            $result = DB::table('datos_mobilvendedor')
                ->where('codvendedor', $codvendedor)
                ->paginate($perPage, ['*'], 'page', $page);

            foreach ($result as $item) {
                $item->informacion_del_movil = json_decode($this->decodeBlob($item->JSONMobilBUILD));
                $item->informacion_de_aplicaciones = json_decode($this->decodeBlob($item->JSONMobilINS));
                unset($item->JSONMobilBUILD);
                unset($item->JSONMobilINS);
            }


            return $this->success($result);
        } catch (\Exception $e) {
            return $this->error('Error al obtener los datos del vendedor.', $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/ordenes",
     *     tags={"Vendedor - Pedidos"},
     *     summary="Obtener todas las órdenes",
     *     description="Obtiene una lista de todas las órdenes con su número, fecha, nombre del cliente y total, filtradas por codvendedor.",
     *     @OA\Parameter(
     *         name="codvendedor",
     *         in="query",
     *         description="Código del vendedor para filtrar las órdenes",
     *         required=true,
     *         @OA\Schema(type="int")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Número de página para la paginación",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="Orden", type="integer"),
     *             @OA\Property(property="Fecha", type="string", format="date"),
     *             @OA\Property(property="Nombre_del_Cliente", type="string"),
     *             @OA\Property(property="Total", type="number", format="double")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al obtener las órdenes",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */
    public function obtenerOrdenes($codvendedor)
    {
        try {
           // $page = $request->query('page', 1);
          // $perPage = 20;

            // $result = DB::table('orden as o')
            //     ->join('cliente as c', 'o.codcliente', '=', 'c.codcliente')
            //     ->join('ordendet as od', 'o.srorden', '=', 'od.srorden')
            //     ->select(
            //         'o.srorden as Orden',
            //         'o.fecha as Fecha',
            //         'c.nombre as Nombre_del_Cliente',
            //         DB::raw('SUM(od.total) as Total')
            //     )
            //     ->where('o.codvendedor', $codvendedor)
            //     ->groupBy('o.srorden', 'o.fecha', 'c.nombre')
            //     ->paginate($perPage, ['*'], 'page', $page);
            $result = DB::table('orden as o')->select('*')->where('o.codvendedor', $codvendedor)->get();
            return $this->success($result);
        } catch (\Exception $e) {
            return $this->error('Error al obtener las órdenes.', $e->getMessage(), 500);
        }
    }
    function envioCorreo($destinatario, $titulo = 'Mensaje de prueba Mydealer', $mensaje = 'Mensaje enviado desde Mydealer') {
        if ($destinatario == null) {
            return false;
        }
        var_dump($destinatario);
        try {
            Mail::raw($mensaje, function ($message) use ($destinatario, $titulo) {
                $message->to($destinatario)->subject($titulo);
            });
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    private function decodeBlob($blob)
    {
        return is_null($blob) ? null : mb_convert_encoding($blob, 'UTF-8', 'binary');
    }
}
