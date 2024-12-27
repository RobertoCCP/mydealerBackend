<?php

namespace App\Http\Resources\Reportes;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReporteGestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'codrutagestion' => $this->codrutagestion,
            'codvendedor' => $this->codvendedor,
            'fecha' => $this->fecha,
            'codruta' => $this->codruta,
            'codcliente' => $this->codcliente,
            'horaini' => $this->horaini,
            'horafin' => $this->horafin,
            'codestado' => $this->codestado,
            'coddireccionenvio' => $this->coddireccionenvio,
            'fechaproximavisita' => $this->fechaproximavisita,
            'observacion' => $this->observacion,
            'codzona' => $this->codzona,
            'codsupervisor' => $this->codsupervisor,
        ];
    }
}

