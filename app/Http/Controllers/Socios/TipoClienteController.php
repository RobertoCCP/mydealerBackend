<?php

namespace App\Http\Controllers\Socios;
use App\Http\Controllers\Controller;
use App\Models\TipoCliente;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;

class TipoClienteController extends Controller
{
    use HttpResponses;
    
    /**     
     * 
     * @OA\Get(
     * path="/api/tipo/cliente",
     * tags={"Web - Tipo Cliente"},
     * summary="Buscar todas los tipos de clientes",
     * description="Devuelve una lista con el tipo de cliente y su descripcion",
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
            return $this->success(TipoCliente::all());                        
        } catch (\Exception $e) {            
            return $this->error('Error al obtener los tipos de novedad.', $e->getMessage(), 500);
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
