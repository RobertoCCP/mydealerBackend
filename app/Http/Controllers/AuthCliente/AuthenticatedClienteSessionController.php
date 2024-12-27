<?php

namespace App\Http\Controllers\AuthCliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Cliente\Cliente;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\Auth\LoginRequest;

/**
 * @group AuthCliente
 *
 * Controlador para la autenticación de clientes.
 */

class AuthenticatedClienteSessionController extends Controller {


    /**
     * @OA\Post(
     *      path="/api/login/cliente",
     *      tags={"Cliente - Autenticación"},
     *      summary="Login para el cliente",
     *      description="Devuelve los siguientes datos:",
     * @OA\Parameter(
     *      description="Usuario del cliente",
     *      name="login",
     *     required=true,
     *     in="query",
     * ),
     * @OA\Parameter(
     *     description="Contraseña del cliente",
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
    public function login(Request $request) {

        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $cliente = Cliente::where('login', $request->login)->first();

        if (!$cliente) {
            return response()->json($this->imprimirError("9999", "Usuario no Existe"), 401);
        } else if ($cliente->estado === 'I') {
            return response()->json($this->imprimirError("9999", "Este usuario está inactivo"), 403);
        } else if ($cliente['password'] === $request->password) {
            $token = Auth::guard('api')->login($cliente);
        } else
            return $this->imprimirError("9999", "password incorrecto");
        unset($cliente['password']);
        $w_res = [
            "data_usuario" => $cliente,
            "token" => $token
        ];
        return $this->imprimirError("0", "Ok", [$w_res]);
    }

    /**
     * Almacena la sesión de cliente.
     *
     * @response 204 {}
     */

    /**
     * Almacena la sesión de cliente.
     *
     * @response 204 {}
     */


    public function store(LoginRequest $request): Response {
        $request->authenticate();

        $request->session()->regenerate();

        return response()->noContent();
    }

    /**
     * Cierra la sesión de cliente.
     *
     * @response 204 {}
     */

    public function destroy(Request $request): Response {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent();
    }

    /**
     * Formatea un error para respuesta JSON.
     *
     * @param string $error El código de error.
     * @param string $mensaje El mensaje de error.
     * @param array|null $i_datos Los datos adicionales (opcional).
     *
     * @return array El arreglo de respuesta JSON formateado.
     */

    function imprimirError($error, $mensaje, $i_datos = null) {
        if (isset($i_datos)) {
            return [
                "error" => $error,
                "mensaje" => $mensaje,
                "datos" => $i_datos
            ];
        } else {
            return [
                "error" => $error,
                "mensaje" => $mensaje
            ];
        }
    }
}
