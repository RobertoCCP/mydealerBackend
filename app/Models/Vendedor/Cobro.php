<?php

namespace App\Models\Vendedor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cobro extends Model
{
    use HasFactory;
    protected $table = 'cxccobro';
    protected $primaryKey = 'codrecibo';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'codcliente',
        'codvendedor',
        'totalaplicado',
        'valornoaplicado',
        'fecha',
        'estado',
        'fechaenvio',
        'numeroenvio',
        'numingresosap',
        'loginusuario',
        'observaciones',
        'tipo',
        'nummovil',
        'errorsap',
        'total',
        'numrecibofisico',
        'fechaweb',
        'codsupervisor',
        'estadomail',
        'recibido_doc',
        'fecha_doc',
        'dias_doc',
        'estado_auto',
        'fecha_auto',
        'usuario_auto',
        'observ_auto',
        'numdeposito',
        'fechadeposito',
        'xmlcab',
        'xmlfpag',
        'xmldocs',
    ];

    protected $dates = [
        'fecha',
        'fechaenvio',
        'fechaweb',
        'fecha_doc',
        'fecha_auto',
        'fechadeposito',
    ];

}