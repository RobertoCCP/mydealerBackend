<?php

use App\Http\Controllers\AuthCliente\AuthenticatedClienteSessionController;
use App\Http\Controllers\Categoria\CategoriaController;
use App\Http\Controllers\Producto\ProductoController;
use App\Http\Controllers\RegistroClienteController;
use App\Http\Controllers\Clientes\ClientePedidoController;
use App\Http\Controllers\DireccionEnvioController;
use App\Http\Controllers\FormaEnvio\FormaEnvioController;
use App\Http\Controllers\FormaPagoController;
use App\Http\Controllers\Marca\MarcaController;
use App\Http\Controllers\Pedido\PedidoController;
use App\Http\Controllers\Producto\AllProductosController;
use Illuminate\Support\Facades\Route;

#AUTH

Route::post('/login/cliente', [AuthenticatedClienteSessionController::class, 'login']);

#CLIENTES

Route::post('/clientes/register', [RegistroClienteController::class, 'register']);
Route::get('/clientes/{codcliente}', [RegistroClienteController::class, 'show']);

#PRODUCTOS

//Route::post('productos/detalles', [ProductoController::class, 'productoDetalles']);
Route::get('/productos/categoria', [ProductoController::class, 'buscarPorCategoria']);
Route::get('/productos/nombre', [ProductoController::class, 'buscarPorNombre']);
Route::get('/productos/categoria-nombre', [ProductoController::class, 'buscarPorCategoriaYNombre']);
Route::get('/all/productos', [AllProductosController::class, 'index']);

Route::get('/productos/detalles/{codproducto}', [ProductoController::class, 'productoDetalle']);
Route::get('/productos/productosrelacionado', [ProductoController::class, 'productosPorTipo']);

#FORMAS DE PAGO

Route::get('formas-pago', [FormaPagoController::class, 'index']);

# FORMAS DE ENVIO
Route::get('formasenvio', [FormaEnvioController::class, 'index']);

#DIRECCION DE ENVIO

Route::get('/clientes/{codcliente}/direcciones-envio', [DireccionEnvioController::class, 'obtenerDirecciones']);

#MARCA

Route::prefix('v1')->group(function () {
    Route::prefix('brands')->group(function () {
        Route::get('/', [MarcaController::class, 'getMarcas']); // Obtener todas las marcas
    });
});

#PEDIDOS

Route::get('/cliente/pedidos', [ClientePedidoController::class, 'getPedidosCliente']);
Route::get('cliente/pedidos/{srorden}', [PedidoController::class, 'getPedido']);

Route::post('/cliente/realizarpedido', [PedidoController::class,'create']);

#PRODUCTO

Route::prefix('v1')->group(function () {
    Route::get('/categories/products', [CategoriaController::class, 'getCategorias']);
});
