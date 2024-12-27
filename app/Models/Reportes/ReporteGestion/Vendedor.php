<?php

namespace App\Models\Reportes\ReporteGestion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendedor extends Model
{
    use HasFactory;

    protected $table = 'vendedor';
    protected $primaryKey = 'codvendedor';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'codvendedor',
        'nombre',
        'email',
        'login',
        'password',
        'estado',
        'mac_pend_asignacion',
        'Fecha_requerimiento',
        'Estado_Autorizacion',
        'Fecha_Autorizacion',
        'codruta',
    ];

    public function rutasGestion()
    {
        return $this->hasMany(RutaGestion::class, 'codvendedor', 'codvendedor');
    }
}
