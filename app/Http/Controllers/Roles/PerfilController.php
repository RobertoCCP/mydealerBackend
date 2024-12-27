<?php

namespace App\Http\Controllers\Roles;

use App\Http\Controllers\Controller;
use App\Models\Roles\Perfil;
use App\Models\Roles\Opcion;
use App\Models\Roles\OpcionPerfil;
use App\Traits\HttpResponses;
use App\Http\Resources\Roles\PerfilResource;
use App\Http\Resources\Roles\OpcionResource;

class PerfilController extends Controller
{
    use HttpResponses;

    /**
     * @OA\Get(
     * path="/api/opcion/perfil/usuario",
     * tags={"Web - Opciones por perfil"},
     * summary="Buscar todas las opciones",
     * description="Devuelve una lista con todas las opciones de perfil",
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
            $perfiles = Perfil::with('tipoPerfil')->get();

            if ($perfiles->isEmpty()) {
                return $this->error('No data', 'No se encontraron perfiles.', 404);
            }

            $message = 'Perfiles encontrados correctamente';
            return $this->success(PerfilResource::collection($perfiles), $message);
        } catch (\Exception $e) {
            return $this->error('Error al obtener los perfiles.', $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     * path="/api/opcion/perfil/usuario/{codigo_perfil}",
     * tags={"Web - Opciones por perfil"},
     * summary="Buscar una opcion de perfil",
     * description="Devuelve una  opcionde perfil por codigo_perfil",
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
            $perfil = Perfil::with('tipoperfil')->find($codigo_perfil);

            if (!$perfil) {
                return $this->error('No data', 'Perfil no encontrado', 404);
            }

            $message = 'Perfil encontrado correctamente';
            return $this->success(new PerfilResource($perfil), $message);
        } catch (\Exception $e) {
            return $this->error('Error al obtener un perfil.', $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     * path="/api/opcion/perfil/{srperfil}",
     * tags={"Web - Opciones por perfil"},
     * summary="Busca las opcions de un perfil",
     * description="Devuelve las opciones de perfil por srperfil",
     * 
     * @OA\Parameter(
     *      description="Necesita el srperfil",
     *      in="path",
     *      name="srperfil",
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
    public function opcionesPorPerfil($srperfil)
    {
        try {
            $perfil = Perfil::with('tipoPerfil')->find($srperfil);

            if (!$perfil) {
                return $this->error('No data', 'Perfil no encontrado', 404);
            }

            $codtipoperfil = $perfil->tipoperfil;

            $opciones = Opcion::where('tipoopcion', $codtipoperfil)
                ->with(['menuCabecera'])
                ->get()
                ->map(function ($opcion) use ($srperfil) {
                    $opcion->seleccion = $opcion->perfiles()->where('opcionperfil.srperfil', $srperfil)->exists();
                    return $opcion;
                });

            if ($opciones->isEmpty()) {
                return $this->error('No data', 'No se encontraron opciones.', 404);
            }

            $message = 'Opciones encontradas correctamente';
            return $this->success(OpcionResource::collection($opciones), $message);
        } catch (\Exception $e) {
            return $this->error('Error al obtener las opciones.', $e->getMessage(), 500);
        }
    }

    /**
    * @OA\Put(
    * path="/api/opcion/perfil/usuario/{srperfil}/{sropcion}",
    * tags={"Web - Opciones por perfil"},
    * summary="Actualizar una opcion de perfil",
    * description="Devuelve una un mensaje del perfil actualizado",
    *     @OA\Parameter(
    *      description="Necesita el srperfil que es el codigo de perfil",
    *      in="path",
    *      name="srperfil",
    *      required=true,
    *     ),
    *     @OA\Parameter(
    *      description="Necesita el sropcion que es el codigo de opcion",
    *      in="path",
    *      name="sropcion",
    *      required=true,
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
    public function actualizarSeleccion($srperfil, $sropcion)
    {
        try {
            $opcionPerfil = OpcionPerfil::where('srperfil', $srperfil)
                ->where('sropcion', $sropcion)
                ->first();

            if (!$opcionPerfil) {
                OpcionPerfil::create([
                    'srperfil' => $srperfil,
                    'sropcion' => $sropcion,
                ]);
                $message = 'Opción seleccionada correctamente';
            } else {
                $opcionPerfil->delete();
                $message = 'Opción deseleccionada correctamente';
            }

            return $this->success([], $message);
        } catch (\Exception $e) {
            return $this->error('Error al actualizar la selección.', $e->getMessage(), 500);
        }
    }
}
