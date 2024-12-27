<?php
 
namespace App\Http\Resources\Configuracion;
 
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
 
class TipoNovedadResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'Codigo' => $this->codtiponovedad,            
            'Tipo_novedad' => $this->tiponovedad,
            'Categoria' => $this->categoria,
            'Orden' => $this->orden,
            'Control' => $this->control,
        ];
    }
}