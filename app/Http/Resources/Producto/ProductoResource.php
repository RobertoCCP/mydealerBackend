<?php

namespace App\Http\Resources\Producto;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'codproducto' => $this->codproducto,
            // 'nombre' => $this->nombre,
            // 'costo' => $this->costo,
            // 'porcimpuesto' => $this->porcimpuesto,
            // 'cantidad_no_disponible' => $this->cantidad_no_disponible ?? 0,
            // 'descuento' => $this->descuento ?? 0
        ];
    }
}
