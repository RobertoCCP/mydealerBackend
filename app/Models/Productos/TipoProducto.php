<?php

namespace App\Models\Productos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoProducto extends Model
{
    use HasFactory;

    protected $table = 'tipoproducto';
    protected $primaryKey = 'codtipoproducto';
    public $incrementing = false; // Asegúrate de que esta clave primaria no sea autoincremental si no debe serlo
    protected $keyType = 'string'; // Si el tipo de dato es varchar, usa 'string'
    public $timestamps = false;

    protected $fillable = [
        'codtipoproducto',  // Solo el código del tipo de producto
        'descripcion',      // Solo la descripción del tipo de producto
    ];

    // Eliminado el método grupoMaterial ya que no es necesario en este caso
}
