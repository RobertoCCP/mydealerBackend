<?php

namespace App\Models\Vendedor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Vendedor extends Authenticatable implements JWTSubject
{
    use HasFactory;
    protected $table = 'vendedor';
    protected $primaryKey = 'codvendedor';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'codvendedor',
        'nombre',
        'email',
        'login',
        'password',
        'estado',
        'mac_pend_asignacion',
        'Fecha_requerimiento',
        'Estado_Autorizacion',
        'Fecha_Autorizacion',
        'codruta'
    ];
    public $timestamps = false;
    // protected $hidden = [
    //     'password',
    // ];
    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->Fecha_requerimiento = $model->Fecha_requerimiento ?? now();
        });
        self::updating(function ($model) {
            $model->Fecha_Autorizacion = $model->Fecha_Autorizacion ?? now();
        });
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function ruta()
    {
        return $this->belongsTo(Ruta::class, 'codruta', 'codruta');
    }
}
