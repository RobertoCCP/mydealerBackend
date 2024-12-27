<?php

namespace App\Models;

use App\Models\Marca\Marca;
use App\Models\Productos\TipoProducto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Producto extends Model
{
    protected $table = 'producto';
    protected $primaryKey = 'codproducto';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'codproducto',
        'nombre',
        'umv',
        'estado',
        'codtipoproducto',
        'costo',
        'unidadmedida',
        'codmarca',
        'porcimpuesto',
       // 'porcdescuento',
        'imagen',
        'categoria',
    ];
    protected $appends = ['porcdescuento'];

    public function stocks()
    {
        return $this->hasMany(BodegaStock::class, 'codproducto');
    }
    public function bodegastock()
    {
        return $this->hasMany(Bodegastock::class, 'codproducto', 'codproducto');
    }
    public function getSlugAttribute()
    {
        return Str::slug($this->nombre);
    }

    public function tipo()
    {
        return $this->belongsTo(TipoProducto::class, 'codtipoproducto');
    }

    public function marca()
    {
        return $this->belongsTo(Marca::class, 'codmarca');
    }

    public function descuento()
    {
        return $this->hasOne(Descuento::class, 'codproducto', 'codproducto');
    }
    public function getPorcdescuentoAttribute()
    {
        $descuento = $this->descuento ? $this->descuento->descuento : 0.0;
        return number_format((float)$descuento, 2, '.', '');
    }
 
}
