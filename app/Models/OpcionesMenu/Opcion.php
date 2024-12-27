<?php

namespace App\Models\OpcionesMenu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Opcion extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'sropcion';
    protected $fillable = ['nombre', 'path', 'orden', 'codmenucabecera', 'perfil', 'estado'];
    protected $table = 'opcion';
    public function menucabecera()
    {
        return $this->belongsTo(Menucabecera::class, 'codmenucabecera');
    }
}
