<?php

return [

    /*
    |----------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |----------------------------------------------------------------------
    |
    | Aquí puedes configurar los ajustes para el intercambio de recursos
    | de origen cruzado (CORS). Esto determina qué operaciones de origen cruzado
    | pueden ejecutarse en los navegadores web. Puedes ajustar estos ajustes según sea necesario.
    |
    | Para aprender más: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['*'],  // Permitir todas las rutas

    'allowed_methods' => ['*'],  // Permitir todos los métodos HTTP

    // Asegúrate de agregar las URLs correctas de tu frontend (Flutter)
    'allowed_origins' => [
        env('FRONTEND_URL', 'http://localhost:3000'),  // Cambia esto si tu frontend usa otro puerto o dirección
        env('TEST_FRONTEND_URL', 'http://localhost:4200'),  // Si tienes un entorno de prueba, también agrégalo
        'http://10.0.2.2:8000',
    ],

    'allowed_origins_patterns' => [],  // No es necesario si no necesitas patrones de origen

    'allowed_headers' => ['*'],  // Permitir todas las cabeceras

    'exposed_headers' => [],  // No es necesario si no necesitas exponer cabeceras

    'max_age' => 0,  // Tiempo máximo para caché de CORS

    'supports_credentials' => true,  // Soporta credenciales como cookies, cabeceras de autorización, etc.

];