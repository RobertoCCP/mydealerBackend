<?php

namespace App\Http\Controllers\PedidosVendedor;

use App\Http\Controllers\Controller;
use App\Models\PedidosVendedor\Ordendet;
use Illuminate\Http\Request;

class OrdendetController extends Controller
{
    
    public function index()
    {
        $ordendets = Ordendet::all();
        return view('ordendets.index', compact('ordendets'));
    }

    
    public function create()
    {
        return view('ordendets.create');
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'orden_id' => 'required|integer',
            'producto_id' => 'required|integer',
            'cantidad' => 'required|integer',
        ]);

        Ordendet::create($request->all());
        return redirect()->route('ordendets.index')->with('success', 'Detalle de orden creado exitosamente.');
    }

    
    public function show(Ordendet $ordendet)
    {
        return view('ordendets.show', compact('ordendet'));
    }

    
    public function edit(Ordendet $ordendet)
    {
        return view('ordendets.edit', compact('ordendet'));
    }

    
    public function update(Request $request, Ordendet $ordendet)
    {
        $request->validate([
            'orden_id' => 'required|integer',
            'producto_id' => 'required|integer',
            'cantidad' => 'required|integer',
        ]);

        $ordendet->update($request->all());
        return redirect()->route('ordendets.index')->with('success', 'Detalle de orden actualizado exitosamente.');
    }


    public function destroy(Ordendet $ordendet)
    {
        $ordendet->delete();
        return redirect()->route('ordendets.index')->with('success', 'Detalle de orden eliminado exitosamente.');
    }
}
