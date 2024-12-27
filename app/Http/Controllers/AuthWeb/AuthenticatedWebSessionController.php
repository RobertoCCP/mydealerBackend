<?php

namespace App\Http\Controllers\AuthWeb;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Cliente\Cliente;
use App\Models\Vendedor\Vendedor;
use App\Models\Empresa;
use App\Models\Empresa\UsuarioAdmin;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use App\Traits\HttpResponses;

class AuthenticatedWebSessionController extends Controller
{
    use HttpResponses;
    /**
     * @OA\Post(
     *      path="/api/loginweb",
     *      tags={"Web - Autenticación"},
     *      summary="Inicio de sesión para usuarioas administrativos, clientes y vendedores en el sistema web",
     *      description="Devuelve los siguientes datos:",
     * @OA\Parameter(
     *      description="Usuario (administrativo, cliente, vendedor)",
     *      name="login",
     *     required=true,
     *     in="query",
     * ),
     * @OA\Parameter(
     *     description="Contraseña (administrativo, cliente, vendedor)",
     *    name="password",
     *   required=true,
     *  in="query",
     * ),
     * @OA\Response(
     *    response=200,
     *  description="Devuelve el token de autenticación",
     * @OA\JsonContent(
     *   @OA\Property(
     *     property="data_usuario",
     *   type="object",
     * ),
     * @OA\Property(
     *  property="token",
     * type="string",
     * ),
     * ),
     * ),
     * )
     *
     * */
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        try {
            $cliente = Cliente::where('login', $request->login)->first();
            if ($cliente) {
                if ($cliente->estado === 'I') {
                    return response()->json($this->imprimirError("9999", "Este usuario está inactivo"), 403);
                }

                if ($cliente->password === $request->password) {
                    $token = Auth::guard('api')->login($cliente);
                    return $this->responseConToken($token, $cliente);
                }
            }

            $vendedor = Vendedor::where('login', $request->login)->first();
            if ($vendedor) {
                if ($vendedor->estado === 'I') {
                    return response()->json($this->imprimirError("9999", "Este usuario está inactivo"), 403);
                }

                if ($vendedor->password === $request->password) {
                    $token = Auth::guard('api')->login($vendedor);
                    return $this->responseConToken($token, $vendedor);
                }
            }

            $usuarioadmin = UsuarioAdmin::where('loginusuario', $request->login)->first();
            if ($usuarioadmin) {
                if ($usuarioadmin->estado === 'I') {
                    return response()->json($this->imprimirError("9999", "Este usuario está inactivo"), 403);
                }

                if (Hash::check($request->password, $usuarioadmin->password)) {
                    $token = Auth::guard('api')->login($usuarioadmin);
                    return $this->responseConToken($token, $usuarioadmin);
                }
            }

            $administrador = Empresa::where('usuarioadmin', $request->login)->first();
            if ($administrador) {
                if ($administrador->claveadmin === $request->password) {
                    $token = Auth::guard('api')->login($administrador);
                    return $this->responseConToken($token, $administrador);
                }
            }

            return $this->error(null, 'Usuario o contraseña incorrectos', 401);
        } catch (\Exception $e) {
            return $this->error('Error en el login web.', $e->getMessage(), 500);
        }
    }


    public function store(LoginRequest $request): Response
    {
        $request->authenticate();

        $request->session()->regenerate();

        return response()->noContent();
    }

    public function destroy(Request $request): Response
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent();
    }

    protected function responseConToken($token, $user)
    {
        unset($user['password']);
        $w_res = [
            "data_usuario" => $user,
            "token" => $token
        ];
        return $this->success([$w_res], 'OK');
    }

    /**
     * @OA\Post(
     * path="/api/usuario/admin/recuperacion",
     * tags={"Web - Autenticación"},
     * summary="Enviar correo de recuperación con clave",
     * description="Envía un correo electrónico con una clave temporal para la recuperación de la cuenta.",
     * @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="email", type="string", description="Correo electrónico del vendedor"),
     *         @OA\Property(property="usuario", type="string", description="Usuario administrativo")
     *     )
     * ),
     * @OA\Response(
     *     response=200,
     *     description="Correo enviado",
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="error", type="string"),
     *         @OA\Property(property="mensaje", type="string")
     *     )
     * ),
     * @OA\Response(
     *     response=404,
     *     description="Correo no existe",
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="error", type="string"),
     *         @OA\Property(property="mensaje", type="string")
     *     )
     * )
     * )
     */
    public function recuperarClave(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'usuario' => 'required|string'
        ]);

        $w_claveTem = $this->claveAutomatica(10);
        $usuario = UsuarioAdmin::where('loginusuario', $request->usuario)->first();
        if (!$usuario) {
            return response()->json($this->imprimirError(404, "Usuario no existe"), 404);
        } else {
            $usuario->update(['password' => $w_claveTem]);

            $html = 'Esta es su clave de recuperación: ' . $w_claveTem;
            Mail::raw($html, function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('Clave de recuperación');
            });
            return response()->json($this->imprimirError(200, "Correo enviado"));
        }
    }

    function claveAutomatica($i_longitud)
    {
        $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
        $cad = "";
        for ($i = 0; $i < $i_longitud; $i++) {
            $cad .= substr($str, rand(0, 62), 1);
        }
        return $cad;
    }
}
