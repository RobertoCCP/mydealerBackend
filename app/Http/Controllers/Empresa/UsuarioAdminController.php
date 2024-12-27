<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Empresa\UsuarioAdmin;
use App\Models\Empresa\Division;
use App\Models\Roles\Perfil;
use App\Http\Resources\Empresa\UsuarioAdminResource;
use App\Traits\HttpResponses;
use App\Http\Requests\Empresa\UsuarioAdminRequest;

class UsuarioAdminController extends Controller
{
    use HttpResponses;

    /**
     * @OA\Get(
     * path="/api/user/administracion",
     * tags={"Web - Usuarios de Administración"},
     * summary="Buscar todos administradores",
     * description="Devuelve una lista con todos los usuarios administrativos de la aplicación",
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
            $usuariosAdmin = UsuarioAdmin::with(['division', 'perfil'])->get();
            if ($usuariosAdmin->isEmpty()) {
                return $this->error('No data', 'No se encontraron perfiles.', 404);
            }
            $usuariosAdmin->each(function ($usuario, $index) {
                $usuario->nro = $index + 1;
            });
            $message = 'Usuarios administradores encontrados correctamente';
            return $this->success(UsuarioAdminResource::collection($usuariosAdmin), $message);
        } catch (\Exception $e) {
            return $this->error('Error al obtener los perfiles.', $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     * path="/api/user/administracion/{loginusuario}",
     * tags={"Web - Usuarios de Administración"},
     * summary="Buscar un administrador",
     * description="Devuelve un usuario administrativo por loginusuario",
     * 
     * @OA\Parameter(
     *      description="Necesita el loginusuario",
     *      in="path",
     *      name="loginusuario",
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
    public function show($loginusuario)
    {
        try {
            $usuarioAdmin = UsuarioAdmin::with(['division', 'perfil'])->where('loginusuario', $loginusuario)->first();
            if (!$usuarioAdmin) {
                return $this->error('Not Found', 'Usuario no encontrado.', 404);
            }
            $message = 'Usuario administrador encontrado correctamente';
            return $this->success(new UsuarioAdminResource($usuarioAdmin), $message);
        } catch (\Exception $e) {
            return $this->error('Error al obtener el usuario administrador.', $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Delete(
     * path="/api/user/administracion/{loginusuario}",
     * tags={"Web - Usuarios de Administración"},
     * summary="Eliminar un administrador",
     * description="Devuelve un estado 204 si se eliminó",
     * 
     * @OA\Parameter(
     *      description="Necesita el loginusuario",
     *      in="path",
     *      name="loginusuario",
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
    public function destroy($loginusuario)
    {
        try {
            $usuarioAdmin = UsuarioAdmin::with(['division', 'perfil'])->where('loginusuario', $loginusuario)->first();
            if (!$usuarioAdmin) {
                return $this->error('Not Found', 'Usuario no encontrado.', 404);
            }
            $usuarioAdmin->delete();
            return $this->success(null, "Usuario administrativo eliminado correctamente.");
        } catch (\Exception $e) {
        }
    }

    /**
     * @OA\Post(
     * path="/api/user/administracion",
     * tags={"Web - Usuarios de Administración"},
     * summary="Crear usuario administrador",
     * description="Devuelve el usuario administrativo creado",
     * @OA\RequestBody(
     * description="Datos del parámetro a crear",
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *                @OA\Property(property="loginusuario", type="string", minLength=6, maxLength=12, description="Login del usuario admin"),
     *                @OA\Property(property="password", type="string", minLength=6, maxLength=12, description="Password del usuario admin"),
     *                @OA\Property(property="nombre", type="string", maxLength=50, description="Nombre del usuario admin"),
     *                @OA\Property(property="apellido", type="string", maxLength=50, description="Apellido del usuario admin"),
     *                @OA\Property(property="email", type="string", format="email", maxLength=100, description="Correo electrónico del usuario admin"),
     *                @OA\Property(property="cargo", type="string", maxLength=1, description="Cargo del usuario admin"),
     *                @OA\Property(property="codperfil", type="integer", description="Código del perfil del usuario admin", example=1),
     *                @OA\Property(property="coddivision", type="string", maxLength=10, description="Código de la división del usuario admin")
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

    public function store(UsuarioAdminRequest $request)
    {
        try {
            $validated = $request->validated();

            $usuarioAdmin = new UsuarioAdmin();
            $usuarioAdmin->loginusuario = $validated['loginusuario'];
            $usuarioAdmin->password = bcrypt($validated['password']);
            $usuarioAdmin->nombre = $validated['nombre'];
            $usuarioAdmin->apellido = $validated['apellido'];
            $usuarioAdmin->email = $validated['email'];
            $usuarioAdmin->cargo = $request->input('cargo');
            $usuarioAdmin->codperfil = $validated['codperfil'];
            $usuarioAdmin->estado = 'A';
            $usuarioAdmin->coddivision = $validated['coddivision'];

            $usuarioAdmin->save();

            $message = 'Usuario administrador creado correctamente';

            return $this->success(new UsuarioAdminResource($usuarioAdmin), $message, 201);
        } catch (\Exception $e) {
            return $this->error('Error al crear el usuario administrador.', $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Put(
     *  path="/api/user/administracion/{loginusuario}",
     * tags={"Web - Usuarios de Administración"},
     * summary="Actualiza usuario administrador",
     * description="Devuelve el usuarios administrativo actualizado",
     *     @OA\Parameter(
     *      description="Necesita el loginusuario",
     *      in="path",
     *      name="loginusuario",
     *      required=true,
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos del parámetro a actualizar",
     *         @OA\JsonContent(
     *             type="object",
     *                @OA\Property(property="loginusuario", type="string", minLength=6, maxLength=12, description="Login del usuario admin"),
     *                @OA\Property(property="password", type="string", minLength=6, maxLength=12, description="Password del usuario admin"),
     *                @OA\Property(property="nombre", type="string", maxLength=50, description="Nombre del usuario admin"),
     *                @OA\Property(property="apellido", type="string", maxLength=50, description="Apellido del usuario admin"),
     *                @OA\Property(property="email", type="string", format="email", maxLength=100, description="Correo electrónico del usuario admin"),
     *                @OA\Property(property="codperfil", type="integer", description="Código del perfil del usuario admin", example=1),
     *                @OA\Property(property="coddivision", type="string", maxLength=10, description="Código de la división del usuario admin")
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
    public function update(UsuarioAdminRequest $request, $loginusuario)
    {
        try {
            $usuarioAdmin = UsuarioAdmin::findOrFail($loginusuario);

            $validated = $request->validated();

            if (isset($validated['password'])) {
                $validated['password'] = bcrypt($validated['password']);
            }

            $usuarioAdmin->update($validated);

            $message = 'Usuario administrador actualizado correctamente';

            return $this->success(new UsuarioAdminResource($usuarioAdmin), $message);
        } catch (\Exception $e) {
            return $this->error('Error al actualizar el usuario administrador.', $e->getMessage(), 500);
        }
    }

    /**
     * Obtiene todos los perfiles disponibles.
     *
     * @OA\Get(
     *     path="/api/user/administracion/perfiles",
     *     tags={"Web - Usuarios de Administración"},
     *     summary="Obtener todos los perfiles",
     *     description="Devuelve una lista con todos los perfiles disponibles en la aplicación",
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa. Devuelve la lista de perfiles.",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No encontrado. No se encontraron perfiles en la base de datos.",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error del servidor. Error interno al procesar la solicitud.",
     *     ),
     * )
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerPerfiles()
    {
        try {
            $perfiles = Perfil::get();
            if ($perfiles->isEmpty()) {
                return $this->error('No data', 'No se encontraron perfiles.', 404);
            }

            $perfiles->each(function ($perfil, $index) {
                $perfil->nro = $index + 1;
            });
            $message = 'Perfiles encontrados correctamente';

            return $this->success($perfiles, $message);
        } catch (\Exception $e) {
            return $this->error('Error al obtener los perfiles.', $e->getMessage(), 500);
        }
    }

    /**
     * Obtiene todas las divisiones disponibles.
     *
     * @OA\Get(
     *     path="/api/user/administracion/divisiones",
     *     tags={"Web - Usuarios de Administración"},
     *     summary="Obtener todas las divisiones",
     *     description="Devuelve una lista con todas las divisiones disponibles en la aplicación",
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa. Devuelve la lista de divisiones.",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No encontrado. No se encontraron divisiones en la base de datos.",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error del servidor. Error interno al procesar la solicitud.",
     *     ),
     * )
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerDivisiones()
    {
        try {
            $divisiones = Division::get();
            if ($divisiones->isEmpty()) {
                return $this->error('No data', 'No se encontraron divisiones.', 404);
            }

            $divisiones->each(function ($division, $index) {
                $division->nro = $index + 1;
            });
            $message = 'Divisiones encontrados correctamente';

            return $this->success($divisiones, $message);
        } catch (\Exception $e) {
            return $this->error('Error al obtener los perfiles.', $e->getMessage(), 500);
        }
    }
}
