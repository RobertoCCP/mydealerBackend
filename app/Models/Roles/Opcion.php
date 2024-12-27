<?php

namespace App\Models\Roles;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Opcion extends Model
{
    use HasFactory;
    protected $table = 'opcion';
    protected $primaryKey = 'sropcion';
    public $timestamps = false;
    protected $fillable = [
        'nombre',
        'path',
        'orden',
        'tipoopcion',
        'codmenucabecera',
        'monitorear',
        'estado'
    ];
    public function menuCabecera()
    {
        return $this->belongsTo(MenuCabecera::class, 'codmenucabecera', 'codmenucabecera');
    }

    public function perfiles()
    {
        return $this->belongsToMany(Perfil::class, 'opcionperfil', 'sropcion', 'srperfil')
            ->withPivot('sropcionperfil');
    }
}