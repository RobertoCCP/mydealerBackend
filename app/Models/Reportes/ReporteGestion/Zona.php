<?php
namespace App\Models\Reportes\ReporteGestion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zona extends Model
{
    use HasFactory;

    protected $table = 'zona';
    protected $primaryKey = 'codzona';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'codzona',
        'descripcion',
        'codvendedor',
    ];

    public function rutasGestion()
    {
        return $this->hasMany(RutaGestion::class, 'codzona', 'codzona');
    }
}
