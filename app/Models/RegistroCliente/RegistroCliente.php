<?php

namespace App\Models\RegistroCliente;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroCliente extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'cliente';
    protected $primaryKey = 'codcliente';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'codcliente', 'codtipocliente', 'nombre', 'email', 'password', 'pais',
        'provincia', 'ciudad', 'codvendedor', 'codformapago', 'estado',
        'limitecredito', 'saldopendiente', 'cedularuc', 'codlistaprecio',
        'calificacion', 'nombrecomercial', 'login'
    ];

    public function tipoCliente()
    {
        return $this->belongsTo(TipoCliente::class, 'codtipocliente');
    }

    public function vendedor()
    {
        return $this->belongsTo(Vendedor::class, 'codvendedor');
    }

    public function formaPago()
    {
        return $this->belongsTo(FormaPago::class, 'codformapago');
    }
}
