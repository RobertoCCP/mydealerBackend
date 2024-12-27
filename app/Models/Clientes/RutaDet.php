<?php

namespace App\Models\Clientes;

use Illuminate\Database\Eloquent\Model;
use Awobaz\Compoships\Compoships;
use App\Models\Vendedor\Vendedor;
use App\Models\Vendedor\Ruta;

class RutaDet extends Model
{
    use Compoships;
    protected $table = 'rutadet';
    protected $primaryKey = 'codrutadet';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'codruta',
        'codcliente',
        'orden',
        'coddireccionenvio',
        'semana',
        'diasemana',
        'codvendedor'
    ];

    public function vendedor()
    {
        return $this->hasOne(Vendedor::class, 'codruta', 'codruta');
    }

    public function ruta()
    {
        return $this->belongsTo(Ruta::class, 'codruta', 'codruta');
    }

    public function direccionEnvio()
    {
        return $this->belongsTo(DireccionEnvio::class, ['coddireccionenvio', 'codcliente'], ['coddireccionenvio', 'codcliente']);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'codcliente', 'codcliente');
    }
}
