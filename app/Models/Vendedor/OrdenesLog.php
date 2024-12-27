<?php

namespace App\Models\Vendedor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenesLog extends Model
{
    use HasFactory;

    protected $table = 'ordeneslog';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'srorden',
        'fecha',
        'estado',
        'mailenviado',
        'estado_AprCom',
        'fecha_AprCom',
        'autor_AprCom',
        'obser_AprCom',
        'estado_AprCred',
        'fecha_AprCred',
        'autor_AprCred',
        'obser_AprCred',
        'codmotrechazo_com',
        'codmotrechazo_cre',
    ];

    public function orden()
    {
        return $this->belongsTo(Orden::class, 'srorden', 'srorden');
    }
}