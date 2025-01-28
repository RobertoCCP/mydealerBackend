<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Clientes\ClientesController;
use App\Http\Controllers\Clientes\ClientesRutaController;
use App\Http\Controllers\GPSVendedor\GPSVendedorController;
use App\Http\Controllers\Login\EmailController;
use App\Http\Controllers\OpcionesMenu\OpcionController;
use App\Http\Controllers\OpcionesMenu\PerfilController;
use App\Http\Controllers\PedidosVendedor\OrdenController;
use App\Http\Controllers\Vendedor\VendedorController;
use App\Models\Reportes\ReporteGestion\Vendedor;
use App\Http\Controllers\Configuracion\GpsHorarioController;
use App\Http\Controllers\ConfiguracionMovil\ConfiguracionMovilController;
use Illuminate\Support\Facades\Route;

Route::get('/ordenesR', [OrdenController::class, 'indexR']);

#AUTH

Route::post('/login', [AuthenticatedSessionController::class, 'login']);
Route::post('/recuperacionEmail', [EmailController::class, "enviarEmail"]);

#DIRECCIÓN ENVIO GPS

Route::get('/direccion/envio', [GPSVendedorController::class, 'direccionEnvio']);
Route::get('/direccion/envio/gps', [GPSVendedorController::class, 'direccionEnvioGPS']);
Route::get('/direccion/envio/gps/{codigo_vendedor}', [GPSVendedorController::class, 'direccionEnvioGPSporCodigoVendedor']);
Route::get('/direccion/envio/gps/{codigo_vendedor}/{codigo_cliente}', [GPSVendedorController::class, 'direccionEnvioGPSporCodigoVendedor']);
Route::get('/datos/usuario/cliente/{codigo_cliente}', [GPSVendedorController::class, 'buscarClientePorId']);
Route::get('/datos/usuario/vendedor/{codigo_vendedor}', [GPSVendedorController::class, 'buscarVendedorPorId']);
Route::get('/direccion/rutas', [GPSVendedorController::class, 'rutasVendedores']);
Route::get('/direccion/rutas/detalle', [GPSVendedorController::class, 'rutaDetalle']);
Route::get('/direccion/rutas/detalle/cliente/{codigo_cliente}', [GPSVendedorController::class, 'rutaDetallePorCodigoCliente']);
Route::get('/direccion/ruta/gestion', [GPSVendedorController::class, 'rutaGestion']);
Route::get('/direccion/ruta/gestion/{codigo_ruta_gestion}', [GPSVendedorController::class, 'rutaGestionById']);

#DASHBOARD

Route::get('/vendedor/informacion/{codvendedor}', [VendedorController::class, 'obtenerInformacion']);
Route::get('/vendedor/numeroPedidos/{codvendedor}', [VendedorController::class, 'obtenerNumeroPedidos']);
Route::get('/vendedor/numeroCobros/{codvendedor}', [VendedorController::class, 'obtenerNumeroCobros']);
Route::get('/vendedor/pedidos/{codvendedor}', [VendedorController::class, 'obtenerOrdenes']);
Route::get('/notificacionPedido/{destinatario}/{titulo}/{mensaje}', [VendedorController::class, 'envioCorreo']);

#REPORTE DE CLIENTES

Route::get('/vendedor/clientes/ruta/{codvendedor}', [ClientesController::class, 'obtenerClientesPorRuta']);
Route::get('/vendedor/clientes/cedula/{cedula}', [ClientesController::class, 'obtenerClientesPorCedula']);
Route::get('/vendedor/clientes/{codvendedor}', [ClientesController::class, 'obtenerClientePorVendedor']);
Route::get('/vendedor/clientes/{codvendedor}/{codruta}', [ClientesRutaController::class, 'ClientesPorVendedorRuta']);
Route::get('/vendedor/clientes/{codvendedor}/{codruta}/{codcliente}', [ClientesRutaController::class, 'ClientesPorVendedorRutaCliente']);
Route::get('/vendedor/clientesVendedor/{codvendedor}/{codcliente}', [ClientesRutaController::class, 'ClientesPorVendedor']);



#PEDIDOS

Route::get('/pedidosCompleto/{idVendedor}/{desde}/{hasta}/{idCliente}/{estado}', [OrdenController::class, 'pedidosCompleto']);
Route::get('/pedidosCompleto/{idVendedor}/{desde}/{hasta}/{idCliente}', [OrdenController::class, 'pedidosCompleto']);
Route::get('/pedidosCompleto/{idVendedor}/{desde}/{hasta}', [OrdenController::class, 'pedidosCompleto']);
Route::get('/pedidosCompleto/{idVendedor}/{desde}', [OrdenController::class, 'pedidosCompleto']);
Route::get('/pedidosCompleto/{idVendedor}', [OrdenController::class, 'pedidosCompleto']);
Route::get('/pedidosCompleto', [OrdenController::class, 'pedidosCompleto']);

#PEDIDOSPORESTADO
Route::get('/pedidosEstado/{idVendedor}/{estado}', [OrdenController::class, 'pedidosEstado']);
Route::get('/pedidosEstado/{idVendedor}', [OrdenController::class, 'pedidosTodos']);

# Información de vendedor

Route::get('/vendedor', [VendedorController::class, 'obtenerInfoVendedor']);
Route::put('/vendedor/{login}', [VendedorController::class, 'editarInfoVendedor']);
Route::delete('/vendedor/{login}', [VendedorController::class, 'eliminarVendedor']);
Route::get('/vendedor/datosmoviles/{codvendedor}', [VendedorController::class, 'obtenerDatosMobilVendedor']);

#APIS DUDOSAS - ELIMINAR O CORREGIR HERRERA

Route::apiResource('opcions', OpcionController::class);
Route::apiResource('perfil', PerfilController::class);

#GPSHORARIO

Route::get('/gpshorarios', [GpsHorarioController::class, 'index']);
Route::apiResource('gpshorarios', GpsHorarioController::class);

#CONFIGURACION
Route::get('/config', [ConfiguracionMovilController::class, 'index']);
