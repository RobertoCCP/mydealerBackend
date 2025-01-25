<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Cliente\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    /**
     * Enviar el enlace de restablecimiento de contraseña.
     */
    public function sendResetLinkEmail(Request $request)
    {
        // Validar el correo
        $request->validate(['email' => 'required|email']);

        // Verificar si el correo existe en la base de datos
        $cliente = Cliente::where('email', $request->input('email'))->first();

        if (!$cliente) {
            return response()->json(['error' => 'Correo no registrado.'], 400);
        }

        // Generar un token de restablecimiento
        $token = bin2hex(random_bytes(32)); // Crear un token único

        // Guardar el token en la tabla password_resets
        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->input('email')],
            ['token' => $token, 'created_at' => now()]
        );

        // Crear el enlace de restablecimiento
        $resetUrl = url('password/reset/'.$token.'/'.$request->input('email'));

        // Reemplazar la URL con 127.0.0.1 en vez de 10.0.2.2 para que sea accesible desde el emulador o dispositivos físicos
        $resetUrl = str_replace('10.0.2.2', '127.0.0.1', $resetUrl);

        // Enviar correo con el enlace
        Mail::raw('Enlace de restablecimiento: '.$resetUrl, function ($message) use ($request) {
            $message->to($request->input('email'))
                    ->subject('Restablecer Contraseña');
        });

        return response()->json(['message' => 'Enlace de restablecimiento enviado correctamente.']);
    }

    /**
     * Mostrar el formulario de restablecimiento.
     */
    public function showResetForm($token, $email)
    {
        return view('auth.passwords.reset', compact('token', 'email'));
    }

    /**
     * Restablecer la contraseña del usuario.
     */
    public function reset(Request $request)
    {
        // Validar los datos
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed', // Eliminar la restricción de longitud mínima
            'token' => 'required'
        ]);

        // Verificar si el token existe
        $passwordReset = DB::table('password_resets')->where('email', $request->email)->where('token', $request->token)->first();

        if (!$passwordReset) {
            return redirect()->back()->withErrors(['email' => 'Token o correo inválido.']);
        }

        // Buscar al cliente
        $cliente = Cliente::where('email', $request->email)->first();

        if (!$cliente) {
            return redirect()->back()->withErrors(['email' => 'No se encontró una cuenta con ese correo.']);
        }

        // Actualizar la contraseña sin hacerle hash
        $cliente->password = $request->password; // No usar Hash::make()
        $cliente->save();

        // Eliminar el token
        DB::table('password_resets')->where('email', $request->email)->delete();

        // Redirigir a la página de éxito
        return redirect()->route('password.success');
    }
}
