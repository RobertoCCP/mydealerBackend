<?php

namespace App\Http\Controllers;

use App\Models\Clientes\Cliente;
use Illuminate\Http\Request;

class DireccionEnvioController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/clientes/{codcliente}/direcciones-envio",
     * tags={"Cliente - Dirección de envío"},
     * summary="Busca las direcciones asociadas de un cliente",
     * description="Devuelve un listado con todas las direcciones disponibles",
     *
     * @OA\Parameter(
     *      description="Necesita el codcliente",
     *      in="path",
     *      name="codcliente",
     *      required=true,
     * ),
     *
     * @OA\Response(
     *         response=200,
     *         description="successful operation",
     * ),
     * @OA\Response(
     *     response=404,
     *     description="Dato no encontrado"
     * ),
     * @OA\Response(
     *     response=500,
     *     description="Database error"
     * ),
     * )
     */
    public function obtenerDirecciones($codcliente)
    {
        // Encuentra el cliente por su código
        $cliente = Cliente::find($codcliente);

        // Verifica si el cliente existe
        if (!$cliente) {
            return response()->json(['error' => 'Cliente no encontrado'], 404);
        }

        // Obtiene las direcciones de envío asociadas al cliente
        $direcciones = $cliente->direccionesEnvio()->get(['coddireccionenvio','pais', 'provincia', 'ciudad', 'direccion', 'latitud', 'longitud']);

        return response()->json($direcciones);
    }
}
