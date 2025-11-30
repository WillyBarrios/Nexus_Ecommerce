<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Configuración de la Aplicación Laravel
 * 
 * Este archivo configura:
 * - Rutas (web, api, console)
 * - Middleware
 * - Manejo de excepciones
 * 
 * @version Laravel 12.39.0
 */

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Middleware configuration
        // Rate limiting deshabilitado temporalmente para testing
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        /**
         * Configuración del manejo de excepciones
         * 
         * Personaliza las respuestas de error para que todas sean JSON
         * con estructura consistente para la API.
         */

        // Manejar errores de validación (422)
        // Retorna estructura JSON consistente con mensajes en español
        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Los datos proporcionados no son válidos',
                    'errors' => $e->errors(),
                ], 422);
            }
        });

        // Manejar errores de autenticación (401)
        // Retorna mensaje cuando el token es inválido o no está presente
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No autenticado. Token inválido o expirado',
                ], 401);
            }
        });

        // Manejar errores 404 (Not Found)
        // Retorna mensaje cuando la ruta no existe
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ruta no encontrada',
                ], 404);
            }
        });

        // Manejar otros errores HTTP (400, 403, 500, etc.)
        $exceptions->render(function (HttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage() ?: 'Error en el servidor',
                ], $e->getStatusCode());
            }
        });

        // Manejar errores generales del servidor (500)
        // Solo en producción, oculta detalles del error
        $exceptions->render(function (\Throwable $e, Request $request) {
            if ($request->is('api/*') && !config('app.debug')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error interno del servidor',
                ], 500);
            }
        });
    })->create();
