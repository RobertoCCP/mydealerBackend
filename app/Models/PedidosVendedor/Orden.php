<?php

namespace App\Models\PedidosVendedor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orden extends Model {
    use HasFactory;

    protected $table = 'orden';
    protected $primaryKey = 'srorden';
    public $timestamps = true;

    protected $fillable = [
        'codcliente', 'fecha', 'fechaweb', 'codformaenvio', 'codformapago', 'idorden', 'codvendedor',
        'coddireccionenvio', 'numordenerp', 'observaciones', 'loginusuario', 'referencia1', 'referencia2',
        'subtotal', 'descuento', 'impuesto', 'total', 'estado', 'origen', 'errorws', 'fechamovil', 'fechaenvioerp',
        'pedido_renegociado', 'codsucursal'
    ];
    public function detalles() {
        return $this->hasMany(Ordendet::class, 'srorden', 'srorden');
    }
}
