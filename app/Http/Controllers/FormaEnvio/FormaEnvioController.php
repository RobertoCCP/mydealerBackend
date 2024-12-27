<?php

namespace App\Http\Controllers\FormaEnvio;

use App\Http\Controllers\Controller;
use App\Models\FormaEnvio;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class FormaEnvioController extends Controller
{
    use HttpResponses;
 /**
     * @OA\Get(
     * path="/api/formasenvio",
     * tags={"Cliente - Formas de Envío"},
     * summary="Lista de formas de Envío",
     * description="Devuelve una lista con todos las formas de envío",
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
        public function index()
    {

        try {

            $formas = FormaEnvio::all();

            if ($formas->isEmpty()) {
                return $this->error(null, 'No hay formas de envío', 404);
            }
            return $this->success($formas, 'Formas de envío encontradas', 200);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 'Error al obtener las formas de envío', 500);
        }
    }
}
