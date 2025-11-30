<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductoAdminController;
use App\Http\Controllers\Admin\CategoriaAdminController;
use App\Http\Controllers\Admin\MarcaAdminController;
use App\Http\Controllers\Admin\PedidoAdminController;
use App\Http\Controllers\Admin\UsuarioAdminController;

Route::get('/', function () {
    return redirect('/admin');
});

// Rutas del Dashboard Administrativo
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Productos
    Route::resource('productos', ProductoAdminController::class);
    
    // Categorías
    Route::resource('categorias', CategoriaAdminController::class);
    
    // Marcas
    Route::resource('marcas', MarcaAdminController::class);
    
    // Pedidos
    Route::get('pedidos', [PedidoAdminController::class, 'index'])->name('pedidos.index');
    Route::get('pedidos/{id}', [PedidoAdminController::class, 'show'])->name('pedidos.show');
    Route::post('pedidos/{id}/estado', [PedidoAdminController::class, 'updateEstado'])->name('pedidos.updateEstado');
    
    // Usuarios
    Route::resource('usuarios', UsuarioAdminController::class);
});
