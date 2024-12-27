<?php

namespace App\Http\Resources\Roles;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PerfilUsuarioResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'Codigo' => $this->srperfil,
            'Perfil' => $this->perfil,
            'Nivel' => $this->nivel,
            'TipoPerfil' => $this->tipoperfil,
        ];
    }
}
