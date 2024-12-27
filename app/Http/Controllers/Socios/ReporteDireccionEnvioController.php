<?php

namespace App\Http\Controllers\Socios;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Traits\HttpResponses;
class ReporteDireccionEnvioController extends Controller
{

    use HttpResponses;

    /**     
     * 
     * @OA\Get(
     * path="/api/reporte/direccion/envio",
     * tags={"Web - Dirección Envio"},
     * summary="Reporte de la dirección de envío",
     * description="Devuelve una lista de clientes y sus direcciones de envío",
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
            // Define la consulta SQL cruda
            $result = DB::select("
                SELECT 
                de.coddireccionenvio,
                de.codcliente,
                de.nombre as nombredestinatario,
                cl.nombre as nombrecliente,
                de.pais,
                de.provincia,
                de.ciudad,
                de.direccion,
                de.orden,
                de.latitud,
                de.longitud
                FROM direccionenvio de 
                LEFT JOIN cliente cl 
                on de.codcliente=cl.codcliente
            ");
            // Retorna los resultados de la consulta
            return $this->success($result);                        
        } catch (\Exception $e) {            
            return $this->error('Error al obtener el reporte de direcciones.', $e->getMessage(), 500);
        }
    }
}
