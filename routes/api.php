<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Controllers\Api\ProductoController;
use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\MarcaController;
use App\Http\Controllers\Api\CarritoController;
use App\Http\Controllers\Api\PedidoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * Rutas de API - Sistema de Autenticación Nexus
 * 
 * Este archivo define todas las rutas de la API REST para el sistema de autenticación.
 * Las rutas están organizadas en dos grupos:
 * - Rutas públicas: No requieren autenticación
 * - Rutas protegidas: Requieren token de autenticación (middleware auth:sanctum)
 * 
 * Todas las rutas tienen el prefijo /api automáticamente.
 * 
 * @version Laravel 12.39.0
 */

/*
|--------------------------------------------------------------------------
| Rutas Públicas (Sin autenticación)
|--------------------------------------------------------------------------
|
| Estas rutas son accesibles sin token de autenticación.
| Incluyen registro, login y recuperación de contraseña.
|
*/

// Grupo de rutas públicas
// Nota: Rate limiting deshabilitado temporalmente para testing
Route::group([], function () {
    
    /**
     * POST /api/register
     * Registrar un nuevo usuario en el sistema
     * 
     * Body (JSON):
     * {
     *   "name": "Juan Pérez",
     *   "email": "juan@example.com",
     *   "password": "password123",
     *   "password_confirmation": "password123"
     * }
     * 
     * Response 201: Usuario creado con token
     * Response 422: Error de validación
     */
    Route::post('/register', [AuthController::class, 'register']);

    /**
     * POST /api/login
     * Iniciar sesión de usuario
     * 
     * Body (JSON):
     * {
     *   "email": "juan@example.com",
     *   "password": "password123"
     * }
     * 
     * Response 200: Login exitoso con token
     * Response 401: Credenciales incorrectas
     * Response 422: Error de validación
     */
    Route::post('/login', [AuthController::class, 'login']);

    /**
     * POST /api/password/forgot
     * Solicitar recuperación de contraseña
     * 
     * Body (JSON):
     * {
     *   "email": "juan@example.com"
     * }
     * 
     * Response 200: Mensaje genérico (por seguridad)
     * Response 422: Error de validación
     */
    Route::post('/password/forgot', [PasswordResetController::class, 'sendResetLink']);

    /**
     * POST /api/password/reset
     * Restablecer contraseña con token
     * 
     * Body (JSON):
     * {
     *   "email": "juan@example.com",
     *   "token": "abc123def456...",
     *   "password": "newpassword123",
     *   "password_confirmation": "newpassword123"
     * }
     * 
     * Response 200: Contraseña restablecida
     * Response 400: Token inválido o expirado
     * Response 422: Error de validación
     */
    Route::post('/password/reset', [PasswordResetController::class, 'reset']);
});

/*
|--------------------------------------------------------------------------
| Rutas Protegidas (Requieren autenticación)
|--------------------------------------------------------------------------
|
| Estas rutas requieren un token de autenticación válido en el header:
| Authorization: Bearer {token}
|
| El middleware 'auth:sanctum' valida el token automáticamente.
|
*/

// Grupo de rutas protegidas con autenticación Sanctum
Route::middleware(['auth:sanctum'])->group(function () {
    
    /**
     * POST /api/logout
     * Cerrar sesión del usuario
     * 
     * Headers:
     * Authorization: Bearer {token}
     * 
     * Response 200: Sesión cerrada exitosamente
     * Response 401: Token inválido o expirado
     */
    Route::post('/logout', [AuthController::class, 'logout']);

    /**
     * GET /api/user
     * Obtener información del usuario autenticado
     * 
     * Headers:
     * Authorization: Bearer {token}
     * 
     * Response 200: Datos del usuario
     * Response 401: Token inválido o expirado
     */
    Route::get('/user', function (Request $request) {
        return response()->json([
            'success' => true,
            'data' => $request->user(),
        ]);
    });
});


/*
|--------------------------------------------------------------------------
| Rutas de Productos
|--------------------------------------------------------------------------
|
| Rutas para gestión de productos del e-commerce
|
*/

