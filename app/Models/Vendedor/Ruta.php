<?php

namespace App\Models\Vendedor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Clientes\RutaDet;

class Ruta extends Model
{
    use HasFactory;
    protected $table = 'rutas';
    protected $primaryKey = 'codruta';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'codruta',
        'nombre',
        'codvendedor'
    ];

    public function vendedor()
    {
        return $this->hasOne(Vendedor::class, 'codruta', 'codruta');
    }

    public function detalles()
    {
        return $this->hasMany(RutaDet::class, 'codruta', 'codruta');
    }
}
