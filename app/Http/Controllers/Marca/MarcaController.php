<?php

namespace App\Http\Controllers\Marca;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Models\Marca\Marca;

class MarcaController extends Controller
{
    use HttpResponses;
    /**
     * @OA\Get(
     *     path="/api/v1/brands",
     *     tags={"Cliente - Marcas Productos"},
     *     summary="Obtener todas las marcas",
     *     description="Devuelve una lista con todas las marcas disponibles",
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No hay datos",
     *     )
     * )
     */
    public function getMarcas()
    {
        $marcas = Marca::all();
        if (!$marcas) {
            return $this->error("Error", 'No hay datos!', 404);
        }
        return $this->success($marcas, 'Ok!');
    }
}
