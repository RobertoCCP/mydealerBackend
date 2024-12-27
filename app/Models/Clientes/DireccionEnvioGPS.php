<?php

namespace App\Models\Clientes;

use Illuminate\Database\Eloquent\Model;
use Awobaz\Compoships\Compoships;

class DireccionEnvioGPS extends Model
{
    use Compoships;

    protected $table = 'direccionenviogps';
    public $incrementing = false;
    protected $primaryKey = ['coddireccionenvio', 'codcliente'];

    protected $fillable = [
        'coddireccionenvio',
        'codcliente',
        'latitud',
        'longitud',
        'codvendedor',
        'fecha'
    ];

    public function direccionEnvio()
    {
        return $this->belongsTo(DireccionEnvio::class, ['coddireccionenvio', 'codcliente'], ['coddireccionenvio', 'codcliente']);
    }
}
