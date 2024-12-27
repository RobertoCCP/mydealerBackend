<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Descuento extends Model
{
    use HasFactory;
    protected $table = 'descuentos';
    protected $primaryKey = ['codproducto', 'descuento','codmarca'];
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'codproducto', 'descuento', 'codmarca','codtipoproducto',
        'codcliente','codtipocliente','codformapago','acumulable',
        'nivel','fechainicio','fechafin','codlistaprecios'
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'codproducto');
    }
}

