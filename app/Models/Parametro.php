<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parametro extends Model
{    
    protected $table = 'parametro';
    
    protected $primaryKey = 'codparametro';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'codparametro',
        'descripcion',
        'valor',
        'categoria',
        'tipo',
    ];
}