<?php

namespace App\Models\Clientes;

use Illuminate\Database\Eloquent\Model;
use Awobaz\Compoships\Compoships;

class DireccionEnvio extends Model
{
    use Compoships;
    protected $table = 'direccionenvio';
    protected $primaryKey = ['coddireccionenvio', 'codcliente'];
    public $incrementing = false;

    protected $fillable = [
        'coddireccionenvio',
        'codcliente',
        'nombre',
        'pais',
        'provincia',
        'ciudad',
        'direccion',
        'orden',
        'latitud',
        'longitud'
    ];

    public function rutaDetalles()
    {
        return $this->hasMany(RutaDet::class, 'coddireccionenvio', 'coddireccionenvio');
    }

    public function gps()
    {
        return $this->hasOne(DireccionEnvioGPS::class, ['coddireccionenvio', 'codcliente'], ['coddireccionenvio', 'codcliente']);
    }
}
