<?php

namespace App\Http\Controllers\PedidosVendedor;

use App\Http\Controllers\Controller;
use App\Models\PedidosVendedor\Orden;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrdenController extends Controller
{


/**
 * @OA\Get(
 *     path="/api/ordenesR",
 *     tags={"Pedidos"},
 *     summary="Listar pedidos con filtros opcionales",
 *     description="Devuelve una lista de pedidos aplicando filtros opcionales como código de cliente, fecha de inicio, fecha de fin y estado del pedido.",
 *     @OA\Parameter(
 *         name="codcliente",
 *         in="query",
 *         description="Código del cliente para filtrar pedidos",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="fechaInicio",
 *         in="query",
 *         description="Fecha de inicio para filtrar pedidos (formato: YYYY-MM-DD)",
 *         required=false,
 *         @OA\Schema(type="string", format="date")
 *     ),
 *     @OA\Parameter(
 *         name="fechaFin",
 *         in="query",
 *         description="Fecha de fin para filtrar pedidos (formato: YYYY-MM-DD)",
 *         required=false,
 *         @OA\Schema(type="string", format="date")
 *     ),
 *     @OA\Parameter(
 *         name="estado",
 *         in="query",
 *         description="Estado del pedido (N=No enviado, E=Enviado)",
 *         required=false,
 *         @OA\Schema(type="string", enum={"N", "E"})
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Operación exitosa",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="codcliente", type="string", example="C123"),
 *                 @OA\Property(property="fecha", type="string", format="date", example="2024-07-09"),
 *                 @OA\Property(property="estado", type="string", enum={"N", "E"}, example="E"),
 *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-07-09 12:00:00"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-07-09 12:00:00")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="No se encontraron pedidos"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error de base de datos"
 *     )
 * )
 */
public function indexR(Request $request)
{
    // Aplicar filtros si se proporcionan en la solicitud
    $query = Orden::query();

    if ($request->has('codcliente')) {
        $query->where('codcliente', $request->codcliente);
    }

    if ($request->has('fechaInicio')) {
        $query->whereDate('fecha', '>=', $request->fechaInicio);
    }

    if ($request->has('fechaFin')) {
        $query->whereDate('fecha', '<=', $request->fechaFin);
    }

    if ($request->has('estado')) {
        $query->where('estado', $request->estado);
    }

    // Ordenamiento por fecha más reciente
    $query->orderBy('fecha', 'desc');

    // Paginación
    $limit = $request->input('limit', 10);
    $offset = $request->input('offset', 0);
    $orders = $query->offset($offset)->limit($limit)->get();

    return response()->json($orders);
}
     /**
     * @OA\Get(
     * path="/api/pedidosCliente/{idVendedor}/{idCliente}",
     * tags={"Vendedor - Pedidos"},
     * summary="Buscar pedidos por cliente del vendedor",
     * description="Devuelve una lista de pedidos por cliente del vendor",
     * @OA\Parameter(
     *      name="idVendedor",
     *      description="ID del vendedor",
     *      required=true,
     *      in="path",
     *      @OA\Schema(type="string")
     * ),
     * @OA\Parameter(
     *      name="idCliente",
     *      description="ID del cliente",
     *      required=false,
     *      in="path",
     *      @OA\Schema(type="string")
     * ),
     * @OA\Response(
     *     response=200,
     *     description="successful operation"
     * ),
     * @OA\Response(
     *     response=404,
     *     description="No cuenta con datos"
     * ),
     * @OA\Response(
     *     response=500,
     *     description="Database error"
     * )
     * )
     */
    public function pedidosCliente($idVendedor = '', $idCliente = '')
    {
        if($idCliente != '') {
            $result = DB::table('cliente')
                ->where('estado', 'A')
                ->where('codcliente', $idCliente)
                ->select('*')
                ->get();
        } else {
            $result = DB::table('cliente')
                ->where('estado', 'A')
                ->select('*')
                ->get();
        }

        if(count($result) > 0) {
            $w_clientes = [];
            foreach ($result as $key => $value) {
                $w_orden = [];
                $w_pedidos = DB::table('orden')
                    ->where('codcliente', $value->codcliente)
                    ->where('codvendedor', $idVendedor)
                    ->select('*')
                    ->get();
                if(count($w_pedidos) > 0) {
                    $result[$key]->pedidos = $w_pedidos;
                    array_push($w_clientes, $result[$key]);
                }
            }
            return $this->imprimirError('0', 'Ok', $w_clientes);
        } else {
            return $this->imprimirError('9998', 'No cuenta con datos');
        }
    }
    /**
     * @OA\Get(
     * path="/api/pedidosCompleto/{idVendedor}/{desde}/{hasta}/{idCliente}/{estado}",
     * tags={"Vendedor - Pedidos"},
     * summary="Buscar pedidos completos por rango de fecha para todos los clientes o filtrado por un solo cliente determinando ordenes autorizadas y pendientes",
     * description="Devuelve una lista de pedidos completos por rango de fecha para todos los clientes o filtrado por un solo cliente determinando ordenes autorizadas y pendientes",
     * @OA\Parameter(
     *      name="idVendedor",
     *      description="ID del vendedor",
     *      required=true,
     *      in="path",
     *      @OA\Schema(type="string")
     * ),
     * @OA\Parameter(
     *      name="desde",
     *      description="Fecha de inicio",
     *      required=false,
     *      in="path",
     *      @OA\Schema(type="string", format="date")
     * ),
     * @OA\Parameter(
     *      name="hasta",
     *      description="Fecha de fin",
     *      required=false,
     *      in="path",
     *      @OA\Schema(type="string", format="date")
     * ),
     * @OA\Parameter(
     *      name="idCliente",
     *      description="ID del cliente",
     *      required=false,
     *      in="path",
     *      @OA\Schema(type="string")
     * ),
     * @OA\Parameter(
     *      name="estado",
     *      description="Estado del pedido",
     *      required=false,
     *      in="path",
     *      @OA\Schema(type="string")
     * ),
     * @OA\Response(
     *     response=200,
     *     description="successful operation"
     * ),
     * @OA\Response(
     *     response=404,
     *     description="No cuenta con datos"
     * ),
     * @OA\Response(
     *     response=500,
     *     description="Database error"
     * )
     * )
     */
    public function pedidosCompleto($idVendedor = '', $desde = '',  $hasta = '', $idCliente = 'TODOS', $estado = 'E')
    {
        if ($idVendedor == '') {
            return $this->imprimirError('9999', 'Falta el campo Vendedor');
        }

        $query = DB::table('orden')
            ->join('cliente', 'orden.codcliente', '=', 'cliente.codcliente')
            ->where('orden.codvendedor', $idVendedor)
            ->where('orden.fecha', '>=', $desde == '' ? date('Y-m-d') : $desde)
            ->where('orden.fecha', '<=', $hasta == '' ? date('Y-m-d') : $hasta)
            ->where('orden.estado', $estado);
            if ($idCliente != 'TODOS') {
                $query->where('cliente.codcliente', $idCliente);
            }

            $result = $query->select('orden.*', 'orden.estado as orden_estado',
            'cliente.codcliente as cliente_codcliente',
            'cliente.nombre as cliente_nombre',
            'cliente.email as cliente_email',
            'cliente.pais as cliente_pais',
            'cliente.ciudad as cliente_ciudad',
            'cliente.estado as cliente_estado',
            'cliente.cedularuc as cliente_cedularuc',
            'cliente.codlistaprecio as cliente_codlistaprecio',
            'cliente.calificacion as cliente_calificacion'
            )->get();
        if (count($result) > 0) {
            foreach ($result as $key => $value) {
                $detalles = DB::table('ordendet')
                    ->join('producto', 'producto.codproducto', '=', 'ordendet.codproducto')
                    ->where('ordendet.idorden', $value->idorden)
                    ->select('ordendet.*', 'producto.*')
                    ->get();
                $result[$key]->detalles = $detalles;
            }
            return $this->imprimirError('0', 'Ok', $result);
        } else {
            return $this->imprimirError('9998', 'No cuenta con datos');
        }
    }
    /**
     * @OA\Get(
     * path="/api/pedidosTodosCliente/{idVendedor}/{idCliente}",
     * tags={"Vendedor - Pedidos"},
     * summary="Buscar todos los pedidos que hay en general por cliente del vendedor",
     * description="Devuelve una lista de todos los pedidos que hay en general por cliente del vendedor",
     * @OA\Parameter(
     *      name="idVendedor",
     *      description="ID del vendedor",
     *      required=true,
     *      in="path",
     *      @OA\Schema(type="string")
     * ),
     * @OA\Parameter(
     *      name="idCliente",
     *      description="ID del cliente",
     *      required=false,
     *      in="path",
     *      @OA\Schema(type="string")
     * ),
     * @OA\Response(
     *     response=200,
     *     description="successful operation"
     * ),
     * @OA\Response(
     *     response=404,
     *     description="No cuenta con datos"
     * ),
     * @OA\Response(
     *     response=500,
     *     description="Database error"
     * )
     * )
     */
    public function pedidosTodosCliente($idVendedor = '', $idCliente = '')
    {
        if($idCliente != '') {
            $result = DB::table('orden')
                ->where('codcliente', $idCliente)
                ->where('codvendedor', $idVendedor)
                ->select('*')
                ->get();
        } else {
            $result = DB::table('orden')
                ->where('codvendedor', $idVendedor)
                ->select('*')
                ->get();
        }

        if(count($result) > 0) {
            return $this->imprimirError('0', 'Ok', $result);
        } else {
            return $this->imprimirError('9998', 'No cuenta con datos');
        }
    }
    /**
     * @OA\Get(
     * path="/api/pedidosRangoFecha/{idVendedor}/{desde}/{hasta}",
     * tags={"Vendedor - Pedidos"},
     * summary="Buscar pedidos por rango de fecha",
     * description="Devuelve una lista de pedidos por rango de fecha",
     * @OA\Parameter(
     *      name="idVendedor",
     *      description="ID del vendedor",
     *      required=true,
     *      in="path",
     *      @OA\Schema(type="string")
     * ),
     * @OA\Parameter(
     *      name="desde",
     *      description="Fecha de inicio",
     *      required=true,
     *      in="path",
     *      @OA\Schema(type="string", format="date")
     * ),
     * @OA\Parameter(
     *      name="hasta",
     *      description="Fecha de fin",
     *      required=true,
     *      in="path",
     *      @OA\Schema(type="string", format="date")
     * ),
     * @OA\Response(
     *     response=200,
     *     description="successful operation"
     * ),
     * @OA\Response(
     *     response=404,
     *     description="No cuenta con datos"
     * ),
     * @OA\Response(
     *     response=500,
     *     description="Database error"
     * )
     * )
     */
    public function pedidosRangoFecha($idVendedor = '', $desde = '', $hasta = '')
    {
        $result = DB::table('orden')
            ->where('codvendedor', $idVendedor)
            ->where('fecha', '>=', $desde)
            ->where('fecha', '<=', $hasta)
            ->select('*')
            ->get();

        if(count($result) > 0) {
            return $this->imprimirError('0', 'Ok', $result);
        } else {
            return $this->imprimirError('9998', 'No cuenta con datos');
        }
    }

/**
 * @OA\Get(
 *     path="/api/pedidosEstado/{idVendedor}/{estado}",
 *     tags={"Vendedor - Pedidos"},
 *     summary="Obtener pedidos por estado y vendedor",
 *     @OA\Parameter(
 *         name="idVendedor",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="string"),
 *         description="ID del vendedor"
 *     ),
 *     @OA\Parameter(
 *         name="estado",
 *         in="path",
 *         required=false,
 *         @OA\Schema(type="string", default="E"),
 *         description="Estado del pedido (por defecto 'E')"
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Operación exitosa",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="srorden", type="integer"),
 *                 @OA\Property(property="estado", type="string"),
 *                 @OA\Property(property="cliente_codcliente", type="string"),
 *                 @OA\Property(property="cliente_nombre", type="string"),
 *                 @OA\Property(property="cliente_email", type="string"),
 *                 @OA\Property(property="cliente_pais", type="string"),
 *                 @OA\Property(property="cliente_ciudad", type="string"),
 *                 @OA\Property(property="cliente_estado", type="string"),
 *                 @OA\Property(property="cliente_cedularuc", type="string"),
 *                 @OA\Property(property="cliente_codlistaprecio", type="string"),
 *                 @OA\Property(property="cliente_calificacion", type="string"),
 *                 @OA\Property(
 *                     property="detalles",
 *                     type="array",
 *                     @OA\Items(
 *                         @OA\Property(property="codproducto", type="string"),
 *                         @OA\Property(property="cantidad", type="integer"),
 *                         @OA\Property(property="precio", type="number", format="float")
 *                     )
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Falta el campo Vendedor"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="No cuenta con datos"
 *     )
 * )
 */
     public function pedidosEstado($idVendedor, $estado = 'E')
     {
    if ($idVendedor == '') {
        return $this->imprimirError('9999', 'Falta el campo Vendedor');
    }

    $query = DB::table('orden')
        ->join('cliente', 'orden.codcliente', '=', 'cliente.codcliente')
        ->where('orden.codvendedor', $idVendedor)
        ->where('orden.estado', $estado);

        $result = $query->select('orden.*', 'orden.estado as orden_estado',
        'cliente.codcliente as cliente_codcliente',
        'cliente.nombre as cliente_nombre',
        'cliente.email as cliente_email',
        'cliente.pais as cliente_pais',
        'cliente.ciudad as cliente_ciudad',
        'cliente.estado as cliente_estado',
        'cliente.cedularuc as cliente_cedularuc',
        'cliente.codlistaprecio as cliente_codlistaprecio',
        'cliente.calificacion as cliente_calificacion'
        )->get();
    if (count($result) > 0) {
        foreach ($result as $key => $value) {
            $detalles = DB::table('ordendet')
                ->join('producto', 'producto.codproducto', '=', 'ordendet.codproducto')
                ->where('ordendet.srorden', $value->srorden)
                ->select('ordendet.*', 'producto.*')
                ->get();
            $result[$key]->detalles = $detalles;
        }
        return $this->imprimirError('0', 'Ok', $result);
    } else {
        return $this->imprimirError('9998', 'No cuenta con datos');
    }
}

/**
 * @OA\Get(
 *     path="/api/pedidosTodos/{idVendedor}",
 *     tags={"Vendedor - Pedidos"},
 *     summary="Obtener todos los pedidos de un vendedor",
 *     @OA\Parameter(
 *         name="idVendedor",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="string"),
 *         description="ID del vendedor"
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Operación exitosa",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="srorden", type="integer"),
 *                 @OA\Property(property="estado", type="string"),
 *                 @OA\Property(property="cliente_codcliente", type="string"),
 *                 @OA\Property(property="cliente_nombre", type="string"),
 *                 @OA\Property(property="cliente_email", type="string"),
 *                 @OA\Property(property="cliente_pais", type="string"),
 *                 @OA\Property(property="cliente_ciudad", type="string"),
 *                 @OA\Property(property="cliente_estado", type="string"),
 *                 @OA\Property(property="cliente_cedularuc", type="string"),
 *                 @OA\Property(property="cliente_codlistaprecio", type="string"),
 *                 @OA\Property(property="cliente_calificacion", type="string"),
 *                 @OA\Property(
 *                     property="detalles",
 *                     type="array",
 *                     @OA\Items(
 *                         @OA\Property(property="codproducto", type="string"),
 *                         @OA\Property(property="cantidad", type="integer"),
 *                         @OA\Property(property="precio", type="number", format="float")
 *                     )
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Falta el campo Vendedor"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="No cuenta con datos"
 *     )
 * )
 */
public function pedidosTodos($idVendedor)
{
if ($idVendedor == '') {
   return $this->imprimirError('9999', 'Falta el campo Vendedor');
}

$query = DB::table('orden')
   ->join('cliente', 'orden.codcliente', '=', 'cliente.codcliente')
   ->where('orden.codvendedor', $idVendedor);

   $result = $query->select('orden.*', 'orden.estado as orden_estado',
   'cliente.codcliente as cliente_codcliente',
   'cliente.nombre as cliente_nombre',
   'cliente.email as cliente_email',
   'cliente.pais as cliente_pais',
   'cliente.ciudad as cliente_ciudad',
   'cliente.estado as cliente_estado',
   'cliente.cedularuc as cliente_cedularuc',
   'cliente.codlistaprecio as cliente_codlistaprecio',
   'cliente.calificacion as cliente_calificacion'
   )->get();
if (count($result) > 0) {
   foreach ($result as $key => $value) {
       $detalles = DB::table('ordendet')
           ->join('producto', 'producto.codproducto', '=', 'ordendet.codproducto')
           ->where('ordendet.srorden', $value->srorden)
           ->select('ordendet.*', 'producto.*')
           ->get();
       $result[$key]->detalles = $detalles;
   }
   return $this->imprimirError('0', 'Ok', $result);
} else {
   return $this->imprimirError('9998', 'No cuenta con datos');
}
}

    function imprimirError($error, $mensaje, $i_datos = null)
    {
        if (isset($i_datos)) {
            return [
                "error" => $error,
                "mensaje" => $mensaje,
                "datos" => $i_datos
            ];
        } else {
            return [
                "error" => $error,
                "mensaje" => $mensaje
            ];
        }
    }

    public function index()
    {
        $ordens = Orden::all();
        return view('ordens.index', compact('ordens'));
    }

    public function create()
    {
        return view('ordens.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            // Validations here
        ]);

        Orden::create($request->all());
        return redirect()->route('ordens.index')->with('success', 'Order created successfully.');
    }

    public function show(Orden $orden)
    {
        return view('ordens.show', compact('orden'));
    }

    public function edit(Orden $orden)
    {
        return view('ordens.edit', compact('orden'));
    }

    public function update(Request $request, Orden $orden)
    {
        $request->validate([
            // Validations here
        ]);

        $orden->update($request->all());
        return redirect()->route('ordens.index')->with('success', 'Order updated successfully.');
    }

    public function destroy(Orden $orden)
    {
        $orden->delete();
        return redirect()->route('ordens.index')->with('success', 'Order deleted successfully.');
    }


}
