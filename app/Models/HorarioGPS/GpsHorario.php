<?php

namespace App\Models\HorarioGPS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GpsHorario extends Model
{
    use HasFactory;

    protected $table = 'gpshorario';
    protected $primaryKey = 'numdiasemana';
    public $timestamps = false;

    protected $fillable = [
        'nombredia',
        'horaini',
        'horafin',
        'frecuenciatoma',
    ];
}