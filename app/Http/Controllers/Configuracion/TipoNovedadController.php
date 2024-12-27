<?php

namespace App\Http\Controllers\Configuracion;

use App\Http\Controllers\Controller;
use App\Traits\HttpResponses;
use App\Http\Resources\Configuracion\TipoNovedadResource;
use App\Models\TipoNovedad;
use App\Http\Requests\Configuracion\TipoNovedadRequest;

class TipoNovedadController extends Controller
{
    use HttpResponses;

    /**
     * @OA\Get(
     * path="/api/tipo/novedad",
     * tags={"Web - Tipo de Novedad"},
     * summary="Buscar todas las novedades",
     * description="Devuelve una lista con todos los tipos de novedad",
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
    public function index(){
        try {                        
            return $this->success(TipoNovedadResource::collection(TipoNovedad::all()));                        
        } catch (\Exception $e) {            
            return $this->error('Error al obtener los tipos de novedad.', $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Post(
     * path="/api/tipo/novedad",
     * tags={"Web - Tipo de Novedad"},
     * summary="Crear novedad",
     * description="Devuelve el tipo de novedad creado",
     * @OA\RequestBody(
     * description="Datos de la mascota que se actualizará",
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="Codigo", type="string", maxLength=5, description="Código de la mascota"),
 *             @OA\Property(property="Tipo_novedad", type="string", maxLength=120, description="Tipo de novedad"),
 *             @OA\Property(property="Categoria", type="string", maxLength=120, description="Categoría"),
 *             @OA\Property(property="Orden", type="integer", description="Orden", format="int32"),
 *             @OA\Property(property="Control", type="string", pattern="^[RC]$", description="Control (R o C)"),
 *             @OA\Property(property="Estado", type="string", pattern="^[IA]$", nullable=true, description="Estado (I o A)")
 *         )
     * ),
     * @OA\Response(
     *         response=200,
     *         description="successful operation",     
     *     ),
     * @OA\Response(
     *     response=400,
     *     description="Bad request"
     * ),
     * @OA\Response(
     *     response=409,
     *     description="Dato duplicado"
     * ),
     * @OA\Response(
     *     response=500,
     *     description="Database error"
     * ),
     * )  
     */
    public function store(TipoNovedadRequest $request)
    {
        try {            
            $validatedData = $request -> validated();
            if (TipoNovedad::find($validatedData['Codigo'])) {                
                return $this->error("No data", "Ya existe un tipo de novedad con ese Id", 409);    
            } 
            $tipoNovedad = $this->requestToModel($validatedData);
            $tipoNovedad->save();
            return $this->success(new TipoNovedadResource($tipoNovedad));                        
        } catch (\Exception $e) {            
            return $this->error('Error al obtener los tipos de novedad.', $e->getMessage(), 500);
        }   
    }

    /**
     * @OA\Get(
     * path="/api/tipo/novedad/{codigo_novedad}",
     * tags={"Web - Tipo de Novedad"},
     * summary="Buscar una novedades específica",
     * description="Devuelve un tipo de novedad por el codigo_novedad",
     * 
     * @OA\Parameter(
     *      description="Necesita el codigo_novedad",
     *      in="path",
     *      name="codigo_novedad",
     *      required=true,
     * ),
     * 
     * @OA\Response(
     *         response=200,
     *         description="successful operation",     
     * ),
     * @OA\Response(
     *     response=404,
     *     description="Dato no encontrado"
     * ),
     * @OA\Response(
     *     response=500,
     *     description="Database error"
     * ),
     * )
     *    
     */
    public function show($codigo_novedad)
    {            
        try {                                            
            $novedad = TipoNovedad::find($codigo_novedad);
            if (!$novedad) {                
                return $this->error("No data", "Tipo de novedad no encontrado", 404);    
            }            
            return $this->success(new TipoNovedadResource($novedad));                        
        } catch (\Exception $e) {            
            return $this->error('Error al obtener los tipos de novedad.', $e->getMessage(), 500);
        }        
    }

