<?php

namespace App\Http\Controllers\Carrito;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Carrito\Carrito;
use App\Models\Producto;
use App\Models\BodegaStock;
use App\Models\Invitado\Invitado;
use App\Models\Cliente\Cliente;
use App\Traits\HttpResponses;
use Illuminate\Support\Str;

class CarritoController extends Controller
{
    use HttpResponses;

    /**
     * @OA\Post(
     * path="/api/carrito/agregar",
     * tags={"Cliente - Agregar productos al carrito"},
     * summary="Agregar productos al carrito",
     * description="Permite agregar productos al carrito de compras",
     * @OA\RequestBody(
     *     required=true,
     *     description="Datos para agregar productos al carrito",
     *     @OA\JsonContent(
     *         required={"codproducto", "idinvitado", "cantidad"},
     *         @OA\Property(property="codproducto", type="string", example="PRO15"),
     *         @OA\Property(property="idinvitado", type="string", example="INV54"),
     *         @OA\Property(property="cantidad", type="integer", example=4),
     *     ),
     * ),
     * @OA\Response(
     *     response=201,
     *     description="Producto agregado al carrito correctamente",
     *     @OA\JsonContent(
     *         @OA\Property(property="estado", type="integer", example=1),
     *         @OA\Property(property="codcarrito", type="integer", example=377),
     *         @OA\Property(property="carrito", type="object",
     *             @OA\Property(property="codcarrito", type="integer", example=377),
     *             @OA\Property(property="idcliente", type="string", example="INV54"),
     *             @OA\Property(property="es_invitado", type="integer", example=1),
     *             @OA\Property(property="idgrupocarrito", type="string", example="cart-TPRO01-INV54"),
     *             @OA\Property(property="codproducto", type="string", example="PRO64"),
     *             @OA\Property(property="descripciontipoproducto", type="string", example="Tipoproducto"),
     *             @OA\Property(property="cantidad", type="integer", example=3),
     *             @OA\Property(property="precio", type="number", format="float", example=100.00),
     *             @OA\Property(property="descuento", type="number", format="float", example=10.00),
     *             @OA\Property(property="impuesto", type="number", format="float", example=15.00),
     *             @OA\Property(property="porcdescuento", type="number", format="float", example=10.00),
     *             @OA\Property(property="porcimpuesto", type="number", format="float", example=15.00),
     *             @OA\Property(property="nombremarca", type="string", example="MARCA"),
     *             @OA\Property(property="is_checked", type="integer", example=0),
     *             @OA\Property(property="slug", type="string", example="6-fit-tripod-stand-for-mobile-6-fit-tripod-stand-6"),
     *             @OA\Property(property="nombre", type="string", example="6 fit tripod stand for mobile 6 Fit Tripod Stand 6"),
     *             @OA\Property(property="imagen", type="string", example="2023-06-10-64844951aad7c.png"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-06-18T02:10:17.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-06-18T02:16:24.000000Z"),
     *             @OA\Property(property="nombregrupo", type="string", example="TPRO01"),
     *         ),
     *         @OA\Property(property="mensaje", type="string", example="Producto agregado al carrito correctamente")
     *     )
     * ),
     * @OA\Response(
     *     response=400,
     *     description="Solicitud inválida",
     *     @OA\JsonContent(
     *         @OA\Property(property="estado", type="integer", example=0),
     *         @OA\Property(property="mensaje", type="string", example="Datos de solicitud no válidos")
     *     )
     * ),
     * @OA\Response(
     *     response=500,
     *     description="Error del servidor",
     *     @OA\JsonContent(
     *         @OA\Property(property="estado", type="integer", example=0),
     *         @OA\Property(property="mensaje", type="string", example="Error al procesar la solicitud")
     *     )
     * )
     * )
     */
    public function agregarProducto(Request $request)
    {
        $request->validate([
            'codproducto' => 'required|string|max:30',
            'idinvitado' => 'required|string',
            'cantidad' => 'required|integer|min:1'
        ]);

        $producto = Producto::where('codproducto', $request->codproducto)->first();

        if (!$producto) {
            return response()->json(['error' => 'Producto no encontrado'], 404);
        }

        $cliente = Cliente::where('codcliente', $request->idinvitado)->first();
        $invitado = Invitado::where('idinvitado', $request->idinvitado)->first();
        $usuario = '';
        if (!$cliente && !$invitado) {
            return response()->json(['error' => 'Datos no encontrados'], 404);
        }

        if ($cliente) {
            $usuario = $cliente->codcliente;
        } else {
            $usuario = $invitado->idinvitado;
        }

        $carrito = Carrito::where('codproducto', $request->codproducto)
            ->where('codcliente', $cliente->codcliente)
            ->first();

        $precioProducto = $producto->costo;
        $descuento = $precioProducto * ($producto->porcdescuento / 100);
        $impuesto = $precioProducto * ($producto->porcimpuesto / 100);

        if ($carrito) {
            // Si el producto ya está en el carrito, actualizar la cantidad
            $carrito->cantidad += $request->cantidad;
            $carrito->descuento = $descuento;
            $carrito->impuesto = $impuesto;
            $carrito->save();

            return response()->json(['message' => 'Cantidad del producto actualizada en el carrito', 'carrito' => $carrito], 200);
        } else {
            $carrito = new Carrito();
            $carrito->codcliente = $cliente ? $cliente->codcliente : null; // Asigna el id según si es invitado o cliente
            $carrito->idinvitado = $invitado ? $invitado->idinvitado : null;
            $carrito->es_invitado = $invitado ? 1 : 0; // Marca como invitado si existe en tabla Invitado
            $carrito->codproducto = $request->codproducto;
            $carrito->cantidad = $request->cantidad;
            $carrito->precio = $producto->costo;
            $carrito->descuento = $descuento;
            $carrito->impuesto = $impuesto;
            $carrito->porcdescuento = $producto->porcdescuento;
            $carrito->porcimpuesto = $producto->porcimpuesto;

            if ($carrito->save()) {
                return response()->json(['message' => 'Producto agregado al carrito correctamente', 'carrito' => $carrito], 201);
            } else {
                return response()->json(['error' => 'Error al agregar producto al carrito'], 500);
            }
        }
    }


