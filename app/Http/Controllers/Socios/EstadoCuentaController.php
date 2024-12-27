<?php

namespace App\Http\Controllers\Socios;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\DB;

class EstadoCuentaController extends Controller
{
    use HttpResponses;
    
    /**     
     * 
     * @OA\Get(
     * path="/api/reporte/estado/cuenta",
     * tags={"Web - Estado Cuenta"},
     * summary="Reporte del estado de cuenta de clientes",
     * description="Devuelve una lista del estado de los clientes y sus documentos",
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
                    ec.srestadocuenta,
                    cl.nombre,
                    ec.fecha,
                    ec.fechavencimiento,    
                    ec.coddocumento,
                    ec.documento,
                    ec.tipodocumento,
                    ec.descripcion,
                    ec.valor,
                    ec.saldo,
                    ec.referencia,
                    ec.codcuota,
                    ec.numcuota
                FROM estadocuenta ec 
                LEFT JOIN cliente cl 
                ON ec.codcliente = cl.codcliente                
            ");
            // Retorna los resultados de la consulta
            return $this->success($result);                        
        } catch (\Exception $e) {            
            return $this->error('Error al obtener el reporte del estado de cuenta.', $e->getMessage(), 500);
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