    /**
    * @OA\Put(
    *     path="/api/tipo/novedad/{codigo_novedad}",
    *     tags={"Web - Tipo de Novedad"},
    *     summary="Actualizar tipo de novedad",
    *     description="Actualiza un tipo de novedad existente",
    *     @OA\Parameter(
    *         name="codigo_novedad",
    *         in="path",
    *         required=true,
    *         description="Código de la novedad a actualizar",
    *     ),
    *     @OA\RequestBody(
    *         required=true,
    *         description="Datos del tipo de novedad a actualizar",
    *         @OA\JsonContent(
    *             type="object",
    *             @OA\Property(property="Codigo", type="string", maxLength=5, description="Nuevo código del tipo de novedad"),
    *             @OA\Property(property="Tipo_novedad", type="string", maxLength=120, description="Nuevo tipo de novedad"),
    *             @OA\Property(property="Categoria", type="string", maxLength=120, description="Nueva categoría"),
    *             @OA\Property(property="Orden", type="integer", description="Nuevo orden", format="int32"),
    *             @OA\Property(property="Control", type="string", pattern="^[RC]$", description="Nuevo control (R o C)"),
    *             @OA\Property(property="Estado", type="string", pattern="^[IA]$", nullable=true, description="Nuevo estado (I o A)")
    *         )
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Operación exitosa. Devuelve el tipo de novedad actualizado.",
    *     ),
    *     @OA\Response(
    *         response=400,
    *         description="Solicitud incorrecta. El formato de la solicitud es inválido."
    *     ),
    *     @OA\Response(
    *         response=404,
    *         description="No encontrado. El tipo de novedad especificado no existe."
    *     ),
    *     @OA\Response(
    *         response=409,
    *         description="Conflicto. Ya existe un tipo de novedad con el nuevo código proporcionado."
    *     ),
    *     @OA\Response(
    *         response=500,
    *         description="Error del servidor. Error interno al procesar la solicitud."
    *     )
    * )
    */
    public function update(TipoNovedadRequest $request, $codigo_novedad)
    {
        try {            
            $tipoNovedad = TipoNovedad::find($codigo_novedad);
            if (!$tipoNovedad) {                
                return $this->error("No data", "Tipo de novedad no encontrado", 404);    
            }
            $validatedData = $request -> validated();
            if ($validatedData['Codigo'] != $tipoNovedad->codtiponovedad){
                if (TipoNovedad::find($validatedData['Codigo'])) {                
                    return $this->error("No data", "Ya existe un tipo de novedad con ese Id", 409);    
                }
            }
            $tipoNovedad = $this->requestToModel($validatedData, $tipoNovedad);               
            $tipoNovedad -> save();
            return $this->success(new TipoNovedadResource($tipoNovedad));                        
        } catch (\Exception $e) {            
            return $this->error('Error al obtener los tipos de novedad.', $e->getMessage(), 500);
        }
    }


    /**
     * @OA\Delete(
     * path="/api/tipo/novedad/{codigo_novedad}",
     * tags={"Web - Tipo de Novedad"},
     * summary="Eliminar una novedades específica",
     * description="Devuelve un estado 204 si se eliminó",
     * 
     * @OA\Parameter(
     *      description="Necesita el codigo_novedad",
     *      in="path",
     *      name="codigo_novedad",
     *      required=true,
     * ),
     * 
     * @OA\Response(
     *         response=204,     
     *     description="Eliminado correctamente"
     * ),
     * @OA\Response(
     *     response=404,
     *     description="Dato no encontrado"
     * ),
     * @OA\Response(
     *     response=500,
     *     description="Database error"
     * ),
     * )    
     */
    public function destroy($codigo_novedad)
    {
        try {            
            $novedad = TipoNovedad::find($codigo_novedad);
            if (!$novedad) {                
                return $this->error("No data", "Tipo de novedad no encontrado", 404);    
            }
            $novedad->delete();
            return $this->success(null,"Deleted", 204);                        
        } catch (\Exception $e) {            
            return $this->error('Error al obtener los tipos de novedad.', $e->getMessage(), 500);
        }
    }

    private function requestToModel($request, TipoNovedad $tipoNovedad = null): TipoNovedad
    {        
        if ($tipoNovedad == null)$tipoNovedad = new TipoNovedad;
        $tipoNovedad->codtiponovedad = $request['Codigo'];
        $tipoNovedad->tiponovedad = $request['Tipo_novedad'];
        $tipoNovedad->categoria = $request['Categoria'];
        $tipoNovedad->orden = $request['Orden'];
        $tipoNovedad->control = $request['Control'];
        $tipoNovedad->estado = 'A';
        if (isset($request['Estado'])) $tipoNovedad->estado = $request['Estado'];
        return $tipoNovedad;
    }
}