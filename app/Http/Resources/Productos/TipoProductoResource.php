<?php

namespace App\Http\Resources\Productos;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TipoProductoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'CodigoTipoProducto' => $this->codtipoproducto,
            'Descripcion' => $this->descripcion,
            'CodigoGrupoMaterial' => $this->codgrupomaterial,
        ];
    }
}
