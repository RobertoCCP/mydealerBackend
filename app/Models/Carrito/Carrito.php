<?php

namespace App\Models\Carrito;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
    use HasFactory;
    protected $table = 'carritos';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'codproducto',
        'idinvitado',
        'cantidad',
        'comprar_ahora',
        'codcliente',
        'es_invitado',
        'idgrupocarrito',
        'precio',
        'descuento',
        'impuesto',
        'porcdescuento',
        'porcimpuesto',
        'is_checked',
        'slug',
        'nombre',
        'imagen',
        'nombregrupo',
    ];

    public function invitado()
    {
        return $this->belongsTo(Invitado::class, 'idinvitado', 'idinvitado');
    }

}
