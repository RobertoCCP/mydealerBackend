<?php

namespace App\Http\Controllers\Producto;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\DB;

class AllProductosController extends Controller
{

    use HttpResponses;

    /**
    * @OA\Get(
    * path="/api/all/productos",
    * tags={"Cliente - Todos los Productos"},
    * summary="Listar productos con paginación",
    * description="Devuelve una lista de productos ordenados por nombre y codproducto con paginación",
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
    *     @OA\JsonContent(
    *         type="array",
    *         @OA\Items(
    *             type="object",
    *             @OA\Property(property="codproducto", type="string"),
    *             @OA\Property(property="nombre", type="string"),
    *             @OA\Property(property="umv", type="number", format="double"),
    *             @OA\Property(property="estado", type="string"),
    *             @OA\Property(property="codtipoproducto", type="string"),
    *             @OA\Property(property="precio", type="number", format="double"),
    *             @OA\Property(property="unidadmedida", type="string"),
    *             @OA\Property(property="codmarca", type="string"),
    *             @OA\Property(property="porcimpuesto", type="number", format="double")
    *         )
    *     )
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
    public function index(Request $request)
    {        
        try {
            // Obtener los parámetros de paginación de la solicitud
            $limit = $request->query('limit', 10); // por defecto 10
            $offset = $request->query('offset', 0); // por defecto 0
            
            // Validar que los parámetros son números enteros positivos
            if (!is_numeric($limit) || !is_numeric($offset) || $limit <= 0 || $offset < 0) {
                return $this->error('Parámetros de paginación inválidos.', null, 400);
            }
    
            // Calcular el valor del OFFSET en base al número de página
            $offsetValue = $offset * $limit;
    
            // Definir la consulta SQL cruda con ordenamiento por nombre e id y paginación
            $productos = Producto::with(['tipo', 'marca','stocks'])
            ->select(
                'codproducto',
                'nombre',
                'umv',
                'estado',
                'codtipoproducto',
                'costo as precio',
                'unidadmedida',
                'codmarca',
                'porcimpuesto',
                'porcdescuento',
                'imagen',
                'categoria'
            )
            ->orderBy('nombre')
            ->orderBy('codproducto')
            ->limit($limit)
            ->offset($offsetValue)
            ->get();

        // Mapear los resultados para incluir la información de las relaciones
        $result = $productos->map(function ($producto) {
            return [
                'codproducto' => $producto->codproducto,
                'nombre' => $producto->nombre,
                'umv' => $producto->umv,
                'estado' => $producto->estado,
                'codtipoproducto' => $producto->codtipoproducto,
                'precio' => $producto->precio,
                'unidadmedida' => $producto->unidadmedida,
                'codmarca' => $producto->codmarca,
                'porcimpuesto' => $producto->porcimpuesto,
                'porcdescuento' => $producto->porcdescuento,
                'imagen' => $producto->imagen,
                'categoria' => $producto->categoria,
                'tipoproducto' => $producto->tipo->descripcion ?? 'No disponible', // Información del tipo de producto
                'marca' => $producto->marca->nombre ?? 'No disponible', // Información de la marca
                'total_actual_stock' => $producto->stocks->sum('stock'),
            ];
        });
            
            // Retornar los resultados de la consulta en formato JSON
            return response()->json($result);                       
        } catch (\Exception $e) {            
            return $this->error('Error al obtener los productos.', $e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
