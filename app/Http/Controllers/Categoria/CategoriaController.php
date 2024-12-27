<?php

namespace App\Http\Controllers\Categoria;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;

class CategoriaController extends Controller
{
    use HttpResponses;
    /**
     * @OA\Get(
     * path="/api/v1/categories/products",
     * tags={"Cliente - Categorias Productos"},
     * summary="Obtener todas las categorías de productos",
     * description="Devuelve una lista con todas las categorías distintas de productos",
     * @OA\Response(
     *     response=200,
     *     description="Datos encontrados",
     *     @OA\JsonContent(
     *         type="array",
     *         @OA\Items(
     *             type="string",
     *             example="Electrónica"
     *         )
     *     )
     * ),
     * @OA\Response(
     *     response=404,
     *     description="No hay datos",
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(
     *             property="message",
     *             type="string",
     *             example="No hay datos!"
     *         ),
     *         @OA\Property(
     *             property="error",
     *             type="string",
     *             example="No data"
     *         )
     *     )
     * ),
     * @OA\Response(
     *     response=500,
     *     description="Error del servidor"
     * ),
     * )
     */
    public function getCategorias()
    {
        $categorias = Producto::select('categoria')
            ->distinct()
            ->whereNotNull('categoria')
            ->pluck('categoria');

        if (!$categorias) {
            return $this->error('No data', 'No hay datos!', 404);
        }
        return $this->success($categorias, 'Datos encontrados!', 200);
    }
}
