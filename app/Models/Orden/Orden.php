<?php

namespace App\Models\Orden;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orden extends Model
{
    use HasFactory;

    protected $table = 'orden';
    protected $primaryKey = 'srorden';
    public $incrementing = true; // Esto puede ser opcional si la clave primaria es auto-incremental
    protected $keyType = 'bigint';
    public $timestamps = false;

    protected $casts = [
        'srorden' => 'integer',  // O 'string' dependiendo del tipo de datos en la base de datos
        'fecha' => 'date',
        'fechaweb' => 'datetime',
        'fechamovil' => 'datetime',
        'fechaenvioerp' => 'datetime'
    ];

    protected $fillable = [
        'srorden',
        'codcliente',
        'fecha',
        'fechaweb',
        'codformaenvio',
        'codformapago',
        'idorden',
        'codvendedor',
        'coddireccionenvio',
        'numordenerp',
        'observaciones',
        'loginusuario',
        'referencia1',
        'referencia2',
        'subtotal',
        'descuento',
        'impuesto',
        'total',
        'estado',
        'origen',
        'fechamovil',
        'fechaenvioerp',
        'estadoAprCred',
        'estadoAprCom'
    ];

    public function detalles()
    {
        return $this->hasMany(Ordendet::class, 'srorden', 'srorden');
    }

    public function logs()
    {
        return $this->hasMany(Ordeneslog::class, 'srorden', 'srorden', 'estado_AprCom','estado_AprCred');
    }
    
}
