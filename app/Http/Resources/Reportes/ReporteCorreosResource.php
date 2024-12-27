<?php

namespace App\Http\Resources\Reportes;

use Illuminate\Http\Resources\Json\JsonResource;

class ReporteCorreosResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'idcorreo' => $this->idcorreo,
            'mailprincipal' => $this->mailprincipal,
            'asunto' => $this->asunto,
            'cuerpo' => $this->cuerpo,
            'estado' => $this->estado,
            'fechacreacion' => $this->fechacreacion,
            'fechaenvio' => $this->fechaenvio,
            'usuario' => $this->usuario,
            'tipo' => $this->tipo,
            'srorden' => $this->srorden,
            'msgerror' => $this->msgerror,
            'numintentos' => $this->numintentos,
            'mostrar_boton_enviar' => $this->estado === 'N', // Agrega este campo para mostrar el botón
        ];
    }
}
