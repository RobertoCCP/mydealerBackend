<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use App\Traits\HttpResponses;
use App\Http\Resources\Reportes\ReporteCorreosResource;
use App\Models\Reportes\ReporteCorreos\CorreoLog;
use App\Http\Requests\Reportes\ReporteCorreosRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ReporteCorreosController extends Controller {
    use HttpResponses;

    /**
     * @OA\Get(
     * path="/api/reporte/correos",
     * tags={"Web - Reporte Correos"},
     * summary="Buscar el reporte de correos",
     * description="Devuelve una lista con los correos aplicando filtros y paginación.",
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
     *      name="estado",
     *      in="query",
     *      description="Estado del correo (E: Enviado, N: No Enviado)",
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

    public function index(ReporteCorreosRequest $request) {
        try {
            $query = CorreoLog::query();

            if ($request->has('fecha_inicio')) {
                $query->whereDate('fechacreacion', '>=', $request->fecha_inicio);
            }

            if ($request->has('fecha_fin')) {
                $query->whereDate('fechacreacion', '<=', $request->fecha_fin);
            }

            if ($request->has('estado') && $request->estado !== 'Todos') {
                $query->where('estado', $request->estado);
            }

            $page = $request->query('page', 1);
            $perPage = 20;

            $correos = $query->paginate($perPage, ['*'], 'page', $page);

            return ReporteCorreosResource::collection($correos);
        } catch (\Exception $e) {
            return $this->error('Error al obtener los correos.', $e->getMessage(), 500);
        }
    }

    public function reenviarCorreo($idcorreo) {
        try {
            $correo = CorreoLog::findOrFail($idcorreo);

            if ($correo->estado === 'N') {
                // Enviar el correo directamente usando el sistema de correos de Laravel
                Mail::raw($correo->cuerpo, function ($message) use ($correo) {
                    $message->to($correo->mailprincipal)
                            ->subject($correo->asunto);
                });

                // Actualizar el estado del correo después de enviarlo
                $correo->estado = 'E';
                $correo->fechaenvio = now();
                $correo->save();

                return response()->json(['message' => 'Correo reenviado con éxito.'], 200);
            }

            return response()->json(['message' => 'El correo ya ha sido enviado.'], 400);
        } catch (\Exception $e) {
            return $this->error('Error al reenviar el correo.', $e->getMessage(), 500);
        }
    }
}
