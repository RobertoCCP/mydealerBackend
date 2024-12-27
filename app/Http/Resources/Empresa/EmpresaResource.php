<?php

namespace App\Http\Resources\Empresa;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class EmpresaResource extends JsonResource
{    
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $decodedMensaje = "Bienvenido a My Dealer";
        
        try {
            if ($this->mensaje) {
                $decodedMensaje = base64_decode($this->mensaje, true);
                if ($decodedMensaje === false) {
                    $decodedMensaje = "Bienvenido a My Dealer";
                }
            }
        } catch (\Exception $e) {
            $decodedMensaje = "Bienvenido a My Dealer";
        }

        return [
            'codempresa' => $this->codempresa,
            'nombre' => $this->nombre ?? null,
            'direccion' => $this->direccion ?? null,
            'telefono' => $this->telefono ?? null,
            'pais' => $this->pais?? null,
            'provincia' => $this->provincia?? null,
            'ciudad' => $this->ciudad?? null,
            'nombretienda' => $this->nombretienda?? null,
            'emailsoporte' => $this->emailsoporte?? null,
            'emailorden' => $this->emailorden?? null,
            'usuarioadmin' => $this->usuarioadmin?? null,
            'claveadmin' => $this->claveadmin?? null,
            'mensaje' => $decodedMensaje,
            'cargo1' => $this->cargo1?? null,
            'nombre1' => $this->nombre1?? null,
            'email1' => $this->email1?? null,
            'foto1' => $this->foto1 ?  url(Storage::url($this->foto1)) : null,            
            'cargo2' => $this->cargo2?? null,
            'nombre2' => $this->nombre2?? null,
            'email2' => $this->email2?? null,
            'foto2' => $this->foto2 ? url(Storage::url($this->foto2)) : null,            
            'cargo3' => $this->cargo3?? null,
            'nombre3' => $this->nombre3?? null,
            'email3' => $this->email3?? null,
            'foto3' => $this->foto3 ? url(Storage::url($this->foto3)) : null,            
            'cargo4' => $this->cargo4?? null,
            'nombre4' => $this->nombre4?? null,
            'email4' => $this->email4?? null,
            'foto4' => $this->foto4 ? url(Storage::url($this->foto4)) : null,            
            'app_version' => $this->app_version?? null,
            'app_empresa' => $this->app_empresa?? null,
            'fotopiecob' => $this->fotopiecob ? url(Storage::url($this->foto4)) : null,
            'fax' => $this->fax?? null,
            'logo' => $this->logo ? url(Storage::url($this->logo)) : null,
            'logo_cabecera' => $this->logo_cabecera ? url(Storage::url($this->logo_cabecera)) : null,
        ];
    }
}
