<?php

namespace App\Models\OpcionesMenu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'perfil';

    protected $primaryKey = 'srperfil';

    public $incrementing = true;

    protected $fillable = [
        'perfil',
    ];
}
