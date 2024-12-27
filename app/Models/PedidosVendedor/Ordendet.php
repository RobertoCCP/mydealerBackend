<?php

namespace App\Models\PedidosVendedor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ordendet extends Model
{
    use HasFactory;

    protected $table = 'ordendet';
    protected $primaryKey = 'numlinea';
    public $timestamps = true;

    protected $fillable = [
        'idorden', 'srorden', 'codproducto', 'cantidad', 'precio', 'descuento', 'subtotal', 'orden',
        'descuentoval', 'impuesto', 'total', 'codunidadmedida', 'descadicional', 'porcdescuentoadic',
        'porcdescuentotal', 'porcimpuesto'
    ];
}
