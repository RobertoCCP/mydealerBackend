<?php

namespace App\Http\Resources\Empresa;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UsuarioAdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'loginusuario' => $this->loginusuario,
            'password' => $this->password,
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'email' => $this->email,
            'cargo' => $this->cargo,
            'codperfil' => $this->perfil->perfil,
            'estado' => $this->estado,
            'coddivision' => $this->division->nombre
        ];
    }
}
