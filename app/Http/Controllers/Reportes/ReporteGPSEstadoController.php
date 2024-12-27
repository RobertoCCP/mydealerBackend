<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use App\Traits\HttpResponses;
use App\Http\Resources\Reportes\ReporteGPSEstadoResource;
use App\Models\Reportes\ReporteGPSEstado\ReporteGPS;
use App\Http\Requests\Reportes\ReporteGPSEstadoRequest;
use Illuminate\Http\Request;

class ReporteGPSEstadoController extends Controller {
    use HttpResponses;

    /**
     * @OA\Get(
     * path="/api/reporte/gps/estado",
     * tags={"Web - Reporte GPS Estado"},
     * summary="Buscar el reporte de GPS",
     * description="Devuelve una lista con los reportes de GPS aplicando filtros y paginación.",
     * @OA\Parameter(
     *      name="codsupervisor",
     *      in="query",
     *      description="Código del supervisor",
     *      required=false,
     *      @OA\Schema(
     *          type="string"
     *      )
     * ),
     * @OA\Parameter(
     *      name="codvendedor",
     *      in="query",
     *      description="Código del vendedor",
     *      required=false,
     *      @OA\Schema(
     *          type="string"
     *      )
     * ),
     * @OA\Parameter(
     *      name="fecha_inicio",
     *      in="query",
     *      description="Fecha de inicio",
     *      required=false,
     *      @OA\Schema(
     *          type="string",
     *          format="date"
     *      )
     * ),
     * @OA\Parameter(
     *      name="fecha_fin",
     *      in="query",
     *      description="Fecha de fin",
     *      required=false,
     *      @OA\Schema(
     *          type="string",
     *          format="date"
     *      )
     * ),
     * @OA\Parameter(
     *      name="page",
     *      in="query",
     *      description="Número de página para la paginación",
     *      required=false,
     *      @OA\Schema(
     *          type="integer",
     *          default=1
     *      )
     * ),
     * @OA\Response(
     *      response=200,
     *      description="successful operation"
     * ),
     * @OA\Response(
     *      response=500,
     *      description="Database error"
     * )
     * )
     */

    public function index(ReporteGPSEstadoRequest $request) {
        try {
            $query = ReporteGps::query();

            if ($request->has('codsupervisor') && $request->codsupervisor !== 'Todos') {
                $query->where('codsupervisor', $request->codsupervisor);
            }

            if ($request->has('codvendedor') && $request->codvendedor !== 'Todos') {
                $query->where('codvendedor', $request->codvendedor);
            }

            if ($request->has('fecha_inicio')) {
                $query->whereDate('fechamovil', '>=', $request->fecha_inicio);
            }

            if ($request->has('fecha_fin')) {
                $query->whereDate('fechamovil', '<=', $request->fecha_fin);
            }

            $page = $request->query('page', 1);
            $perPage = 20;

            $reportes = $query->paginate($perPage, ['*'], 'page', $page);

            return ReporteGPSEstadoResource::collection($reportes);
        } catch (\Exception $e) {
            return $this->error('Error al obtener los reportes de GPS.', $e->getMessage(), 500);
        }
    }
}
