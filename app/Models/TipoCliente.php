<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoCliente extends Model
{
    protected $table = 'tipocliente';

    protected $primaryKey = 'codtipocliente';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false; 

    protected $fillable = [        
        'codtipocliente',
        'descripcion',        
    ];
}
