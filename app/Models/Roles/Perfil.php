<?php

namespace App\Models\Roles;

use App\Models\Empresa\UsuarioAdmin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    use HasFactory;
    protected $table = 'perfil';
    protected $primaryKey = 'srperfil';
    public $timestamps = false;
    protected $fillable = [
        'perfil',
        'nivel',
        'tipoperfil'
    ];
    public function tipoPerfil()
    {
        return $this->belongsTo(TipoPerfil::class, 'tipoperfil', 'codtipoperfil');
    }

    public function opciones()
    {
        return $this->belongsToMany(Opcion::class, 'opcionperfil', 'srperfil', 'sropcion')
            ->withPivot('sropcionperfil');
    }

    public function usuariosAdmin()
    {
        return $this->hasMany(UsuarioAdmin::class, 'codperfil', 'srperfil');
    }
}
