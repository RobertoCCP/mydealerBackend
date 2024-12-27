<?php

namespace App\Models\Empresa;

use App\Models\Roles\Perfil;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class UsuarioAdmin extends Authenticatable implements JWTSubject
{
    use HasFactory;
    protected $table = 'usuarioadmin';
    protected $primaryKey = 'loginusuario';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $fillable = [
        'loginusuario',
        'password',
        'nombre',
        'apellido',
        'email',
        'cargo',
        'codperfil',
        'estado',
        'coddivision'
    ];

    public function perfil()
    {
        return $this->belongsTo(Perfil::class, 'codperfil', 'srperfil');
    }

    public function division()
    {
        return $this->belongsTo(Division::class, 'coddivision', 'coddivision');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
