<?php

namespace App\Models\Reportes\ReporteCorreos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorreoLog extends Model
{
    use HasFactory;

    protected $table = 'correolog';
    protected $primaryKey = 'idcorreo';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'mailprincipal',
        'asunto',
        'cuerpo',
        'cabeceras',
        'estado',
        'fechacreacion',
        'fechaenvio',
        'usuario',
        'tipo',
        'srorden',
        'msgerror',
        'numintentos',
    ];
}
