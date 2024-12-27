<?php
namespace App\Http\Controllers\OpcionesMenu;

use App\Http\Controllers\Controller;
use App\Models\OpcionesMenu\Opcion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Tag(
 *     name="Web - Opciones de Menú",
 *     description="Controlador para manejar las opciones del menú."
 * )
 */
class OpcionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/opcions",
     *     tags={"Web - Opciones de Menú"},
     *     summary="Mostrar todas las opciones de menú",
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
            $opcion = Opcion::all();
            if ($opcion) {
                $o_res = $this->imprimirError("0", "Ok", $opcion);
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
     *     path="/api/opcions/{id}",
     *     tags={"Web - Opciones de Menú"},
     *     summary="Mostrar una opción de menú específica",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="El ID de la opción de menú",
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
            $opcion = Opcion::find($id);
            if ($opcion) {
                $o_res = $this->imprimirError("0", "Ok", $opcion);
            } else {
                $o_res = $this->imprimirError("9998", "Sin datos");
            }
        } catch (\Throwable $th) {
            $o_res = $this->imprimirError("9999", "Error interno del servidor", $th->getMessage());
        }
        return response()->json($o_res);
    }

    /**
     * @OA\Post(
     *     path="/api/opcions",
     *     tags={"Web - Opciones de Menú"},
     *     summary="Almacenar una nueva opción de menú",
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
            $opcion = Opcion::create($request->all());
            if (isset($opcion)) {
                $o_res = $this->imprimirError("0", "Ok", $opcion);
            }
        } catch (\Throwable $th) {
            $o_res = $this->imprimirError("9999", "Error interno del servidor", $th->getMessage());
        }
        return response()->json($o_res, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/opcions/{id}",
     *     tags={"Web - Opciones de Menú"},
     *     summary="Actualizar una opción de menú existente",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="El ID de la opción de menú a actualizar",
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
    public function update(Request $request, $id)
    {
        try {
            $opcion = Opcion::find($id);
            if ($opcion) {
                $opcion->update($request->all());
                $o_res = $this->imprimirError("0", "Actualizado", $opcion);
            } else {
                $o_res = $this->imprimirError("9998", "Sin datos");
            }
        } catch (\Throwable $th) {
            $o_res = $this->imprimirError("9999", "Error interno del servidor", $th->getMessage());
        }
        return response()->json($o_res);
    }

    /**
     * @OA\Delete(
     *     path="/api/opcions/{id}",
     *     tags={"Web - Opciones de Menú"},
     *     summary="Eliminar una opción de menú",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="El ID de la opción de menú a eliminar",
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
            $opcion = Opcion::find($id);
            if ($opcion) {
                $opcion->delete();
                $o_res = $this->imprimirError("0", "Eliminado");
            } else {
                $o_res = $this->imprimirError("9998", "Sin datos");
            }
        } catch (\Throwable $th) {
            $o_res = $this->imprimirError("9999", "Error interno del servidor", $th->getMessage());
        }
        return response()->json($o_res);
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
