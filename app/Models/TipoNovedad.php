<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoNovedad extends Model
{    
    protected $table = 'tiponovedad';

    protected $primaryKey = 'codtiponovedad';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false; 

    protected $fillable = [        
        'codtiponovedad',
        'tiponovedad',
        'categoria',
        'orden',
        'control',
        'estado',
    ];
}