<?php

namespace App\Http\Controllers\Roles;

use App\Http\Controllers\Controller;
use App\Traits\HttpResponses;
use App\Http\Resources\Roles\PerfilUsuarioResource;
use App\Models\Roles\PerfilUsuario;
use App\Http\Requests\Roles\PerfilUsuario\PerfilUsuarioRequest;

class PerfilUsuarioController extends Controller
{
    use HttpResponses;

    /**
     * @OA\Get(
     * path="/api/user/perfil",
     * tags={"Web - Perfil Usuario"},
     * summary="Buscar todos los perfiles del usuario",
     * description="Devuelve una lista con todos los perfiles de la usuarios",
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
            return $this->success(PerfilUsuarioResource::collection(PerfilUsuario::all()));
        } catch (\Exception $e) {
            return $this->error('Error al obtener los perfiles de usuario.', $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Post(
     * path="/api/user/perfil",
     * tags={"Web - Perfil Usuario"},
     * summary="Crear perfil de usuario",
     * description="Devuelve el perfil de usuario creado",
     * @OA\RequestBody(
     * description="Datos del perfil a crear",
     *    required=true,
     *    @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="perfil", type="string", maxLength=255, description="Descripción del perfil"),
     *        @OA\Property(property="nivel", type="integer", description="Nivel del perfil"),     
     *        @OA\Property(property="tipoperfil", type="string", maxLength=255, description="Tipo de perfil"),
     *    )
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
    public function store(PerfilUsuarioRequest $request)
    {
        try {
            $validatedData = $request->validated();            
            $perfilUsuario = $this->requestToModel($validatedData);
            $perfilUsuario->save();
            return $this->success(new PerfilUsuarioResource($perfilUsuario));
        } catch (\Exception $e) {
            return $this->error('Error al crear el perfil de usuario.', $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     * path="/api/user/perfil/{codigo_perfil}",
     * tags={"Web - Perfil Usuario"},
     * summary="Buscar perfil específico",
     * description="Devuelve el perfil por el codigo_perfil",
     * 
     * @OA\Parameter(
     *      description="Necesita el codigo_perfil",
     *      in="path",
     *      name="codigo_perfil",
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
    public function show($codigo_perfil)
    {
        try {
            $perfilUsuario = PerfilUsuario::find($codigo_perfil);
            if (!$perfilUsuario) {
                return $this->error("No data", "Perfil de usuario no encontrado", 404);
            }
            return $this->success(new PerfilUsuarioResource($perfilUsuario));
        } catch (\Exception $e) {
            return $this->error('Error al obtener el perfil de usuario.', $e->getMessage(), 500);
        }
    }

    /**
    * @OA\Put(
    * path="/api/user/perfil/{codigo_perfil}",
    * tags={"Web - Perfil Usuario"},
    * summary="Actualizar perfil",
    * description="Actualiza un perfil por codigo_perfil",
    *     @OA\Parameter(
    *      description="Necesita el codigo_perfil",
    *      in="path",
    *      name="codigo_perfil",
    *      required=true,
    *     ),
    *     @OA\RequestBody(
    *         required=true,
    *         description="Datos del parámetro a actualizar",
    *         @OA\JsonContent(
    *             type="object",
    *             @OA\Property(property="perfil", type="string", maxLength=255, description="Descripción del perfil"),
    *             @OA\Property(property="nivel", type="integer", description="Nivel del perfil"),     
    *             @OA\Property(property="tipoperfil", type="string", maxLength=255, description="Tipo de perfil"),
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
    public function update(PerfilUsuarioRequest $request, $codigo_perfil)
    {
        try {
            $perfilUsuario = PerfilUsuario::find($codigo_perfil);
            if (!$perfilUsuario) {
                return $this->error("No data", "Perfil de usuario no encontrado", 404);
            }
            $validatedData = $request->validated();            
            $perfilUsuario = $this->requestToModel($validatedData, $perfilUsuario);
            $perfilUsuario->save();
            return $this->success(new PerfilUsuarioResource($perfilUsuario));
        } catch (\Exception $e) {
            return $this->error('Error al actualizar el perfil de usuario.', $e->getMessage(), 500);
        }
    }

    
    /**
     * @OA\Delete(
     * path="/api/user/perfil/{codigo_perfil}",
     * tags={"Web - Perfil Usuario"},
     * summary="Eliminar una perfil específica",
     * description="Devuelve un estado 204 si se eliminó",
     * 
     * @OA\Parameter(
     *      description="Necesita el codigo_perfil",
     *      in="path",
     *      name="codigo_perfil",
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
    public function destroy($codigo_perfil)
    {
        try {
            $perfilUsuario = PerfilUsuario::find($codigo_perfil);
            if (!$perfilUsuario) {
                return $this->error("No data", "Perfil de usuario no encontrado", 404);
            }
            $perfilUsuario->delete();
            return $this->success(null, "Deleted", 204);
        } catch (\Exception $e) {
            return $this->error('Error al eliminar el perfil de usuario.', $e->getMessage(), 500);
        }
    }

    private function requestToModel($request, PerfilUsuario $perfilUsuario = null): PerfilUsuario
    {
        if ($perfilUsuario == null)
            $perfilUsuario = new PerfilUsuario;
        $perfilUsuario->perfil = $request['perfil'];
        $perfilUsuario->nivel = $request['nivel'];
        $perfilUsuario->tipoperfil = $request['tipoperfil'];
        return $perfilUsuario;
    }
}
