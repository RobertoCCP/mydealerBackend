<?php

namespace App\Traits;

trait HttpResponses
{
  protected function success($data, $message = null, $code = 200)
  {
    return response()->json([
      'estado' => 'La petición se ha completado correctamente.',
      'mensaje' => $message,
      'datos' => $data
    ], $code);
  }

  protected function error($data, $message = null, $code)
  {
    return response()->json([
      'estado' => 'Ha ocurrido un error.',
      'mensaje' => $message,
      'datos' => $data
    ], $code);
  }
}
