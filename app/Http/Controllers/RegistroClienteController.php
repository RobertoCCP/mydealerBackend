<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistroCliente\RegistroCliente;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class RegistroClienteController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'codcliente' => 'required|string|max:50|unique:cliente,codcliente',
            'codtipocliente' => 'nullable|string|max:10|exists:tipocliente,codtipocliente',
            'nombre' => 'nullable|string|max:300',
            'email' => 'nullable|string|max:250|email',
            'pais' => 'nullable|string|max:60',
            'provincia' => 'nullable|string|max:60',
            'ciudad' => 'nullable|string|max:60',
            'codvendedor' => 'nullable|string|max:10|exists:vendedor,codvendedor',
            'codformapago' => 'nullable|string|max:10|exists:formapago,codformapago',
            'estado' => 'nullable|string|in:A,I',
            'limitecredito' => 'nullable|numeric',
            'saldopendiente' => 'nullable|numeric',
            'cedularuc' => 'required|string|max:20',
            'codlistaprecio' => 'nullable|string|max:10',
            'calificacion' => 'nullable|string|max:30',
            'nombrecomercial' => 'nullable|string|max:100',
            'login' => 'nullable|string|max:15|unique:cliente,login',
            'password' => 'nullable|string|max:15',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        // Preparar los datos para crear el cliente
        $data = $request->all();        
        $w_cliente = [
            'codcliente' => $data['codcliente'],
            'codtipocliente' => $data['codtipocliente'],
            'nombre' => $data['nombre'],
            'email' => $data['email'],
            'pais' => $data['pais'],
            'provincia' => $data['provincia'],
            'ciudad' => $data['ciudad'],
            'codvendedor' => $data['codvendedor'],
            'codformapago' => $data['codformapago'],
            'estado' => $data['estado'],
            'limitecredito' => $data['limitecredito'],
            'saldopendiente' => $data['saldopendiente'],
            'cedularuc' => $data['cedularuc'],
            'codlistaprecio' => $data['codlistaprecio'],
            'calificacion' => $data['calificacion'],
            'nombrecomercial' => $data['nombrecomercial'], // Mantener el valor del nombre comercial
            'login' => $data['login'],
            'password' => $data['password'],
        ];
        
        // Crear el cliente
        $cliente = RegistroCliente::create($w_cliente);

        // Enviar correo de verificación
        $w_enviarCorreo = $this->envioCorreo($data['email'], 'Código de verificación', "Su código de verificación es: " . $this->generarCodigoTemporal($w_cliente['codcliente']));

        return response()->json(['message' => 'Cliente registrado exitosamente', 'cliente' => $cliente], 201);
    }

    // Función para generar el código temporal
    function generarCodigoTemporal($clave, $duracionEnMinutos = 60)
    {
        $codigo = Str::random(6);
        Cache::put($clave, $codigo, now()->addMinutes($duracionEnMinutos));
        return $codigo;
    }

    // Función para enviar el correo
    function envioCorreo($destinatario, $titulo = 'Mensaje de prueba Mydealer', $mensaje = 'Mensaje enviado desde Mydealer') {
        if ($destinatario == null) {
            return false; 
        }
        try {
            Mail::raw($mensaje, function ($message) use ($destinatario, $titulo) {
                $message->to($destinatario)->subject($titulo);
            });
            
            return true; 
        } catch (\Exception $e) {
            return false;
        }
    }

    // Función para verificar el código temporal
    function verificarCodigoTemporal($clave, $codigoIngresado)
    {
        $codigoGuardado = Cache::get($clave);
        if (!$codigoGuardado) {
            return jsonResponse('9998', 'El código ingresado no se encuentra');
        }
        if($codigoIngresado == $codigoGuardado)
            return jsonResponse('0', 'Código verificado');
        else
            return jsonResponse('9998', 'El código ingresado no coincide');
    }

    // Mostrar información de un cliente
    public function show($codcliente)
    {
        $cliente = RegistroCliente::where('codcliente', $codcliente)->first();
    
        if (!$cliente) {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }
    
        return response()->json([
            'codigo' => '0',
            'mensaje' => 'Cliente obtenido correctamente',
            'data' => [$cliente]
        ]);
    }
}
