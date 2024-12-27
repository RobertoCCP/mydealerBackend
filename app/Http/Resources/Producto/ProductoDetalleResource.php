<?php

namespace App\Http\Resources\Producto;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductoDetalleResource extends JsonResource
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
            'nombre' => $this->nombre,
            'slug' => $this->slug,
            'tipoproducto' => $this->tipo->descripcion,
            'categoria' => $this->categoria,
            'marca' => $this->marca?->codmarca,
            'unidadmedida' => $this->unidadmedida,
            'imagenes' => [$this->imagen],
            'imagen'=>$this->imagen,
            'precio' => $this->costo,
            'preciocompra' => $this->costo, 
            'porcimpuesto' =>  $this->porcimpuesto,
            'porcdescuento' => $this->porcdescuento,
            'total_actual_stock' => $this->stocks->sum('stock'),
            'umv' => $this->umv,
            'detalles' => "", 
        ];
    }
}
