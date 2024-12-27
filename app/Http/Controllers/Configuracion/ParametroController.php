<?php

namespace App\Http\Controllers\Configuracion;

use App\Http\Controllers\Controller;
use App\Models\Parametro;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Http\Resources\Configuracion\ParametroResource;

class ParametroController extends Controller {
    use HttpResponses;

    /**
     * @OA\Get(
     * path="/api/parametros",
     * tags={"Web - Parámetro"},
     * summary="Buscar todos parametros",
     * description="Devuelve una lista con todos los parametro de la aplicación",
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
    public function index() {
        Log:
        info('Parametros');
        try {
            return $this->success(ParametroResource::collection(Parametro::all()), 'Ok!!');
        } catch (\Exception $e) {
            return $this->error('Error al obtener los parámetros.', $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Post(
     * path="/api/parametros",
     * tags={"Web - Parámetro"},
     * summary="Crear Parametro",
     * description="Devuelve el tipo de novedad creado",
     * @OA\RequestBody(
     * description="Datos del parametro a crear",
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="codparametro", type="string", maxLength=20, description="Código del parametro"),
     *             @OA\Property(property="descripcion", type="string", maxLength=100, description="Descripción del parámetro"),
     *             @OA\Property(property="valor", type="string", maxLength=200, description="Valor de la categoría"),
     *             @OA\Property(property="categoria", type="string", maxLength=200, description="Categoria del parametro"),
     *             @OA\Property(property="tipo", type="string", maxLength=1, description="Tipo, es una letra"),
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
    public function store(Request $request) {
        try {
            $validatedData = $request->validate([
                'codparametro' => 'required|string|max:20',
                'descripcion' => 'nullable|string|max:100',
                'valor' => 'nullable',
                'categoria' => 'nullable|string|max:200',
                'tipo' => 'nullable|string|max:1'
            ]);

            if (Parametro::find($validatedData['codparametro'])) {
                return $this->error('Ya existe un parámetro con ese código.', null, 409);
            }

            $parametro = Parametro::create($validatedData);
            return $this->success(new ParametroResource($parametro), 'Parámetro creado exitosamente.', 201);
        } catch (\Exception $e) {
            return $this->error('Error al crear el parámetro.', $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     * path="/api/parametros/{codparametro}",
     * tags={"Web - Parámetro"},
     * summary="Buscar Parámetro específico",
     * description="Devuelve el parametro por el codparametro",
     *
     * @OA\Parameter(
     *      description="Necesita el codparametro",
     *      in="path",
     *      name="codparametro",
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
    public function show($codparametro) {
        try {
            $parametro = Parametro::findOrFail($codparametro);
            return $this->success(new ParametroResource($parametro), 'Ok!!');
        } catch (\Exception $e) {
            return $this->error('Parámetro no encontrado.', $e->getMessage(), 404);
        }
    }

    /**
     * @OA\Put(
     * path="/api/parametros/{codparametro}",
     * tags={"Web - Parámetro"},
     * summary="Actualizar parametro",
     * description="Actualiza un parametro por codparametro",
     *     @OA\Parameter(
     *      description="Necesita el codparametro",
     *      in="path",
     *      name="codparametro",
     *      required=true,
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos del parámetro a actualizar",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="descripcion", type="string", maxLength=100, description="Descripción del parámetro"),
     *             @OA\Property(property="valor", type="string", maxLength=200, description="Valor de la categoría"),
     *             @OA\Property(property="categoria", type="string", maxLength=200, description="Categoria del parametro"),
     *             @OA\Property(property="tipo", type="string", maxLength=1, description="Tipo, es una letra"),
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
    public function update(Request $request, $codparametro) {
        try {
            $validatedData = $request->validate([
                'descripcion' => 'nullable|string|max:100',
                'valor' => 'nullable',
                'categoria' => 'nullable|string|max:200',
                'tipo' => 'nullable|string|max:1'
            ]);

            $parametro = Parametro::findOrFail($codparametro);
            $parametro->update($validatedData);

            return $this->success(new ParametroResource($parametro), 'Parámetro actualizado exitosamente.');
        } catch (\Exception $e) {
            return $this->error('Error al actualizar el parámetro.', $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Delete(
     * path="/api/parametros/{codparametro}",
     * tags={"Web - Parámetro"},
     * summary="Eliminar una parametro específica",
     * description="Devuelve un estado 204 si se eliminó",
     *
     * @OA\Parameter(
     *      description="Necesita el codparametro",
     *      in="path",
     *      name="codparametro",
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
    public function destroy($codparametro) {
        try {
            $parametro = Parametro::findOrFail($codparametro);
            $parametro->delete();
            return $this->success(null, 'Parámetro eliminado exitosamente.', 204);
        } catch (\Exception $e) {
            return $this->error('Error al eliminar el parámetro.', $e->getMessage(), 500);
        }
    }
}
