<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistroCliente\RegistroCliente;
use Illuminate\Support\Facades\Validator;

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

        // Crear el cliente
        $cliente = RegistroCliente::create([
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
            'nombrecomercial' => $data['nombrecomercial'],
            'login' => $data['login'],
            'password' => $data['password'],  // Agregar la contraseña
        ]);
    
        return response()->json(['message' => 'Cliente registrado exitosamente', 'cliente' => $cliente], 201);
    }

    public function show($codcliente)
    {
        $cliente = RegistroCliente::where('codcliente', $codcliente)->first();

        if (!$cliente) {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }

        return response()->json(['cliente' => $cliente], 200);
    }
}
