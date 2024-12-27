<?php

namespace App\Models\Empresa;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Division extends Model
{
    protected $table = 'division';
    protected $primaryKey = 'coddivision';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'coddivision',
        'nombre',
        'vedisponibilidad',
        'codsector',
        'imagen',
        'mensajebienvenida',
        'mensaje',
        'gerente',
        'email',
        'foto'
    ];

    public function usuariosadmin()
    {
        return $this->hasMany(UsuarioAdmin::class, 'coddivision', 'coddivision');
    }
}