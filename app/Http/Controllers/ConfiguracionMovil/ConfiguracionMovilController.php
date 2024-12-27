<?php

namespace App\Http\Controllers\ConfiguracionMovil;

use App\Http\Controllers\Controller;
use App\Traits\HttpResponses;

class ConfiguracionMovilController extends Controller {

    use HttpResponses;

    /**
     * @OA\Get(
     * path="/api/config",
     * tags={"Cliente - Configuracion Json"},
     * summary="Devuelve la configuración Json",
     * description="Devuelve la configuración por defecto para la app móvil",
     * @OA\Response(
     *         response=200,
     *         description="successful operation",     
     *     ),
     * @OA\Response(
     *     response=500,
     *     description="Database error"
     * ),
     * )  
     */ 
    public function index()
    {
        // Ruta al archivo JSON en la nueva ubicación
        $jsonPath = app_path('Http/Controllers/ConfiguracionMovil/json/data.json');
        
        // Leer el contenido del archivo JSON
        if (!file_exists($jsonPath)) {
            return response()->json(['error' => 'JSON file not found'], 404);
        }

        $json = file_get_contents($jsonPath);
        
        // Decodificar el JSON para asegurarse de que es válido
        $data = json_decode($json, true);

        // Comprobar si hay errores en la decodificación del JSON
        if (json_last_error() !== JSON_ERROR_NONE) {            
            return $this->error(['error' => 'Failed to decode JSON'], null,500);
        }

        // Devolver el JSON como respuesta
        return $this->success($data);
    }
}