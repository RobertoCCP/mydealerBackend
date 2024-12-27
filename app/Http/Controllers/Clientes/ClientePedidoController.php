<?php

namespace App\Http\Controllers\Clientes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Orden\Orden;
use App\Traits\HttpResponses;

class ClientePedidoController extends Controller
{
    use HttpResponses;
    /**
     * @OA\Get(
     *     path="/api/cliente/pedidos",
     *     tags={"Cliente - Pedidos Cliente"},
     *     summary="Obtener pedidos de un cliente",
     *     description="Devuelve una lista de pedidos filtrados por cliente, fecha y estado",
     *     @OA\Parameter(
     *         name="codcliente",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string"),
     *         description="Código del cliente"
     *     ),
     *     @OA\Parameter(
     *         name="fechaInicio",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", format="date"),
     *         description="Fecha de inicio para el filtro"
     *     ),
     *     @OA\Parameter(
     *         name="fechaFin",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", format="date"),
     *         description="Fecha de fin para el filtro"
     *     ),
     *     @OA\Parameter(
     *         name="estado",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string"),
     *         description="Estado del pedido"
     *     ),
     *  @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer", default=10),
     *         description="Cantidad de resultados por página"
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer", default=1),
     *         description="Número de página"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(
     *                     property="id",
     *                     type="integer",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="codcliente",
     *                     type="integer",
     *                     example=123
     *                 ),
     *                 @OA\Property(
     *                     property="fecha",
     *                     type="string",
     *                     format="date",
     *                     example="2023-06-16"
     *                 ),
     *                 @OA\Property(
     *                     property="estado",
     *                     type="string",
     *                     example="pendiente"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No hay datos",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No hay datos!")
     *         )
     *     )
     * )
     */
    public function getPedidosCliente(Request $request)
    {
        $codcliente = $request->input('codcliente');
        $fechaInicio = $request->input('fechaInicio');
        $fechaFin = $request->input('fechaFin');
        $estado = $request->input('estado');
        $perPage = $request->input('per_page', 30);
        $page = $request->input('page', 1);

        $query = Orden::query();

        if ($codcliente) {
            $query->where('codcliente', $codcliente);
        }
        if ($fechaInicio) {
            $query->whereDate('fecha', '>=', $fechaInicio);
        }
        if ($fechaFin) {
            $query->whereDate('fecha', '<=', $fechaFin);
        }
        if ($estado) {
            $query->where('estado', $estado);
        }

        if (!$codcliente && !$fechaInicio && !$fechaFin && !$estado) {
            $pedidos = Orden::paginate($perPage);

        } else {
            $pedidos = $query->get();
        }

        if ($pedidos->isEmpty()) {
            return $this->error('Error', 'No hay datos!', 404);
        } else {
            return $this->success($pedidos, 'Ok!!');
        }

    }
}