// Rutas públicas de productos (lectura)
Route::get('/productos', [ProductoController::class, 'index']);
Route::get('/productos/{id}', [ProductoController::class, 'show']);

// Rutas protegidas de productos (requieren autenticación de Admin)
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/productos', [ProductoController::class, 'store']);
    Route::put('/productos/{id}', [ProductoController::class, 'update']);
    Route::delete('/productos/{id}', [ProductoController::class, 'destroy']);
    
    // Gestión de imágenes
    Route::post('/productos/{id}/imagenes', [ProductoController::class, 'agregarImagen']);
    Route::delete('/productos/imagenes/{id}', [ProductoController::class, 'eliminarImagen']);
});


/*
|--------------------------------------------------------------------------
| Rutas de Categorías
|--------------------------------------------------------------------------
*/

// Rutas públicas de categorías
Route::get('/categorias', [CategoriaController::class, 'index']);
Route::get('/categorias/{id}', [CategoriaController::class, 'show']);
Route::get('/categorias/{id}/productos', [CategoriaController::class, 'productos']);

// Rutas protegidas de categorías
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/categorias', [CategoriaController::class, 'store']);
    Route::put('/categorias/{id}', [CategoriaController::class, 'update']);
    Route::delete('/categorias/{id}', [CategoriaController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| Rutas de Marcas
|--------------------------------------------------------------------------
*/

// Rutas públicas de marcas
Route::get('/marcas', [MarcaController::class, 'index']);
Route::get('/marcas/{id}', [MarcaController::class, 'show']);
Route::get('/marcas/{id}/productos', [MarcaController::class, 'productos']);

// Rutas protegidas de marcas
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/marcas', [MarcaController::class, 'store']);
    Route::put('/marcas/{id}', [MarcaController::class, 'update']);
    Route::delete('/marcas/{id}', [MarcaController::class, 'destroy']);
});


/*
|--------------------------------------------------------------------------
| Rutas del Carrito de Compras
|--------------------------------------------------------------------------
|
| Todas las rutas del carrito requieren autenticación
|
*/

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/carrito', [CarritoController::class, 'index']);
    Route::post('/carrito/agregar', [CarritoController::class, 'agregar']);
    Route::put('/carrito/actualizar/{id}', [CarritoController::class, 'actualizar']);
    Route::delete('/carrito/eliminar/{id}', [CarritoController::class, 'eliminar']);
    Route::delete('/carrito/vaciar', [CarritoController::class, 'vaciar']);
});


/*
|--------------------------------------------------------------------------
| Rutas de Pedidos
|--------------------------------------------------------------------------
|
| Todas las rutas de pedidos requieren autenticación
|
*/

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/pedidos', [PedidoController::class, 'index']);
    Route::get('/pedidos/{id}', [PedidoController::class, 'show']);
    Route::post('/pedidos', [PedidoController::class, 'store']);
    Route::put('/pedidos/{id}/estado', [PedidoController::class, 'actualizarEstado']);
    Route::delete('/pedidos/{id}', [PedidoController::class, 'cancelar']);
});


/*
|--------------------------------------------------------------------------
| Rutas de Pagos (PayPal y Stripe)
|--------------------------------------------------------------------------
|
| Sistema de pagos que soporta múltiples métodos:
| - PayPal
| - Stripe
| - Tarjeta
| - Efectivo
| - Transferencia
|
*/

use App\Http\Controllers\Api\PagoController;

Route::middleware(['auth:sanctum'])->group(function () {
    // Gestión de pagos
    Route::get('/pagos', [PagoController::class, 'index']);
    Route::get('/pagos/{id}', [PagoController::class, 'show']);
    Route::post('/pagos/crear', [PagoController::class, 'crear']);
    Route::post('/pagos/confirmar', [PagoController::class, 'confirmar']);
});

// Webhooks (no requieren autenticación, usan verificación de firma)
Route::post('/pagos/paypal/webhook', [PagoController::class, 'webhookPayPal']);
Route::post('/pagos/stripe/webhook', [PagoController::class, 'webhookStripe']);
