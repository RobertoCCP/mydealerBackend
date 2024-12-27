<?php
namespace App\Http\Controllers\OpcionesMenu;

use App\Http\Controllers\Controller;
use App\Models\OpcionesMenu\Menucabecera;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Tag(
 *     name="Web - Opciones de Menú",
 *     description="Controlador para manejar las opciones de menú."
 * )
 */
class MenucabeceraController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/menucabecerasCompleto",
     *     tags={"Web - Opciones de Menú"},
     *     summary="Mostrar todas las cabeceras de menú",
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
            $cabecera = Menucabecera::all();
            if($cabecera){
                $o_res = $this->imprimirError("0", "Ok", $cabecera);
            } else {
                $o_res = $this->imprimirError("9998", "Sin datos");
            }
        } catch (\Throwable $th) {
            $o_res = $this->imprimirError("9999", "Error interno del servidor", $th->getMessage());
        }
        return response()->json($o_res);
    }

    /**
     * @OA\Get(
     *     path="/api/menucabeceras/{id}",
     *     tags={"Web - Opciones de Menú"},
     *     summary="Mostrar una cabecera de menú específica",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="El ID de la cabecera de menú",
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
    public function show($id)
    {
        try {
            $cabecera = Menucabecera::find($id);
            if($cabecera){
                $o_res = $this->imprimirError("0", "Ok", $cabecera);
            } else {
                $o_res = $this->imprimirError("9998", "Sin datos");
            }
        } catch (\Throwable $th) {
            $o_res = $this->imprimirError("9999", "Error interno del servidor", $th->getMessage());
        }
        return response()->json($o_res);
    }

    /**
     * @OA\Get(
     *     path="/api/menucabecerasCompleto/{id}",
     *     tags={"Web - Opciones de Menú"},
     *     summary="Mostrar una cabecera de menú con sus opciones",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="El ID de la cabecera de menú (opcional)",
     *         @OA\Schema(type="integer", default=-1)
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
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="campo1", type="string", example="valor1"),
     *                     @OA\Property(property="campo2", type="string", example="valor2"),
     *                     @OA\Property(
     *                         property="opcion",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="campo1", type="string", example="valor3"),
     *                             @OA\Property(property="campo2", type="string", example="valor4")
     *                         )
     *                     )
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
    public function completo($id = -1)
    {
        try {
            $w_cabecera = [];
            if ($id == -1) {
                $cabecera = Menucabecera::all();
                $w_cabecera = $cabecera;
            } else {
                $cabecera = Menucabecera::find($id);
                if($cabecera) array_push($w_cabecera, $cabecera);
            }

            if(count($w_cabecera) > 0){
                foreach ($w_cabecera as $key => $value) {
                    $opcion = DB::table('opcion')
                        ->join('perfil as p', 'p.srperfil', '=', 'opcion.perfil')
                        ->where('opcion.codmenucabecera', '=', $value->codmenucabecera)
                        ->select('opcion.*', 'p.perfil as nombre_perfil')
                        ->get();
                    $value->opcion = $opcion;
                }
                $o_res = $this->imprimirError("0", "Ok", $w_cabecera);
            } else {
                $o_res = $this->imprimirError("9998", "Sin datos");
            }
        } catch (\Exception $e) {
            $o_res = $this->imprimirError("9999", "Error interno del servidor", $e->getMessage());
        }
        return response()->json($o_res);
    }

    /**
     * @OA\Post(
     *     path="/api/menucabeceras",
     *     tags={"Web - Opciones de Menú"},
     *     summary="Almacenar una nueva cabecera de menú",
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
            $menucabecera = Menucabecera::create($request->all());
            if(isset($menucabecera)){
                $o_res = $this->imprimirError("0", "Ok", $menucabecera);
            }
        } catch (\Throwable $th) {
            $o_res = $this->imprimirError("9999", "Error interno del servidor", $th->getMessage());
        }
        return response()->json($o_res, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/menucabeceras/{id}",
     *     tags={"Web - Opciones de Menú"},
     *     summary="Actualizar una cabecera de menú existente",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="El ID de la cabecera de menú a actualizar",
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
     *         response=404,
     *         description="No existe el id",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="9998"),
     *             @OA\Property(property="mensaje", type="string", example="No existe el id")
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
    public function update(Request $request, $id)
    {
        try {
            $menucabecera = Menucabecera::find($id);
            if($menucabecera){
                $menucabecera->update($request->all());
                $o_res = $this->imprimirError("0", "Actualizado", $menucabecera);
            } else {
                $o_res = $this->imprimirError("9998", "No existe el id");
            }
        } catch (\Throwable $th) {
            $o_res = $this->imprimirError("9999", "Error interno del servidor", $th->getMessage());
        }
        return response()->json($o_res);
    }

    /**
     * @OA\Delete(
     *     path="/api/menucabeceras/{id}",
     *     tags={"Web - Opciones de Menú"},
     *     summary="Eliminar una cabecera de menú existente",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="El ID de la cabecera de menú a eliminar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Eliminado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="0"),
     *             @OA\Property(property="mensaje", type="string", example="Eliminado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No existe el id",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="9998"),
     *             @OA\Property(property="mensaje", type="string", example="No existe el id")
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
    public function destroy($id)
    {
        try {
            $menucabecera = Menucabecera::find($id);
            if($menucabecera){
                $menucabecera->delete();
                $o_res = $this->imprimirError("0", "Eliminado");
            } else {
                $o_res = $this->imprimirError("9998", "No existe el id");
            }
        } catch (\Throwable $th) {
            $o_res = $this->imprimirError("9999", "Error interno del servidor", $th->getMessage());
        }
        return response()->json($o_res);
    }

    /**
     * Formatea un error para respuesta JSON.
     *
     * @param string $error El código de error.
     * @param string $mensaje El mensaje de error.
     * @param mixed|null $i_datos Los datos adicionales (opcional).
     *
     * @return array El arreglo de respuesta JSON formateado.
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
