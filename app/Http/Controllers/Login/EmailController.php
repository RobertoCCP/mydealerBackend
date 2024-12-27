<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Vendedor\Vendedor;

class EmailController extends Controller {
    /**
     * @OA\Post(
     * path="/api/recuperacionEmail",
     * tags={"Vendedor - Autenticación"},
     * summary="Enviar correo de recuperación",
     * description="Envía un correo electrónico con una clave temporal para la recuperación de la cuenta.",
     * @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="email", type="string", description="Correo electrónico del vendedor")
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
    public function enviarEmail(Request $request) {
        // $request->validate([
        //     'email' => 'required|email',
        // ]);
        $w_claveTem = $this->claveAutomatica(10);
        $vendedor = Vendedor::where('email', $request->email)->first();
        if (!$vendedor) {
            return $this->imprimirError("9999", "Correo no existe");
        } else {
            $vendedor->update([
                "password" => $w_claveTem
            ]);
            $html = 'Esta es su clave de recuperación: ' . $w_claveTem;
            Mail::raw($html, function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('Clave de recuperación');
            });
            return $this->imprimirError("0", "Correo enviado");
        }
    }

    /**
     * Genera una clave automática de una longitud especificada.
     *
     * @param int $i_longitud Longitud de la clave generada.
     * @return string Clave generada.
     */
    function claveAutomatica($i_longitud) {
        $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
        $cad = "";
        for ($i = 0; $i < $i_longitud; $i++) {
            $cad .= substr($str, rand(0, 62), 1);
        }
        return $cad;
    }

    /**
     * Imprime un error en formato JSON.
     *
     * @param string $error Código de error.
     * @param string $mensaje Mensaje de error.
     * @param mixed|null $i_datos Datos adicionales (opcional).
     * @return array Respuesta con el error y el mensaje.
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
