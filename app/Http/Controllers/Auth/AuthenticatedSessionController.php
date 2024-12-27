<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Vendedor\Vendedor;

class AuthenticatedSessionController extends Controller {

    /**
     * @OA\Post(
     *  path="/api/login",
     *  tags={"Vendedor - Autenticación"},
     *  summary="Login para el vendedor",
     *  description="Devuelve los siguientes datos:",
     * @OA\Parameter(
     *  description="Usuario del vendedor",
     *  name="login",
     *  required=true,
     *  in="query",
     * ),
     * @OA\Parameter(
     *  description="Contraseña del vendedor",
     *  name="password",
     *  required=true,
     *  in="query",
     * ),
     * @OA\Response(
     *  response=200,
     *  description="Devuelve el token de autenticación",
     * @OA\JsonContent(
     *   @OA\Property(
     *  property="data_usuario",
     *  type="object",
     * ),
     *   @OA\Property(
     *  property="token",
     *  type="string",
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
        $vendedor = Vendedor::where('login', $request->login)->first();
        if (!$vendedor) return $this->imprimirError("9999", "Usuario no Existe");
        // Verificar el estado del usuario
        else  if ($vendedor->estado === 'I') {
            return $this->imprimirError("9999", "Este usuario está inactivo");
        } else if ($vendedor['password'] === $request->password) {
            $token = Auth::guard('api')->login($vendedor);
        } else return $this->imprimirError("9999", "password incorrecto");
        unset($vendedor['password']);
        $w_res = [
            "data_usuario" => $vendedor,
            "token" => $token
        ];
        return $this->imprimirError("0", "Ok", [$w_res]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): Response {
        $request->authenticate();

        $request->session()->regenerate();

        return response()->noContent();
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent();
    }
    function imprimirError($error, $mensaje, $i_datos = null) {
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
