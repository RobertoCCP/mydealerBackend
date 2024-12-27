<?php

namespace App\Http\Resources\Roles;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PerfilResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'Código' => $this->srperfil,
            'Perfil' => $this->perfil,
            'Nivel' => $this->nivel,
            'Tipo Perfil' => $this->tipoPerfil->nombre,
        ];
    }
}
