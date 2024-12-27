<?php

namespace App\Http\Controllers\Clientes;


use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Models\Clientes\Cliente;
use App\Http\Controllers\Controller;

class ClienteController extends Controller {
  use HttpResponses;

  /**
   *
   * @OA\Get(
   * path="/api/cliente",
   * tags={"Web - Vista Cliente"},
   * summary="Buscar info del cliente",
   * description="Devuelve una lista con los datos del cliente",
   * @OA\Parameter(
   *      description="Necesita el código del cliente",
   *      in="query",
   *      name="codcliente",
   *      required=false,
   * ),
   * @OA\Parameter(
   *      description="Necesita nombre del cliente",
   *      in="query",
   *      name="nombre",
   *      required=false,
   * ),
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
  public function index(Request $resquest) {
    try {
      $limit = 3000;
      $nombre = $resquest->nombre;
      $codcliente = $resquest->codcliente;

      if (!$nombre) $nombre = '';

      if (!$codcliente) $codcliente = '';

      $nombre = strtoupper($nombre);
      $codcliente = strtoupper($codcliente);

      $clientes = Cliente::where('nombre', 'like', "%$nombre%")
        ->where('codcliente', 'like', "%$codcliente%")
        ->limit($limit)
        ->get();

      return $this->success($clientes, 'Clientes obtenidos correctamente.', 200);
    } catch (\Exception $e) {
      return $this->error('Error al obtener los clientes.', $e->getMessage(), 500);
    }
  }
}
