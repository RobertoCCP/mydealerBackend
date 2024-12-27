<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BodegaStock extends Model
{
   
    protected $table = 'bodegastock';
    protected $primaryKey = ['codproducto', 'codbodega'];
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'codproducto', 'codbodega', 'stock'
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'codproducto');
    }

    public function bodega()
    {
        return $this->belongsTo(Bodega::class, 'codbodega', 'codbodega');
    }
}
