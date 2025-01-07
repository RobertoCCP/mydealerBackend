<?php
// filepath: /c:/xampp/htdocs/mydealerBackend/app/Helpers/MyHelper.php
if (!function_exists('jsonResponse')) {
    function jsonResponse($estado, $mensaje, $datos = null) {
        if($datos == null) {
            return response()->json([
                'estado' => $estado,
                'mensaje' => $mensaje
            ], 200);
        }
        return response()->json([
            'estado' => $estado,
            'mensaje' => $mensaje,
            'datos' => $datos
        ], 200);
    }
}