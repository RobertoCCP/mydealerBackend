<?php

namespace App\Models\Roles;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpcionPerfil extends Model
{
    use HasFactory;
    protected $table = 'opcionperfil';
    protected $primaryKey = 'sropcionperfil';
    public $timestamps = false;
    protected $fillable = [
        'sropcion',
        'srperfil',
    ];
    public function opcion()
    {
        return $this->belongsTo(Opcion::class, 'sropcion', 'sropcion');
    }

    public function perfil()
    {
        return $this->belongsTo(Perfil::class, 'srperfil', 'srperfil');
    }
}
