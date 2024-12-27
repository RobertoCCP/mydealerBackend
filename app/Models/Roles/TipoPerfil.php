<?php

namespace App\Models\Roles;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoPerfil extends Model
{
    use HasFactory;
    protected $table = 'tipoperfil';
    protected $primaryKey = 'codtipoperfil';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [
        'nombre',
        'descripcion'
    ];
    public function perfiles()
    {
        return $this->hasMany(Perfil::class, 'tipoperfil', 'codtipoperfil');
    }
}