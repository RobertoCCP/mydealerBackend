<?php

namespace App\Http\Resources\Reportes;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReporteGPSEstadoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'idrptgps' => $this->idrptgps,
            'codvendedor' => $this->codvendedor,
            'fechamovil' => $this->fechamovil,
            'observacion' => $this->observacion,
            'version' => $this->version,
            'gestion' => $this->gestion,
            'mac' => $this->mac,
            'bateria' => $this->bateria,
        ];
    }
}
