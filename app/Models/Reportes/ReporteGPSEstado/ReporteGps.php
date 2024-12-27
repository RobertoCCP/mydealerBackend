<?php

namespace App\Models\Reportes\ReporteGPSEstado;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReporteGps extends Model
{
    use HasFactory;

    protected $table = 'reportegps';
    protected $primaryKey = 'idrptgps';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'idrptgps',
        'codvendedor',
        'fechamovil',
        'observacion',
        'version',
        'gestion',
        'mac',
        'bateria',
    ];

    public function vendedor()
    {
        return $this->belongsTo(Vendedor::class, 'codvendedor', 'codvendedor');
    }
}

