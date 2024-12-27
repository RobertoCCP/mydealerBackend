<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Empresa extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $table = 'empresa';

    protected $primaryKey = 'codempresa';

    public $timestamps = false;

    protected $fillable = [
        'codempresa',
        'nombre',
        'direccion',
        'telefono',
        'pais',
        'provincia',
        'ciudad',
        'nombretienda',
        'emailsoporte',
        'emailorden',
        'usuarioadmin',
        'claveadmin',
        'mensaje',
        'cargo1',
        'nombre1',
        'email1',
        'foto1',
        'cargo2',
        'nombre2',
        'email2',
        'foto2',
        'cargo3',
        'nombre3',
        'email3',
        'foto3',
        'cargo4',
        'nombre4',
        'email4',
        'foto4',
        'fax',
        'logo',
        'logo_cabecera',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
