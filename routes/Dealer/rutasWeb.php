<?php

use App\Http\Controllers\AuthWeb\AuthenticatedWebSessionController;
use App\Http\Controllers\Clientes\ClienteController;
use App\Http\Controllers\Configuracion\ParametroController;
use App\Http\Controllers\Configuracion\RutaController;
use App\Http\Controllers\Configuracion\TipoNovedadController;
use App\Http\Controllers\Empresa\EmpresaController;
use App\Http\Controllers\Empresa\UsuarioAdminController;
use App\Http\Controllers\Menu\MenuCabeceraController;
use App\Http\Controllers\Socios\TipoClienteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OpcionesMenu\MenucabeceraController as MenuCabeceraCtr;
use App\Http\Controllers\OpcionesMenu\PerfilController;
use App\Http\Controllers\Productos\TipoProductoController;
use App\Http\Controllers\ReportePedidos\ReportePedidosController;
use App\Http\Controllers\Reportes\ReporteGestionController;
use App\Http\Controllers\Reportes\ReporteCorreosController;
use App\Http\Controllers\Reportes\ReporteGPSEstadoController;
use App\Http\Controllers\Roles\PerfilUsuarioController;
use App\Http\Controllers\Socios\EstadoCuentaController;
use App\Http\Controllers\Socios\ReporteDireccionEnvioController;
use App\Http\Controllers\Carrito\CarritoController;
use App\Http\Requests\Reportes\ReporteCorreosRequest;

// RUTA DE PRUEBA
Route::get('/helloworld', fn () => ['message' => 'Hello World!']);

#AUT

Route::post('/loginweb', [AuthenticatedWebSessionController::class, 'login']);

#DATOS EMPRESA

Route::apiResource('datos/empresa', EmpresaController::class);

#OPCIONES POR PERFIL o ROLES

Route::get('/opcion/perfil/usuario', [PerfilController::class, 'index']);
Route::get('/opcion/perfil/usuario/{codigo_perfil}', [PerfilController::class, 'show']);
Route::get('/opcion/perfil/{srperfil}', [PerfilController::class, 'opcionesPorPerfil']);
Route::put('/opcion/perfil/usuario/{srperfil}/{sropcion}', [PerfilController::class, 'actualizarSeleccion']);

#TIPO NOVEDAD

Route::apiResource('tipo/novedad', TipoNovedadController::class);

#REPORTE TIPO CLIENTE

Route::get('tipo/cliente', [TipoClienteController::class, 'index']);

#MENU CABECERA

Route::get('/menucabecerasCompleto/', [MenuCabeceraCtr::class, 'completo']);
Route::get('/menucabecerasCompleto/{id}', [MenuCabeceraCtr::class, 'completo']);
Route::apiResource('menucabeceras', MenuCabeceraCtr::class);

#USUARIO DE ADMINISTRACION

Route::get('/user/administracion', [UsuarioAdminController::class, 'index']);
Route::get('/user/administracion/perfiles', [UsuarioAdminController::class, 'obtenerPerfiles']);
Route::get('/user/administracion/divisiones', [UsuarioAdminController::class, 'obtenerDivisiones']);
Route::get('/user/administracion/{loginusuario}', [UsuarioAdminController::class, 'show']);
Route::delete('/user/administracion/{loginusuario}', [UsuarioAdminController::class, 'destroy']);
Route::post('/user/administracion', [UsuarioAdminController::class, 'store']);
Route::put('/user/administracion/{loginusuario}', [UsuarioAdminController::class, 'update']);

#PARAMETRO

Route::apiResource('parametros', ParametroController::class);

#TIPO PRODUCTO

Route::apiResource('tipo/producto', TipoProductoController::class);

#PERFIL USUARIO

Route::apiResource('user/perfil', PerfilUsuarioController::class)->names('user.perfil');

#RUTAS GPS

Route::get('/rutasgps/{id}', [RutaController::class, 'rutasgps']);
Route::get('/rutasgps', [RutaController::class, 'rutasgps']);

#REPORTE GESTION

Route::get('reporte/gestion', [ReporteGestionController::class, 'index']);

#REPORTE GPS ESTADO

Route::get('reporte/gps/estado', [ReporteGPSEstadoController::class, 'index']);

#REPORTE CORREOS

Route::get('reporte/correos', [ReporteCorreosController::class, 'index']);

#MENU CABECERA

Route::apiResource('menu/cabecera', MenuCabeceraController::class, ['parameters' => ['cabecera' => 'menuCabecera']]);

// REPORTE PEDIDOS
Route::apiResource('reporte/pedidos', ReportePedidosController::class)->only(['show']);
Route::post('reporte/pedidos', [ReportePedidosController::class, 'index'], ['parameters' => ['pedidos' => 'orden']]);


// RECUPERACION DE CONTRASEÑA ADMIN
Route::post('/usuario/admin/recuperacion', [AuthenticatedWebSessionController::class, 'recuperarClave']);

//REPORTE ESTADO DE CUENTA
Route::get('reporte/estado/cuenta', [EstadoCuentaController::class, 'index']);

//REPORTE DIRECCION DE ENVIO
Route::get('reporte/direccion/envio', [ReporteDireccionEnvioController::class, 'index']);


// Recuperacion de clientes
Route::get('cliente', [ClienteController::class, 'index']);

// Carrito 
Route::post('/carrito/agregar', [CarritoController::class, 'agregarProducto']);
Route::get('/carrito', [CarritoController::class, 'listarProductosCarrito']);
Route::put('/carrito/actualizar', [CarritoController::class,'actualizarCantidad']);
Route::delete('/carrito/borrarProducto', [CarritoController::class,'eliminarProductoCarrito']);
Route::post('/carrito/seleccionarArticulosCarrito', [CarritoController::class, 'seleccionarArticulosDelCarrito']);
