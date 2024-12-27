<?php

namespace App\Http\Controllers\OpcionesMenu;

use App\Models\OpcionesMenu\Perfil;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * @OA\Tag(
 *     name="Perfiles de Usuario",
 *     description="Controlador para gestionar los perfiles de usuario."
 * )
 */
class PerfilController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/perfil",
     *     tags={"Perfiles de Usuario"},
     *     summary="Mostrar todos los perfiles de usuario",
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="0"),
     *             @OA\Property(property="mensaje", type="string", example="Ok"),
     *             @OA\Property(
     *                 property="datos",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="campo1", type="string", example="valor1"),
     *                     @OA\Property(property="campo2", type="string", example="valor2")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Sin datos",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="9998"),
     *             @OA\Property(property="mensaje", type="string", example="Sin datos")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="9999"),
     *             @OA\Property(property="mensaje", type="string", example="Error interno del servidor"),
     *             @OA\Property(property="datos", type="string", example="Descripción del error")
     *         )
     *     )
     * )
     */
    public function index()
    {
        try {
            $perfils = Perfil::all();
            if(count($perfils) > 0){
                $o_res = $this->imprimirError("0", "Ok",$perfils);
            }else $o_res = $this->imprimirError("9998", "Sin datos");

        } catch (\Throwable $th) {
            $o_res = $this->imprimirError("9999",$th->errorInfo);
        }
        return response()->json($o_res);
    }

     /**
     * @OA\Get(
     *     path="/api/perfil/{id}",
     *     tags={"Perfiles de Usuario"},
     *     summary="Mostrar un perfil de usuario específico",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="El ID del perfil de usuario",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="0"),
     *             @OA\Property(property="mensaje", type="string", example="Ok"),
     *             @OA\Property(
     *                 property="datos",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="campo1", type="string", example="valor1"),
     *                 @OA\Property(property="campo2", type="string", example="valor2")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Sin datos",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="9998"),
     *             @OA\Property(property="mensaje", type="string", example="Sin datos")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="9999"),
     *             @OA\Property(property="mensaje", type="string", example="Error interno del servidor"),
     *             @OA\Property(property="datos", type="string", example="Descripción del error")
     *         )
     *     )
     * )
     */
    public function show($perfil)
    {
        try {
             $perfils = Perfil::find($perfil);
            if(isset($perfils)){
                $o_res = $this->imprimirError("0", "Ok",$perfils);
            }else $o_res = $this->imprimirError("9998", "Sin datos");
        } catch (\Throwable $th) {
            $o_res = $this->imprimirError("9999",$th->errorInfo);
        }
        return response()->json($o_res);
    }

    /**
     * @OA\Post(
     *     path="/api/perfil",
     *     tags={"Perfiles de Usuario"},
     *     summary="Almacenar un nuevo perfil de usuario",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="campo1", type="string", description="Descripción del campo1", example="valor1"),
     *             @OA\Property(property="campo2", type="string", description="Descripción del campo2", example="valor2")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Ok",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="0"),
     *             @OA\Property(property="mensaje", type="string", example="Ok"),
     *             @OA\Property(
     *                 property="datos",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="campo1", type="string", example="valor1"),
     *                 @OA\Property(property="campo2", type="string", example="valor2")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="9999"),
     *             @OA\Property(property="mensaje", type="string", example="Error interno del servidor"),
     *             @OA\Property(property="datos", type="string", example="Descripción del error")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        try {
            $perfil = Perfil::create($request->all());
            if(isset($perfil)){
                $o_res = $this->imprimirError("0", "Ok", $perfil);
            }
        } catch (\Throwable $th) {
            $o_res = $this->imprimirError("9999",$th->errorInfo);
        }
        return response()->json($o_res, 201);
    }


    /**
     * @OA\Put(
     *     path="/api/perfil/{id}",
     *     tags={"Perfiles de Usuario"},
     *     summary="Actualizar un perfil de usuario existente",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="El ID del perfil de usuario a actualizar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="campo1", type="string", description="Descripción del campo1", example="valor1"),
     *             @OA\Property(property="campo2", type="string", description="Descripción del campo2", example="valor2")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Actualizado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="0"),
     *             @OA\Property(property="mensaje", type="string", example="Actualizado"),
     *             @OA\Property(
     *                 property="datos",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="campo1", type="string", example="valor1"),
     *                 @OA\Property(property="campo2", type="string", example="valor2")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="9999"),
     *             @OA\Property(property="mensaje", type="string", example="Error interno del servidor"),
     *             @OA\Property(property="datos", type="string", example="Descripción del error")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $perfil)
    {
        try {
            $perfils = Perfil::find($perfil);
            if(isset($perfils)){
                $perfils->update($request->all());
                $o_res = $this->imprimirError("0", "Actualizado", $perfils);
            }else $o_res = $this->imprimirError("9998", "Sin datos");
        } catch (\Throwable $th) {
            $o_res = $this->imprimirError("9999",$th->errorInfo);
        }
        return response()->json($o_res);
    }

    /**
     * @OA\Delete(
     *     path="/api/perfil/{id}",
     *     tags={"Perfiles de Usuario"},
     *     summary="Eliminar un perfil de usuario",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="El ID del perfil de usuario a eliminar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="No Content",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="0"),
     *             @OA\Property(property="mensaje", type="string", example="Eliminado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="9999"),
     *             @OA\Property(property="mensaje", type="string", example="Error interno del servidor"),
     *             @OA\Property(property="datos", type="string", example="Descripción del error")
     *         )
     *     )
     * )
     */
    public function destroy($perfil)
    {
        try {
            $perfils = Perfil::find($perfil);
            if(isset($perfils)){
                $perfils->delete();
                $o_res = $this->imprimirError("0", "Eliminado");
            }else $o_res = $this->imprimirError("9998", "Sin datos");
        } catch (\Throwable $th) {
            $o_res = $this->imprimirError("9999",$th->errorInfo);
        }
        return response()->json($o_res, 204);
    }

        /**
     * Función para manejar los mensajes de error
     *
     * @param string $codigo
     * @param string $mensaje
     * @param mixed $datos
     * @return array
     */

    function imprimirError($error, $mensaje, $i_datos = null)
    {
        if (isset($i_datos))
            return [
                "error" => $error,
                "mensaje" => $mensaje,
                "datos" => $i_datos
            ];
        else
            return [
                "error" => $error,
                "mensaje" => $mensaje
            ];
    }
}
