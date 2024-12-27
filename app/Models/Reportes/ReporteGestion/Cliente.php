<?php

namespace App\Models\Reportes\ReporteGestion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

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
        'password',
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
        'login',
    ];

    public function rutasGestion()
    {
        return $this->hasMany(RutaGestion::class, 'codcliente', 'codcliente');
    }

    public function vendedor()
    {
        return $this->belongsTo(Vendedor::class, 'codvendedor', 'codvendedor');
    }
}
