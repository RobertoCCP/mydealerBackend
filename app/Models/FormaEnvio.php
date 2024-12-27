<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormaEnvio extends Model
{
    use HasFactory;

    protected $table = 'formaenvio'; // Nombre de la tabla en la base de datos

    protected $primaryKey = 'codformaenvio'; // Clave primaria de la tabla

    public $incrementing = false; // Si la clave primaria es autoincremental

    protected $keyType = 'string'; // Tipo de dato de la clave primaria

    protected $fillable = [
        'codformaenvio', 'nombre'
    ];

    public $timestamps = false; // Indica que la tabla no tiene timestamps created_at y updated_at
}
