<?php

namespace App\Http\Controllers;

use App\Models\TipoCliente;
use Illuminate\Http\Request;

class TipoClienteController extends Controller
{
    public function index()
    {
        // Obtener todos los tipos de clientes
        $tiposClientes = TipoCliente::all();
        return response()->json($tiposClientes);
    }
}
