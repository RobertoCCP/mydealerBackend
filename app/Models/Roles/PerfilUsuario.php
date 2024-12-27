<?php

namespace App\Models\Roles;

use Illuminate\Database\Eloquent\Model;

class PerfilUsuario extends Model
{
    protected $table = 'perfil';
    
    protected $primaryKey = 'srperfil';
    public $incrementing = true;
    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'perfil',
        'nivel',
        'tipoperfil',
    ];
}
