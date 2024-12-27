<?php

namespace App\Http\Controllers\Pedido;

use App\Models\Orden\Orden;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Carrito\Carrito;
use App\Models\Orden\Ordendet;
use App\Models\Vendedor\OrdenesLog;
use App\Traits\HttpResponses;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class PedidoController extends Controller
{
    use HttpResponses;
    /**
 * @OA\Get(
 *     path="/api/cliente/pedidos/{srorden}",
 *     tags={"Cliente - Pedidos Cliente"},
 *     summary="Obtener detalles de un pedido",
 *     description="Devuelve los detalles de un pedido específico junto con sus detalles y logs",
 *     @OA\Parameter(
 *         name="srorden",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer"),
 *         description="Número de orden"
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Operación exitosa",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="id",
 *                 type="integer",
 *                 example=1,
 *                 description="ID de la orden"
 *             ),
 *             @OA\Property(
 *                 property="codcliente",
 *                 type="integer",
 *                 example=123,
 *                 description="Código del cliente"
 *             ),
 *             @OA\Property(
 *                 property="fecha",
 *                 type="string",
 *                 format="date",
 *                 example="2023-06-16",
 *                 description="Fecha de la orden"
 *             ),
 *             @OA\Property(
 *                 property="estado",
 *                 type="string",
 *                 example="pendiente",
 *                 description="Estado de la orden"
 *             ),
 *             @OA\Property(
 *                 property="detalles",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(
 *                         property="detalle_id",
 *                         type="integer",
 *                         example=1,
 *                         description="ID del detalle"
 *                     ),
 *                     @OA\Property(
 *                         property="producto",
 *                         type="string",
 *                         example="Producto A",
 *                         description="Nombre del producto"
 *                     ),
 *                     @OA\Property(
 *                         property="cantidad",
 *                         type="integer",
 *                         example=2,
 *                         description="Cantidad del producto"
 *                     ),
 *                     @OA\Property(
 *                         property="precio",
 *                         type="number",
 *                         format="float",
 *                         example=19.99,
 *                         description="Precio del producto"
 *                     )
 *                 )
 *             ),
 *             @OA\Property(
 *                 property="logs",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(
 *                         property="log_id",
 *                         type="integer",
 *                         example=1,
 *                         description="ID del log"
 *                     ),
 *                     @OA\Property(
 *                         property="accion",
 *                         type="string",
 *                         example="Creación",
 *                         description="Acción realizada"
 *                     ),
 *                     @OA\Property(
 *                         property="fecha",
 *                         type="string",
 *                         format="date-time",
 *                         example="2023-06-16T14:00:00Z",
 *                         description="Fecha y hora de la acción"
 *                     )
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="No hay datos",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="No se encontraron pedidos.")
 *         )
 *     )
 * )
 */
    public function getPedido($srorden)
    {
        $orden = Orden::with(['detalles', 'logs'])->find($srorden);

        if (!$orden) {
            return $this->error('No data', 'No se encontraron pedidos.', 404);
        }

        return $this->success($orden,'Ordenes encontradas!!');
    }
     /**
     * @OA\Post(
     *     path="/api/cliente/realizarpedido",
     *     summary="El cliente puede realizar un pedido",
     *     tags={"Cliente - Pedidos Cliente"},
     *   
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"codformaenvio", "codformapago", "coddireccionenvio", "fechamovil",  "codcliente", "origen", "observaciones"},
     *             @OA\Property(property="codformaenvio", type="string"),
     *             @OA\Property(property="codformapago", type="string"),
     *             @OA\Property(property="coddireccionenvio", type="string"),
     *             @OA\Property(property="fechamovil", type="string"),
     *             @OA\Property(property="codcliente", type="string"),
     *             @OA\Property(property="origen", type="string"),
     *             @OA\Property(property="observaciones", type="string"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Orden creada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="codcliente", type="string"),
     *                 @OA\Property(property="fecha", type="string"),
     *                 @OA\Property(property="fechaweb", type="string"),
     *                 @OA\Property(property="idorden", type="string"),
     *                 @OA\Property(property="codformaenvio", type="string"),
     *                 @OA\Property(property="codformapago", type="string"),
     *                 @OA\Property(property="coddireccionenvio", type="string"),
     *                 @OA\Property(property="observaciones", type="string"),
     *                 @OA\Property(property="descuento", type="number"),
     *                 @OA\Property(property="impuesto", type="number"),
     *                 @OA\Property(property="origen", type="string"),
     *                 @OA\Property(property="fechamovil", type="string"),
     *                 @OA\Property(property="subtotal", type="number"),
     *                 @OA\Property(property="total", type="number")
     *             ),
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontró ese código del cliente en el carrito",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function create(Request $request)
    {
        $codcliente=$request->codcliente;
        $cart = Carrito::where('codcliente', $codcliente)
            ->where('is_checked', 1)
            ->get();

  
        if ($cart->count()==0) {
            return $this->error('No hay data', 'No hay productos marcados en el carrito asociados al cliente', 404);
        }

        $descuento = $cart->sum('descuento');
        $impuesto = $cart->sum('impuesto');

        // Validar datos
        $validate = Validator::make($request->all(), [
            'codcliente' => 'required|exists:cliente,codcliente',
            'codformaenvio' => 'required|exists:formaenvio,codformaenvio',
            'codformapago' => 'required|exists:formapago,codformapago',
            "coddireccionenvio" => 'required|exists:direccionenvio,coddireccionenvio',
            "fechamovil" => 'required',
            "observaciones" => 'required',
            'origen' => 'required',
        ]);

        if ($validate->fails()) {
            return $this->error($validate->errors()->first(), '', 404);
        }

        // Crear orden
        $orden = Orden::create([
            'codcliente' => $codcliente,
            'fecha' => date('Y-m-d'),
            'fechaweb' => Carbon::now()->format('Y-m-d H:i:s'),
            'idorden' => '1',
            'codformaenvio' => $request->codformaenvio,
            'codformapago' => $request->codformapago,
            'coddireccionenvio' => $request->coddireccionenvio,
            'observaciones' => $request->observaciones,
            'descuento' => $descuento,
            'impuesto' => $impuesto,
            'origen' => $request->origen,
            'fechamovil' => $request->fechamovil,
            'estado' => 'N',
            'loginusuario'=>$codcliente,
            'referencia1'=>$codcliente
        ]);

        $orden->update([
            'idorden' => $orden->fechaweb . '.' . $codcliente,
        ]);

        $subtotalOrden = 0;
        $totalOrden = 0;

        foreach ($cart as $item) {

            $subtotal = $item->precio * $item->cantidad - $item->descuento;
            $total = $subtotal + $item->impuesto;

            $orderDetail = Ordendet::create([
                'idorden' => $orden->idorden,
                'codproducto' => $item->codproducto,
                'cantidad' => $item->cantidad,
                'precio' => $item->precio,
                'descuento' => $item->descuento,
                'descuentoval' => $item->descuento,
                'porcdescuentotal' => $item->porcdescuento,
                'impuesto' => $item->impuesto,
                'subtotal' => $subtotal,
                'total' => $total,
                'porcimpuesto' => $item->porcimpuesto,
            ]);
            $subtotalOrden += $subtotal;
            $totalOrden += $total;
        }

        // Update orden
        $orden->update([
            'subtotal' =>  $subtotalOrden,
            'total' => $totalOrden,
        ]);

        // Log
        OrdenesLog::create([
            'srorden' => $orden->srorden,
            'fecha' =>  Carbon::now(),
            'estado_AprCom'=>'P',
            'estado'=>'N'
        ]);

        // Eliminar esos productos de mi carrito
        $productosAEliminar = $cart->pluck('codproducto')->toArray();
        Carrito::whereIn('codproducto', $productosAEliminar)->delete();

        return $this->success($orden, 'Ok!!');
    }
}
