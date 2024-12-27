<?php

namespace App\Http\Resources\Reportes;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RutaGestionDetalladaResource extends JsonResource
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
            'nombre_vendedor' => $this->vendedor->nombre,  
            'codcliente' => $this->codcliente,
            'nombre_cliente' => $this->cliente->nombre,   
            'fecha_hora' => $this->fecha_hora,
            'codnovedad' => $this->codnovedad,
            'novedad' => $this->novedad,
            'clientes' => $this->clientes,
            'visitas' => $this->visitas,
            'pedidos' => $this->pedidos,
        ];
    }
}
