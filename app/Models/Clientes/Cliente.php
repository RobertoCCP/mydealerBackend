<?php

namespace App\Models\Clientes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'cliente';
    protected $primaryKey = 'codcliente';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $fillable = [
        'codcliente',
        'codtipocliente',
        'nombre',
        'email',
        'pais',
        'provincia',
        'ciudad',
        'codvendedor',
        'codformapago',
        'estado',
        'limitecredito',
        'saldopendiente',
        'cedularuc',
        'codlistaprecio',
        'calificacion',
        'nombrecomercial',
    ];

    public function direccionesEnvio()
    {
        return $this->hasMany(DireccionEnvio::class, 'codcliente', 'codcliente');
    }
}