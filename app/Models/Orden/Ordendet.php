<?php

namespace App\Models\Orden;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ordendet extends Model
{
    use HasFactory;

    protected $table = 'ordendet';
    protected $primaryKey = 'numlinea';
    public $incrementing = true;
    protected $keyType = 'bigint';
    public $timestamps = false;

     // Especifica los casts
     protected $casts = [
        'numlinea' => 'integer',
        'srorden' => 'integer',
        'cantidad' => 'float',
        'precio' => 'float',
        'descuento' => 'float',
        'subtotal' => 'float',
        'orden' => 'integer',
        'descuentoval' => 'float',
        'impuesto' => 'float',
        'total' => 'float',
        'descadicional' => 'float',
        'porcdescuentoadic' => 'float',
        'porcdescuentotal' => 'float',
        'porcimpuesto' => 'float',
    ];

    protected $fillable = [
        'numlinea',
        'idorden',
        'srorden',
        'codproducto',
        'cantidad',
        'precio',
        'descuento',
        'subtotal',
        'orden',
        'descuentoval',
        'impuesto',
        'total',
        'codunidadmedida',
        'descadicional',
        'porcdescuentoadic',
        'porcdescuentotal',
        'porcimpuesto'
    ];

    public function orden()
    {
        return $this->belongsTo(Orden::class, 'srorden', 'srorden');
    }
}