    /**
     * @OA\Get(
     *     path="/api/carrito",
     *     summary="Listar productos del carrito",
     *     tags={"Cliente - Listar productos carrito"},
     *     @OA\Parameter(
     *         name="idInvitado",
     *         in="query",
     *         description="ID del invitado",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Productos listados exitosamente",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="codcarrito", type="integer", example=1),
     *                 @OA\Property(property="idcliente", type="string", example="INV54"),
     *                 @OA\Property(property="es_invitado", type="boolean", example=true),
     *                 @OA\Property(property="idgrupocarrito", type="string", example="cart-producto-INV54"),
     *                 @OA\Property(property="codproducto", type="string", example="12345"),
     *                 @OA\Property(property="descripciontipoproducto", type="string", example="Electrónica"),
     *                 @OA\Property(property="cantidad", type="integer", example=2),
     *                 @OA\Property(property="precio", type="number", format="float", example=99.99),
     *                 @OA\Property(property="descuento", type="number", format="float", example=5.00),
     *                 @OA\Property(property="impuesto", type="number", format="float", example=2.00),
     *                 @OA\Property(property="porcdescuento", type="number", format="float", example=5.00),
     *                 @OA\Property(property="porcimpuesto", type="number", format="float", example=12.00),
     *                 @OA\Property(property="subtotal", type="number", format="float", example=189.98),
     *                 @OA\Property(property="total", type="number", format="float", example=191.98),
     *                 @OA\Property(property="nombremarca", type="string", example="MarcaX"),
     *                 @OA\Property(property="is_checked", type="boolean", example=false),
     *                 @OA\Property(property="slug", type="string", example="producto-x"),
     *                 @OA\Property(property="nombreproducto", type="string", example="Producto X"),
     *                 @OA\Property(property="imagen", type="string", example="url_imagen"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-07-07T12:34:56.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-07-07T12:34:56.000000Z"),
     *                 @OA\Property(property="nombregrupo", type="string", example="Electrónica"),
     *                 @OA\Property(property="esta_producto_disponible", type="boolean", example=true),
     *                 @OA\Property(
     *                     property="producto",
     *                     type="object",
     *                     @OA\Property(property="codproducto", type="string", example="12345"),
     *                     @OA\Property(property="umv", type="string", example="unidad"),
     *                     @OA\Property(property="total_actual_stock", type="number", example=100)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="El parámetro idInvitado es obligatorio",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="El parámetro guest_id es obligatorio")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontraron productos en el carrito para el invitado dado",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="No se encontraron productos en el carrito para el invitado dado")
     *         )
     *     )
     * )
     */
    public function listarProductosCarrito(Request $request)
    {
        $idinvitado = $request->query('idInvitado');

        if (!$idinvitado) {
            return response()->json(['error' => 'El parámetro guest_id es obligatorio'], 400);
        }

        $productosCarrito = Carrito::where('idinvitado', $idinvitado)->get();
        $productosCarrito = Carrito::where('codcliente', $idinvitado)->get();

        if ($productosCarrito->isEmpty()) {
            return response()->json(['error' => 'No se encontraron productos en el carrito para el invitado dado'], 404);
        }

        $response = $productosCarrito->map(function ($carrito) {
            $producto = Producto::where('codproducto', $carrito->codproducto)->first();
            $productoStock = BodegaStock::where('codproducto', $carrito->codproducto)->first();
            return [
                'codcarrito' => $carrito->id,
                'idcliente' => $carrito->codcliente,
                'es_invitado' => $carrito->es_invitado,
                'idgrupocarrito' => "cart-{$producto->tipo?->descripcion}-{$carrito->idinvitado}",
                'codproducto' => $carrito->codproducto,
                'tipoproducto' => $producto->tipo?->descripcion,
                'cantidad' => $carrito->cantidad,
                'precio' => $carrito->precio,
                'descuento' => $carrito->descuento,
                'impuesto' => $carrito->impuesto,
                'porcdescuento' => $carrito->porcdescuento,
                'porcimpuesto' => $carrito->porcimpuesto,
                'subtotal' => ($carrito->precio * $carrito->cantidad) - $carrito->descuento,
                'total' => (($carrito->precio * $carrito->cantidad) - $carrito->descuento) + $carrito->impuesto,
                'marca' => $producto->marca?->nombre,
                'is_checked' => $carrito->is_checked,
                'slug' => Str::slug($producto->nombre),
                'nombre' => $producto->nombre,
                'imagen' => $producto->imagen,
                'created_at' => $carrito->created_at,
                'updated_at' => $carrito->updated_at,
                'nombregrupo' => $producto->tipo?->descripcion,
                'esta_producto_disponible' => $producto->stocks->sum('stock') > 0 ? 1 : 0,
                'producto' => [
                    'codproducto' => $producto->codproducto,
                    'umv' => $producto->umv,
                    'total_actual_stock' => $productoStock,
                ],
            ];
        });

        return response()->json($response, 200);
    }

