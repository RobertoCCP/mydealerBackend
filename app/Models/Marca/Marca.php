<?php

namespace App\Models\Marca;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;
    protected $table = 'marcas'; 
    protected $primaryKey = 'codmarca';

    public $incrementing = false; 

    protected $keyType = 'string'; 

    public $timestamps = false; 

    protected $fillable = [
        'codmarca',
        'nombre',
        'orden'
    ]; 
}
