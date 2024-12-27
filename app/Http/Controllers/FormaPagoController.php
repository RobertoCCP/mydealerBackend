<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormaPago;

class FormaPagoController extends Controller
{
 
    /**
     * @OA\Get(
     * path="/api/formas-pago",
     * tags={"Cliente - Formas de Pago"},
     * summary="Lista de formas de pago",
     * description="Devuelve una lista con todos las formas de pago aceptadas",
     * @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     * @OA\Response(
     *     response=500,
     *     description="Database error"
     * ),
     * )
     */
    public function index()
    {
        $formasPago = FormaPago::all();
        return response()->json($formasPago);
    }
}
