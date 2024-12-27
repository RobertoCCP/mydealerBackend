<?php

namespace App\Models\Productos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupoMaterial extends Model
{
    use HasFactory;

    protected $table = 'grupomaterial';
    protected $primaryKey = 'codgrupomaterial';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
    ];

    public function tipoProductos()
    {
        return $this->hasMany(TipoProducto::class, 'codgrupomaterial', 'codgrupomaterial');
    }
}
