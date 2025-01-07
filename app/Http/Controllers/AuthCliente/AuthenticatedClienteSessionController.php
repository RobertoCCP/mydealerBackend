<?php

namespace App\Http\Controllers\AuthCliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Cliente\Cliente;
use App\Models\Vendedor\Vendedor;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
//use App\Models\Reportes\ReporteGPSEstado\Vendedor;

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
    public function loginVendedor(Request $request) {

        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);
        $vendedor = Vendedor::where('login', $request->login)->first();
        if (!$vendedor) {
            return response()->json($this->imprimirError("9999", "Usuario no Existe"), 401);
        } else if ($vendedor->estado === 'I') {
            return response()->json($this->imprimirError("9999", "Este usuario está inactivo"), 403);
        } else if ($vendedor['password'] === $request->password) {
            $token = Auth::guard('api')->login($vendedor);
        } else
            return $this->imprimirError("9999", "password incorrecto");
        unset($vendedor['password']);
        $codigo = $this->generarCodigoTemporal($vendedor['codvendedor']);
        // var_dump($codigo);
        //$w_cliente['nombrecomercial'] = $codigo;
        $w_enviarCorreo = $this->envioCorreo($vendedor['email'], 'Código de verificación', "Su código de verificación es: " . $codigo);
        var_dump($w_enviarCorreo);
        $w_res = [
            "data_usuario" => $vendedor,
            "token" => $token
        ];
        return $this->imprimirError("0", "Ok", [$w_res]);
    }
    function generarCodigoTemporal($clave, $duracionEnMinutos = 60)
    {
        $codigo = Str::random(6);
        Cache::put($clave, $codigo, now()->addMinutes($duracionEnMinutos));
        return $codigo;
    }


    function envioCorreo($destinatario, $titulo = 'Mensaje de prueba Mydealer', $mensaje = 'Mensaje enviado desde Mydealer') {
        if ($destinatario == null) {
            return false;
        }
        var_dump($destinatario);
        try {
            Mail::raw($mensaje, function ($message) use ($destinatario, $titulo) {
                $message->to($destinatario)->subject($titulo);
            });
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    function verificarCodigoTemporal($clave, $codigoIngresado)
    {
        $codigoGuardado = Cache::get($clave);
        // var_dump($codigoGuardado);
        if (!$codigoGuardado) {
            return $this->imprimirError('9998', 'El codigo ingresado no encontrado');
        }
        if($codigoIngresado == $codigoGuardado)
            return $this->imprimirError('0', 'Codigo verificado');
        else  return $this->imprimirError('9998', 'El codigo ingresado no coincide');
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
