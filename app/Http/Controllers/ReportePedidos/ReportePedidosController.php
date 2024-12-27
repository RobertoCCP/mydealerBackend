<?php

namespace App\Http\Controllers\ReportePedidos;

use App\Http\Controllers\Controller;
use App\Models\PedidosVendedor\Orden;
use App\Http\Requests\Reportes\Pedidos\ReportePedidosRequest;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\DB;

class ReportePedidosController extends Controller {

    use HttpResponses;

    /**
     * @OA\Post(
     * path="/api/reporte/pedidos",
     * tags={"Web - Reporte Pedido"},
     * summary="Reporte de pedidos",
     * description="Devuelve una lista de reportes de pedidos de acuerdo a los filtros aplicados",
     * @OA\RequestBody(
     * description="Datos del parámetros a buscar",
     *        required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="cod_sucursal", type="string", description="Código de la sucursal"),
     *             @OA\Property(property="cod_vendedor", type="string", description="Código del vendedor"),
     *             @OA\Property(property="cliente", type="string", description="Nombre del cliente"),
     *             @OA\Property(property="estado_autorizacion", type="string", description="Estado de autorización"),
     *             @OA\Property(property="cod_motivo_rechazo", type="string", description="Código del motivo de rechazo"),
     *             @OA\Property(property="fecha_inicio", type="date", description="Fecha de inicio"),
     *             @OA\Property(property="fecha_fin", type="date", description="Fecha de fin"),
     *             @OA\Property(property="tipo_pedido", type="string", description="Código del tipo de producto"),
     *             @OA\Property(property="origen", type="string", description="Origen del pedido"),
     *             @OA\Property(property="tipo_entrega", type="string", description="Tipo de entrega"),
     *             @OA\Property(property="no_renegociado", type="boolean", description="Define si el pedido fue renegociado")
     *         )
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
    public function index(ReportePedidosRequest $request) {
        $request->validated();

        $params = $request->all();

        $key_word = 'Todos';

        $reportes = Orden::join('cliente', 'orden.codcliente', '=', 'cliente.codcliente')
            ->join('ordeneslog', 'orden.srorden', '=', 'ordeneslog.srorden')
            ->where('orden.fecha', '>=', $params['fecha_inicio'])
            ->where('orden.fecha', '<=', $params['fecha_fin']);


        if ($params['cod_sucursal'] && $params['cod_sucursal'] !== $key_word) {
            $reportes =  $reportes->where('orden.codsucursal', $params['cod_sucursal']);
        }

        if ($params['cod_vendedor'] && $params['cod_vendedor'] !== $key_word) {
            $reportes =  $reportes->where('orden.codvendedor', $params['cod_vendedor']);
        }

        if ($params['origen'] && $params['origen'] !== $key_word) {
            $reportes =  $reportes->where('orden.origen', $params['origen']);
        }

        if ($params['tipo_pedido'] && $params['tipo_pedido'] !== $key_word) {
            $reportes =  $reportes->where('orden.tipopedido', $params['tipo_pedido']);
        }

        if ($params['tipo_entrega'] && $params['tipo_entrega'] !== $key_word) {
            $reportes =  $reportes->where('orden.entregapedido', $params['tipo_entrega']);
        }

        if (in_array('no_renegociado', $params)) {
            $reportes =  $reportes->where('orden.pedido_renegociado', null);
        }

        if ($params['cod_motivo_rechazo'] && $params['cod_motivo_rechazo'] !== $key_word) {
            $reportes = $reportes->where('ordeneslog.codmotrechazo_com', '=', $params['cod_motivo_rechazo']);
        }

        if ($params['estado_autorizacion'] && $params['estado_autorizacion'] !== $key_word) {
            $reportes = $reportes->where('ordeneslog.estado_AprCred', '=', $params['estado_autorizacion']);
        }

        if (in_array('cliente', $params) && $params['cliente'] !== $key_word) {
            $reportes = $reportes->where('cliente.nombre', 'like', '%' . $params['cliente'] . '%');
        }

        $reportes = $reportes->select(
            'orden.*',
            'ordeneslog.*',
            'cliente.codcliente',
            'cliente.nombre as nombre_cliente',
        )->orderBy('orden.fecha', 'desc')->take(1000)->get();

        $reportes = $reportes->map(fn ($reporte) => $this->mapearCampo($reporte));

        return $this->success($reportes);
    }


    /**
     * @OA\Get(
     * path="/api/reporte/pedidos/{orden}",
     * tags={"Web - Reporte Pedido"},
     * summary="Reporte de pedidos",
     * description="Devuelve un reporte de pedidos de acuerdo al número de orden",
     * @OA\Parameter(
     *      description="Código de la orden",
     *      in="path",
     *      name="orden",
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
    public function show(int $orden) {
        $orden = Orden::join('cliente', 'orden.codcliente', '=', 'cliente.codcliente')
            ->join('ordeneslog', 'orden.srorden', '=', 'ordeneslog.srorden')
            ->where('orden.srorden', $orden)->select(
                'orden.*',
                'ordeneslog.*',
                'cliente.codcliente',
                'cliente.nombre as nombre_cliente',
            )->orderBy('orden.fecha', 'desc')->get();

        $orden = $orden->map(fn ($reporte) => $this->mapearCampo($reporte));

        return $this->success($orden);
    }


    private function mapearCampo($reporte) {
        if ($reporte->codmotrechazo_com !== null) {
            $consulta = DB::select('SELECT nombre as motivo_rechazo FROM  motrechazo WHERE codmotivo = ?', [$reporte->codmotrechazo_com]);
            if (count($consulta) > 0) {
                $reporte->motivo_rechazo = $consulta[0]->motivo_rechazo;
            }
        }

        if ($reporte->estado_AprCred !== null) {
            $consulta = DB::select('SELECT descripcion as estado_autorizacion FROM  estados_aprobacion WHERE codestadoaprob = ?', [$reporte->estado_AprCred]);
            if (count($consulta) > 0) {
                $reporte->estado_autorizacion = $consulta[0]->estado_autorizacion;
            }
        }

        $reporte->reporte_detalle = $reporte
            ->join('ordendet', 'orden.srorden', '=', 'ordendet.srorden')
            ->where('ordendet.srorden', $reporte->srorden)
            ->select('ordendet.*')->get();

        return $reporte;
    }
}
