<?php

namespace App\Models\Vendedor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orden extends Model
{
    use HasFactory;
    protected $table = 'orden';
    protected $primaryKey = 'srorden';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
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
        'errorws',
        'fechamovil',
        'fechaenvioerp',
    ];

    public function ordenesLog()
    {
        return $this->hasMany(OrdenesLog::class, 'srorden', 'srorden');

    }
}