<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aquí es donde puedes registrar rutas web para tu aplicación. Estas
| rutas son cargadas por el RouteServiceProvider y todas ellas serán
| asignadas al grupo de middleware "web". ¡Haz algo genial!
|
*/

// Ruta principal para la página de inicio del cliente
Route::get('/', [ClientController::class, 'home'])->name('client.home');

// Rutas para el panel de administración
Route::prefix('admin')->name('admin.')->group(function () {
    // Ruta para el dashboard de administración
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Ruta para la página de perfil de administración
    Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
});
