<?php

namespace App\Models\OpcionesMenu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menucabecera extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'codmenucabecera';
    protected $fillable = ['nombre', 'orden'];
    protected $table = 'menucabecera';
    public function opcions()
    {
        return $this->hasMany(Opcion::class, 'codmenucabecera');
    }
}
