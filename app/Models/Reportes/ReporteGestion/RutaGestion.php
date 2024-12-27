<?php

namespace App\Models\Reportes\ReporteGestion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RutaGestion extends Model
{
    use HasFactory;

    protected $table = 'rutagestion';
    protected $primaryKey = 'codrutagestion';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'codrutagestion',
        'codvendedor',
        'fecha',
        'codruta',
        'codcliente',
        'horaini',
        'horafin',
        'codestado',
        'coddireccionenvio',
        'fechaproximavisita',
        'observacion',
        'codzona',
        'codsupervisor',
    ];

    public function zona()
    {
        return $this->belongsTo(Zona::class, 'codzona', 'codzona');
    }

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class, 'codsupervisor', 'codsupervisor');
    }

    public function vendedor()
    {
        return $this->belongsTo(Vendedor::class, 'codvendedor', 'codvendedor');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'codcliente', 'codcliente');
    }
}
