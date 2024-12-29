<?php

namespace App\Models\Cliente;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;

class Cliente extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $table = 'cliente';
    protected $primaryKey = 'codcliente';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
'codcliente', 'codtipocliente', 'nombre', 'email', 'pais', 'provincia', 'ciudad',
        'codvendedor', 'codformapago', 'estado', 'limitecredito', 'saldopendiente',
        'cedularuc', 'codlistaprecio', 'calificacion', 'nombrecomercial', 'login', 'password'
    ];

    public $timestamps = false;

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
