<?php

namespace App\Models\Roles;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuCabecera extends Model
{
    use HasFactory;
    protected $table = 'menucabecera';
    protected $primaryKey = 'codmenucabecera';
    public $timestamps = false;
    protected $fillable = [
        'nombre',
        'orden',
        'sitio'
    ];

    public function opciones()
    {
        return $this->hasMany(Opcion::class, 'codmenucabecera', 'codmenucabecera');
    }
}