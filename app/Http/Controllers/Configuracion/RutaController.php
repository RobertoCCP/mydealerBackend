<?php

namespace App\Http\Controllers\Configuracion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RutaController extends Controller
{
    public function rutasgps($id_ruta_gestion ='')
    {
        
         if($id_ruta_gestion !== ''){
            $result = DB::table('rutas')
            ->join('rutadet as rd', 'rd.codruta', '=', 'rutas.codruta')
            ->join('rutagestion as rg', 'rutas.codruta', '=', 'rg.codruta')
            ->join('rutagestiondet as rgd', 'rgd.codrutagestion', '=', 'rg.codrutagestion')
            ->where('rg.codrutagestion', '=', $id_ruta_gestion)
            ->select('rg.codrutagestion as ruta_gestion', 'rgd.codrutagestiondet as ruta_gestion_detalle','rd.semana', 'rd.diasemana', 'rgd.horaini', 'rgd.horafin', 'rgd.posiciongps', 'rgd.descripcion')
            ->get();
         }else{
            $result = DB::table('rutas')
            ->join('rutadet as rd', 'rd.codruta', '=', 'rutas.codruta')
            ->join('rutagestion as rg', 'rutas.codruta', '=', 'rg.codruta')
            ->join('rutagestiondet as rgd', 'rgd.codrutagestion', '=', 'rg.codrutagestion')
            ->select('rg.codrutagestion as ruta_gestion', 'rgd.codrutagestiondet as ruta_gestion_detalle','rd.semana', 'rd.diasemana', 'rgd.horaini', 'rgd.horafin', 'rgd.posiciongps', 'rgd.descripcion')
            //->gruopBy('')
            ->get();
         }

        if ($result->isNotEmpty()) {
            return $this->imprimirError("0", "Ok", $result);
        } else {
            return $this->imprimirError("9999", "No cuenta con datos");
        }
    }
    function imprimirError($error, $mensaje, $i_datos = null)
    {
        if (isset($i_datos))
            return [
                "error" => $error,
                "mensaje" => $mensaje,
                "datos" => $i_datos
            ];
        else
            return [
                "error" => $error,
                "mensaje" => $mensaje
            ];
    }
}
