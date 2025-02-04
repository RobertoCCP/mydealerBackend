<?php

namespace App\Http\Controllers\Producto;

use App\Http\Controllers\Controller;
use App\Models\BodegaStock;
use App\Models\Producto;
use App\Models\Productos\TipoProducto;
use Illuminate\Http\Request;
    use App\Http\Requests\Producto\ProductoRequest;
    use App\Http\Resources\Producto\ProductoResource;
use App\Traits\HttpResponses;
use SebastianBergmann\CodeCoverage\Test\TestStatus\Success;

use App\Http\Resources\Producto\ProductoDetalleResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductoController extends Controller
{
    use HttpResponses;
    //COMENTADO- NO SE ESTÁ USANDO LA API
    // /**
    //  * @OA\Post(
    //  * path="/api/productos/detalles",
    //  * tags={"Cliente - Detalle de Productos"},
    //  * summary="Detalla los productos del carrrito",
    //  * description="recibir una lista de códigos de productos junto con la cantidad correspondiente en el carrito",
    //  * @OA\RequestBody(
    //  *         description="Lista de productos y cantidades",
    //  *         required=true,
    //  * @OA\JsonContent(
    //  *             type="object",
    //  *             required={"productos"},
    //  *             properties={
    //  *                 @OA\Property(property="productos", type="array", description="Lista de productos", @OA\Items(
    //  *                     type="object",
    //  *                     required={"codproducto", "cantidad"},
    //  *                     properties={
    //  *                         @OA\Property(property="codproducto", type="string", maxLength=20, description="Código del producto" , default="'01000300-BL'"),
    //  *                         @OA\Property(property="cantidad", type="integer", description="Cantidad del producto", default="2")
    //  *                     }
    //  *                 ))
    //  *             }
    //  *         )
    //  *     ),
    //  * @OA\Response(
    //  *         response=200,
    //  *         description="successful operation",
    //  *     ),
    //  * @OA\Response(
    //  *     response=500,
    //  *     description="Database error"
    //  * ),
    //  * )
    //  */

    public function productoDetalles(Request $request)
    {
        $productos = $request->input('productos');
        $resultados = [];

        foreach ($productos as $producto) {
            $codproducto = $producto['codproducto'];
            $cantidad = $producto['cantidad'];

            $productoInfo = Producto::with('bodegastock')->where('codproducto', $codproducto)->first();

            if (!$productoInfo) {
                continue;
            }

            $stockTotal = $productoInfo->bodegastock->sum('stock');

            $cantidadNoDisponible = max(0, $cantidad - $stockTotal);

            $resultados[] = [
                'codproducto' => $codproducto,
                'costo' => $productoInfo->costo,
                'descuento' => $this->calcularDescuento($productoInfo), // Asumiendo que tienes una lógica para calcular el descuento
                'porcimpuesto' => $productoInfo->porcimpuesto,
                'cantidad_no_disponible' => $cantidadNoDisponible
            ];
        }

        if (!empty($resultados)) {
            return $this->success($resultados, 'Ok!!');
        } else {
            return $this->error('Error', 'No hay datos!', 404);
        }
    }

    /**
    * @OA\Get(
    *     path="/api/productos/categoria",
    *     tags={"Cliente - Detalle de Productos"},
    *     summary="Detalla los productos por categoria",
    *     description="Devuelve una lista filtrada por categoria",
    *     @OA\Parameter(
    *         name="categoria",
    *         in="query",
    *         required=true,
    *         description="Categoría de productos",
    *         @OA\Schema(type="string"),
    *         example="Electronica"
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Operación exitosa"
    *     ),
    *     @OA\Response(
    *         response=500,
    *         description="Error en la base de datos"
    *     )
    * )
    */
    public function buscarTiposProductos()
{
    // Obtener todos los tipos de productos
    $tiposProductos = TipoProducto::all();

    if ($tiposProductos->isEmpty()) {
        return $this->error('Error', 'No hay tipos de productos disponibles', 404);
    }

    return $this->success($tiposProductos, 'Tipos de productos encontrados');
}
    

    public function productosTipoCategoria($tipoCategoria)
    {
        $productos = Producto::select("*")->where('codtipoproducto', $tipoCategoria)->get();
        return $this->success($productos, 'Productos encontrados', 200);
    }


    /**
    * @OA\Get(
    *     path="/api/productos/nombre",
    *     tags={"Cliente - Detalle de Productos"},
    *     summary="Detalla los productos por nombre",
    *     description="Devuelve una lista filtrada por nombre",
    *     @OA\Parameter(
    *         name="nombre",
    *         in="query",
    *         required=true,
    *         description="Categoría de productos",
    *         @OA\Schema(type="string"),
    *         example=""
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Operación exitosa"
    *     ),
    *     @OA\Response(
    *         response=500,
    *         description="Error en la base de datos"
    *     )
    * )
    */
    public function buscarPorNombre($nombre)
    {
        //$nombre = $request->input('nombre');

        $productos = Producto::select('*')->where('nombre', 'LIKE', '%' . $nombre . '%')->limit(2)->get();


        return $this->success($productos, 'Ok!!');
    }

        /**
    * @OA\Get(
    *     path="/api/productos/categoria-nombre",
    *     tags={"Cliente - Detalle de Productos"},
    *     summary="Detalla los productos por nombre y categoria",
    *     description="Devuelve una lista filtrada por nombre y categoria",
    *     @OA\Parameter(
    *         name="categoria",
    *         in="query",
    *         required=true,
    *         description="Categoría de productos",
    *         @OA\Schema(type="string"),
    *         example="Electronica"
    *     ),
    *     @OA\Parameter(
    *         name="nombre",
    *         in="query",
    *         required=true,
    *         description="Categoría de productos",
    *         @OA\Schema(type="string"),
    *         example="Tapa"
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Operación exitosa"
    *     ),
    *     @OA\Response(
    *         response=500,
    *         description="Error en la base de datos"
    *     )
    * )
    */
    public function buscarPorCategoriaYNombre(Request $request)
    {
        $categoria = $request->input('categoria');
        $nombre = $request->input('nombre');

        $productos = Producto::where('categoria', $categoria)
            ->where('nombre', 'LIKE', '%' . $nombre . '%')
            ->get();

        $resultados = $productos->map(function ($producto) {
            $stock = $producto->stocks->sum('stock');
            return [
                'codproducto' => $producto->codproducto,
                'nombre' => $producto->nombre,
                'precio' => $producto->costo,
                'precio_con_descuento' => $this->calcularDescuento($producto),
                'imagen' => $producto->imagen,
                'porc_descuento' => $producto->porcdescuento,
                'cantidad_en_stock' => $stock
            ];
        });

        if ($resultados->isNotEmpty()) {
            return $this->success($resultados, 'Ok!!');
        } else {
            return $this->error('Error', 'No hay datos!', 404);
        }
    }


    private function calcularDescuento(Producto $producto)
    {
        $precioOriginal = $producto->costo ?? 0;
        $porcDescuento = $producto->porcdescuento ?? 0;

        $valorDescuento = ($precioOriginal * $porcDescuento) / 100;
        $precioFinal = $precioOriginal - $valorDescuento;
        return $precioFinal;
    }
        #TODO:: Detalle de un producto po código del producto
/**
     * @OA\Get(
     * path="/api/productos/detalles/{codproducto}",
     * tags={"Cliente - Detalle de Productos"},
     * summary="Devuelve los detalles de acuerdo al código producto",
     * description="Devuelve los detalles de acuerdo al código producto",
     *
     * @OA\Parameter(
     *      description="Necesita el codproducto",
     *      in="path",
     *      name="codproducto",
     *      required=true,
     *      @OA\Schema(type="string", default="A-1001151")
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
        public function productoDetalle($codproducto)
        {

            try {
                $producto = Producto::with('stocks', 'tipo', 'marca')->where('codproducto', $codproducto)->first();
                if ($producto) {
                    return $this->success(
                        new ProductoDetalleResource($producto),
                        'Producto encontrado',
                        200
                    );
                } else {
                    return $this->error(null, 'Producto no encontrado', 404);
                }
            } catch (\Exception $e) {
                return $this->error($e->getMessage(), 'Error en el servidor', 500);
            }
        }


        #TODO:: Obtener productos de un mismo tipo
      /**
    * @OA\Get(
    * path="/api/productos/productosrelacionado",
    * tags={"Cliente - Detalle de Productos"},
    * summary="Listar productos relacionados al tipo de producto con paginación",
    * description="Devuelve una lista de productos relacionados al tipo de productos con paginación",
    * @OA\Parameter(
    *     name="codproducto",
    *     in="query",
    *     description="Código del producto",
    *     required=false,
    *     @OA\Schema(type="string", default="A-924298")
    * ),
    * @OA\Parameter(
    *     name="limit",
    *     in="query",
    *     description="Número de productos por página",
    *     required=false,
    *     @OA\Schema(type="integer", default=10)
    * ),
    * @OA\Parameter(
    *     name="offset",
    *     in="query",
    *     description="Número de página (empieza en 0)",
    *     required=false,
    *     @OA\Schema(type="integer", default=0)
    * ),
    * @OA\Response(
    *     response=200,
    *     description="Operación exitosa",
    *
    * ),
    * @OA\Response(
    *     response=400,
    *     description="Parámetros de paginación inválidos"
    * ),
    * @OA\Response(
    *     response=500,
    *     description="Error al obtener los productos"
    * ),
    * )
    */
        public function productosPorTipo(Request $request)
        {
            try {
                // Obtener los parámetros de paginación de la solicitud
            $limit = $request->query('limit', 10); // por defecto 10
            $offset = $request->query('offset', 0); // por defecto 0
            $codproducto= $request->query('codproducto'); //Código del producto

            // Validar que los parámetros son números enteros positivos
            if (!is_numeric($limit) || !is_numeric($offset) || $limit <= 0 || $offset < 0) {
                return $this->error('Parámetros de paginación inválidos.', null, 400);
            }

            // Calcular el valor del OFFSET en base al número de página
            $offsetValue = $offset * $limit;

                $producto = Producto::where('codproducto', $codproducto)->firstOrFail();
                $productos = Producto::with('stocks', 'tipo', 'marca')->where('codtipoproducto', $producto->codtipoproducto)
                    ->where('codproducto', '!=', $codproducto);
                    // Obtener el total de productos
                $totalProductos = $productos->count();

                // Obtener los productos paginados
                $productos = $productos->skip($offsetValue)->take($limit)->get();

                if ($productos->isEmpty()) {
                    return $this->error(null, 'No hay otros productos del mismo tipo', 404);
                }

                return $this->success(
                    [
                        'productos' => ProductoDetalleResource::collection($productos),
                        'total' => $totalProductos,
                        'limit' => $limit,
                        'offset' => $offset
                    ],
                    'Productos relacionados al mismo tipo de productos encontrados',
        200
                );
            } catch (ModelNotFoundException $e) {
                return $this->error(null, 'Producto no encontrado', 404);
            } catch (\Exception $e) {
                return $this->error(null, 'Error al buscar productos: ' . $e->getMessage(), 500);
            }
        }

}
