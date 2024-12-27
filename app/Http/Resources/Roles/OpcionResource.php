<?php

namespace App\Http\Resources\Roles;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OpcionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $menuPrincipalNombre = $this->menuCabecera ? $this->menuCabecera->nombre : null;

        return [
            'Seleccion' => $this->seleccion,
            'Código' => $this->sropcion,
            'Descripción' => $this->nombre,
            'Menú Principal' => $menuPrincipalNombre
        ];
    }
}
