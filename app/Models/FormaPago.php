<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormaPago extends Model
{
    use HasFactory;
    // Define la tabla asociada con este modelo
    protected $table = 'formapago';

    // Define la clave primaria de la tabla
    protected $primaryKey = 'codformapago';

    // Indica que la clave primaria no es un incremento automático
    public $incrementing = false;

    // Indica que la clave primaria es de tipo string (varchar)
    protected $keyType = 'string';

    // Si no usas timestamps, puedes deshabilitarlo
    public $timestamps = false;

    // Define las columnas que pueden ser asignadas en masa
    protected $fillable = ['codformapago', 'nombre'];
}
