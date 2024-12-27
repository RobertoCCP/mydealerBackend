<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bodega extends Model
{
 
    protected $table = 'bodegas';
    protected $primaryKey = 'codbodega';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'codbodega',
        'descripcion',
        'estado'
    ];

    // Relación con BodegaStock
    public function bodegastock()
    {
        return $this->hasMany(BodegaStock::class, 'codbodega', 'codbodega');
    }
}
