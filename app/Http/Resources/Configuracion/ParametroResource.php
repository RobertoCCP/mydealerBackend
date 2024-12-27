<?php

namespace App\Http\Resources\Configuracion;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParametroResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'Codigo' => $this->codparametro,
            'Descripcion' => $this->descripcion,
            'Valor' => $this->valor,
            'Categoria' => $this->categoria,
            'Tipo' => $this->tipo,
        ];
    }
}
