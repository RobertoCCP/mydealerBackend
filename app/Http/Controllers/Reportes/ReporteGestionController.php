<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use App\Traits\HttpResponses;
use App\Http\Resources\Reportes\ReporteGestionResource;
use App\Models\Reportes\ReporteGestion\RutaGestion;
use App\Http\Requests\Reportes\ReporteGestionRequest;
use Illuminate\Http\Request;

class ReporteGestionController extends Controller {
    use HttpResponses;

    /**
     * @OA\Get(
     * path="/api/reporte/gestion",
     * tags={"Web - Reporte Gestión"},
     * summary="Buscar el reporte de gestión",
     * description="Devuelve una lista con los reportes de gestión aplicando filtros y paginación.",
     * @OA\Parameter(
     *      name="supervisor",
     *      in="query",
     *      description="Código del supervisor",
     *      required=false,
     *      @OA\Schema(
     *          type="string"
     *      )
     * ),
     * @OA\Parameter(
     *      name="vendedor",
     *      in="query",
     *      description="Código del vendedor",
     *      required=false,
     *      @OA\Schema(
     *          type="string"
     *      )
     * ),
     * @OA\Parameter(
     *      name="cliente",
     *      in="query",
     *      description="Código del cliente",
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
     *      name="tipo_novedad",
     *      in="query",
     *      description="Tipo de novedad",
     *      required=false,
     *      @OA\Schema(
     *          type="string"
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

    public function index(ReporteGestionRequest $request) {
        try {
            $query = RutaGestion::query();

            if ($request->has('supervisor') && $request->supervisor !== 'Todos') {
                $query->where('codsupervisor', $request->supervisor);
            }

            if ($request->has('vendedor') && $request->vendedor !== 'Todos') {
                $query->where('codvendedor', $request->vendedor);
            }

            if ($request->has('cliente') && $request->cliente !== 'Todos') {
                $query->where('codcliente', $request->cliente);
            }

            if ($request->has('fecha_inicio')) {
                $query->whereDate('fecha', '>=', $request->fecha_inicio);
            }

            if ($request->has('fecha_fin')) {
                $query->whereDate('fecha', '<=', $request->fecha_fin);
            }

            if ($request->has('tipo_novedad') && $request->tipo_novedad !== 'Todas') {
                $query->where('codestado', $request->tipo_novedad);
            }

            $page = $request->query('page', 1);
            $perPage = 20;

            $rutas = $query->paginate($perPage, ['*'], 'page', $page);

            return ReporteGestionResource::collection($rutas);
        } catch (\Exception $e) {
            return $this->error('Error al obtener las rutas de gestión.', $e->getMessage(), 500);
        }
    }
}
//
