<?php

namespace App\Http\Controllers\Productos;

use App\Http\Controllers\Controller;
use App\Traits\HttpResponses;
use App\Http\Resources\Productos\TipoProductoResource;
use App\Models\Productos\TipoProducto;
use App\Http\Requests\Productos\TipoProductoRequest;

class TipoProductoController extends Controller
{
    use HttpResponses;

    /**
     * @OA\Get(
     * path="/api/tipo/producto",
     * tags={"Web - Tipo Producto"},
     * summary="Buscar los tipos de productos",
     * description="Devuelve una lista con los tipos de productos",
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
            return $this->success(TipoProductoResource::collection(TipoProducto::all()));                        
        } catch (\Exception $e) {            
            return $this->error('Error al obtener los tipos de producto.', $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Post(
     * path="/api/tipo/producto",
     * tags={"Web - Tipo Producto"},
     * summary="Crear tipo de producto",
     * description="Devuelve el tipo de producto creado",
     * @OA\RequestBody(
     * description="Datos del parametro a crear",
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="CodigoTipoProducto", type="string", maxLength=10, description="Código del tipo de producto"),
     *             @OA\Property(property="Descripcion", type="string", maxLength=60, description="Descripción del parámetro"),
     *             @OA\Property(property="CodigoGrupoMaterial", type="string", maxLength=10, description="Código del grupo de material"),
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
    public function store(TipoProductoRequest $request)
    {
        try {            
            $validatedData = $request->validated();
            if (TipoProducto::find($validatedData['CodigoTipoProducto'])) {                
                return $this->error("No data", "Ya existe un tipo de producto con ese Id", 409);    
            } 
            $tipoProducto = $this->requestToModel($validatedData);
            $tipoProducto->save();
            return $this->success(new TipoProductoResource($tipoProducto));                        
        } catch (\Exception $e) {            
            return $this->error('Error al crear el tipo de producto.', $e->getMessage(), 500);
        }   
    }

    /**
     * @OA\Get(
     * path="/api/tipo/producto/{codtipoproducto}",
     * tags={"Web - Tipo Producto"},
     * summary="Buscar tipo de producto",
     * description="Devuelve el tipo de producto por codtipoproducto",
     * 
     * @OA\Parameter(
     *      description="Necesita el codtipoproducto",
     *      in="path",
     *      name="codtipoproducto",
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
     */
    public function show($codtipoproducto)
    {            
        try {                                            
            $producto = TipoProducto::find($codtipoproducto);
            if (!$producto) {                
                return $this->error("No data", "Tipo de producto no encontrado", 404);    
            }            
            return $this->success(new TipoProductoResource($producto));                        
        } catch (\Exception $e) {            
            return $this->error('Error al obtener el tipo de producto.', $e->getMessage(), 500);
        }        
    }

    /**
    * @OA\Put(
    * path="/api/tipo/producto/{codtipoproducto}",
    * tags={"Web - Tipo Producto"},
    * summary="Actualizar tipo de producto",
    * description="Devuelve el tipo de producto actualizado",
    *     @OA\Parameter(
    *      description="Necesita el codtipoproducto",
    *      in="path",
    *      name="codtipoproducto",
    *      required=true,
    *     ),
    *     @OA\RequestBody(
    *         required=true,
    *         description="Datos del parámetro a actualizar",
    *         @OA\JsonContent(
    *             type="object",
    *             @OA\Property(property="CodigoTipoProducto", type="string", maxLength=10, description="Código del tipo de producto"),
    *             @OA\Property(property="Descripcion", type="string", maxLength=60, description="Descripción del parámetro"),
    *             @OA\Property(property="CodigoGrupoMaterial", type="string", maxLength=10, description="Código del grupo de material"),
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
    public function update(TipoProductoRequest $request, $codtipoproducto)
    {
        try {            
            $tipoProducto = TipoProducto::find($codtipoproducto);
            if (!$tipoProducto) {                
                return $this->error("No data", "Tipo de producto no encontrado", 404);    
            }
            $validatedData = $request->validated();
            if ($validatedData['CodigoTipoProducto'] != $tipoProducto->codtipoproducto){
                if (TipoProducto::find($validatedData['CodigoTipoProducto'])) {                
                    return $this->error("No data", "Ya existe un tipo de producto con ese Id", 409);    
                }
            }
            $tipoProducto = $this->requestToModel($validatedData, $tipoProducto);               
            $tipoProducto->save();
            return $this->success(new TipoProductoResource($tipoProducto));                        
        } catch (\Exception $e) {            
            return $this->error('Error al actualizar el tipo de producto.', $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Delete(
     * path="/api/tipo/producto/{codtipoproducto}",
     * tags={"Web - Tipo Producto"},
     * summary="Borrar tipo de producto",
     * description="Devuelve un estado 204 si se eliminó",
     * 
     * @OA\Parameter(
     *      description="Necesita el codtipoproducto",
     *      in="path",
     *      name="codtipoproducto",
     *      required=true,
     * ),
     * 
     * @OA\Response(
     *         response=204,
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
     */
    public function destroy($codtipoproducto)
    {
        try {            
            $producto = TipoProducto::find($codtipoproducto);
            if (!$producto) {                
                return $this->error("No data", "Tipo de producto no encontrado", 404);    
            }
            $producto->delete();
            return $this->success(null, "Deleted", 204);                        
        } catch (\Exception $e) {            
            return $this->error('Error al eliminar el tipo de producto.', $e->getMessage(), 500);
        }
    }

    private function requestToModel($request, TipoProducto $tipo = null): TipoProducto
    {        
        if ($tipo == null)$tipo = new TipoProducto;
        $tipo->codtipoproducto = $request['CodigoTipoProducto'];
        $tipo->descripcion = $request['Descripcion'];
        $tipo->codgrupomaterial = $request['CodigoGrupoMaterial'];                
        return $tipo;
    }
}
