<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

// web.php


use App\Http\Controllers\Auth\ForgotPasswordController;

// Ruta para solicitar el enlace de restablecimiento de contraseña
Route::post('password/forgot', [ForgotPasswordController::class, 'sendResetLinkEmail']); 

// Ruta para mostrar el formulario de restablecimiento de contraseña
Route::get('password/reset/{token}/{email}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');

// Ruta para procesar el restablecimiento de la contraseña
Route::post('password/reset', [ForgotPasswordController::class, 'reset'])->name('password.update');

// Ruta para mostrar el mensaje de éxito después de un restablecimiento exitoso
Route::get('password/success', function() {
    return view('auth.passwords.success');
})->name('password.success');