    /**
     * @OA\Put(
     *     path="/api/carrito/actualizar",
     *     summary="Actualizar cantidad de producto en el carrito",
     *     tags={"Cliente - Actualizar cantidad de productos del carrito"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"codproducto", "cantidad", "idinvitado"},
     *             @OA\Property(property="codproducto", type="string", example="12345"),
     *             @OA\Property(property="cantidad", type="integer", example=2),
     *             @OA\Property(property="idinvitado", type="string", example="INV54")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cantidad actualizada correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="estado", type="integer", example=1),
     *             @OA\Property(property="cantidad", type="integer", example=2),
     *             @OA\Property(property="mensaje", type="string", example="Cantidad actualizada correctamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontró el producto en el carrito del invitado",
     *         @OA\JsonContent(
     *             @OA\Property(property="estado", type="integer", example=0),
     *             @OA\Property(property="mensaje", type="string", example="No se encontró el producto en el carrito del invitado")
     *         )
     *     )
     * )
     */
    public function actualizarCantidad(Request $request)
    {
        $request->validate([
            'codproducto' => 'required|string',
            'cantidad' => 'required|integer|min:1',
            'idinvitado' => 'required|string',
        ]);

        $codproducto = $request->input('codproducto');
        $cantidad = $request->input('cantidad');
        $idinvitado = $request->input('idinvitado');

        $carrito = Carrito::where('codproducto', $codproducto)
            ->where('idinvitado', $idinvitado)
            ->first();

        $carrito = Carrito::where('codproducto', $codproducto)
            ->where('codcliente', $idinvitado)
            ->first();

        if (!$carrito) {
            return response()->json([
                'estado' => 0,
                'mensaje' => 'No se encontró el producto en el carrito del invitado',
            ], 404);
        }

        $carrito->cantidad = $cantidad;
        $carrito->save();

        return response()->json([
            'estado' => 1,
            'cantidad' => $carrito->cantidad,
            'mensaje' => 'Cantidad actualizada correctamente',
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/carrito/borrarProducto",
     *     summary="Eliminar producto del carrito",
     *     tags={"Cliente - Borrar productos de carrito"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"codproducto", "idinvitado", "codcarrito"},
     *             @OA\Property(property="codproducto", type="string", example="12345"),
     *             @OA\Property(property="idinvitado", type="string", example="INV54"),
     *             @OA\Property(property="codcarrito", type="integer", example=377)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Eliminado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Eliminado exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontró el producto en el carrito",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No se encontró el producto en el carrito")
     *         )
     *     )
     * )
     */
    public function eliminarProductoCarrito(Request $request)
    {
        $request->validate([
            'codproducto' => 'required|string',
            'idinvitado' => 'required|string',
            'codcarrito' => 'required|integer',
        ]);

        $codproducto = $request->input('codproducto');
        $idinvitado = $request->input('idinvitado');
        $codcarrito = $request->input('codcarrito');

        $carrito = Carrito::where('codproducto', $codproducto)
            ->where('idinvitado', $idinvitado)
            ->where('id', $codcarrito)
            ->delete();

        $carrito = Carrito::where('codproducto', $codproducto)
            ->where('codcliente', $idinvitado)
            ->where('id', $codcarrito)
            ->delete();

        if ($carrito) {
            return response()->json(['message' => 'Eliminado exitosamente'], 200);
        } else {
            return response()->json(['message' => 'No se encontró el producto en el carrito'], 404);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/carrito/seleccionarArticulosCarrito",
     *     summary="Seleccionar artículos del carrito",
     *     tags={"Cliente - Seleccionar articulos del carrito"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"ids", "action"},
     *             @OA\Property(property="ids", type="array", @OA\Items(type="integer"), example={379, 381}),
     *             @OA\Property(property="action", type="string", enum={"checked", "unchecked"}, example="unchecked")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Actualizado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Actualizado exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontraron registros para actualizar",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No se encontraron registros para actualizar")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al actualizar",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error al actualizar"),
     *             @OA\Property(property="error", type="string", example="Mensaje de error detallado")
     *         )
     *     )
     * )
     */
    public function seleccionarArticulosDelCarrito(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'action' => 'required|in:checked,unchecked',
        ]);

        $ids = $validated['ids'];
        $action = $validated['action'];

        try {
            $updatedRows = Carrito::whereIn('id', $ids)->update(['is_checked' => ($action === 'checked') ? 1 : 0]);
            if ($updatedRows > 0) {
                return response()->json(['message' => 'Actualizado exitosamente'], 200);
            } else {
                return response()->json(['message' => 'No se encontraron registros para actualizar'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al actualizar', 'error' => $e->getMessage()], 500);
        }
    }
}
