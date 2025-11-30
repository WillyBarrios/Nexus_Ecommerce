<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Controlador de Autenticación
 * 
 * Este controlador maneja todas las operaciones relacionadas con
 * la autenticación de usuarios: registro, login y logout.
 * 
 * Utiliza Laravel Sanctum para la gestión de tokens de autenticación.
 * Todas las respuestas son en formato JSON con estructura consistente.
 * 
 * @package App\Http\Controllers\Api
 * @version Laravel 12.39.0
 */
class AuthController extends Controller
{
    /**
     * Registrar un nuevo usuario en el sistema
     * 
     * Este método valida los datos de entrada, crea el usuario con contraseña
     * hasheada automáticamente (Laravel 12) y retorna un token de autenticación.
     * 
     * Flujo:
     * 1. Validar datos con RegisterRequest (automático)
     * 2. Crear usuario (password se hashea automáticamente con cast 'hashed')
     * 3. Generar token de autenticación con Sanctum
     * 4. Retornar respuesta con usuario y token
     * 
     * @param RegisterRequest $request Datos validados del usuario
     * @return JsonResponse Usuario creado y token de autenticación
     * 
     * @response 201 {
     *   "success": true,
     *   "message": "Usuario registrado exitosamente",
     *   "data": {
     *     "user": {
     *       "id": 1,
     *       "name": "Juan Pérez",
     *       "email": "juan@example.com",
     *       "created_at": "2025-11-19T20:00:00.000000Z",
     *       "updated_at": "2025-11-19T20:00:00.000000Z"
     *     },
     *     "token": "1|abc123def456..."
     *   }
     * }
     * 
     * @response 422 {
     *   "success": false,
     *   "message": "Los datos proporcionados no son válidos",
     *   "errors": {
     *     "email": ["Este email ya está registrado en el sistema."]
     *   }
     * }
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        // Crear usuario con los datos validados
        // Nota: La contraseña se hashea automáticamente gracias al cast 'hashed' en Laravel 12
        // No es necesario usar Hash::make() manualmente
        $user = User::create([
            'nombre_completo' => $request->name,
            'correo_electronico' => $request->email,
            'contrasena' => $request->password, // Se hashea automáticamente
            'id_rol' => 3, // Por defecto asignamos rol de Cliente (id_rol = 3)
        ]);

        // Generar token de autenticación con Sanctum
        // El nombre 'auth-token' identifica el propósito del token
        $token = $user->createToken('auth-token')->plainTextToken;

        // Retornar respuesta exitosa con código HTTP 201 (Created)
        // El usuario se serializa automáticamente excluyendo password y remember_token
        return response()->json([
            'success' => true,
            'message' => 'Usuario registrado exitosamente',
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
        ], 201);
    }

    /**
     * Iniciar sesión de usuario
     * 
     * Este método valida las credenciales del usuario (email y contraseña),
     * verifica que sean correctas y retorna un token de autenticación.
     * 
     * Flujo:
     * 1. Validar formato de datos con LoginRequest (automático)
     * 2. Buscar usuario por email
     * 3. Verificar contraseña con Hash::check()
     * 4. Generar token de autenticación si credenciales son válidas
     * 5. Retornar respuesta con usuario y token
     * 
     * Seguridad:
     * - Retorna mensaje genérico si credenciales son incorrectas
     * - No revela si el email existe o si la contraseña es incorrecta
     * - Previene enumeración de usuarios
     * 
     * @param LoginRequest $request Credenciales del usuario
     * @return JsonResponse Usuario y token de autenticación o error
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "Inicio de sesión exitoso",
     *   "data": {
     *     "user": {
     *       "id": 1,
     *       "name": "Juan Pérez",
     *       "email": "juan@example.com",
     *       "created_at": "2025-11-19T20:00:00.000000Z",
     *       "updated_at": "2025-11-19T20:00:00.000000Z"
     *     },
     *     "token": "2|xyz789ghi012..."
     *   }
     * }
     * 
     * @response 401 {
     *   "success": false,
     *   "message": "Las credenciales proporcionadas son incorrectas"
     * }
     */
    public function login(LoginRequest $request): JsonResponse
    {
        // Buscar usuario por email
        $user = User::where('correo_electronico', $request->email)->first();

        // Verificar que el usuario existe y la contraseña es correcta
        // Hash::check() compara la contraseña en texto plano con el hash almacenado
        if (!$user || !Hash::check($request->password, $user->contrasena)) {
            // Retornar error genérico por seguridad
            // No revelamos si el email existe o si la contraseña es incorrecta
            return response()->json([
                'success' => false,
                'message' => 'Las credenciales proporcionadas son incorrectas',
            ], 401);
        }

        // Generar token de autenticación con Sanctum
        $token = $user->createToken('auth-token')->plainTextToken;

        // Retornar respuesta exitosa con código HTTP 200 (OK)
        return response()->json([
            'success' => true,
            'message' => 'Inicio de sesión exitoso',
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
        ], 200);
    }

    /**
     * Cerrar sesión del usuario
     * 
     * Este método invalida el token de autenticación actual del usuario,
     * cerrando su sesión en el dispositivo actual.
     * 
     * Flujo:
     * 1. Verificar que usuario esté autenticado (middleware auth:sanctum)
     * 2. Obtener token actual de la petición
     * 3. Eliminar token de la base de datos
     * 4. Retornar confirmación
     * 
     * Nota: Este método solo cierra sesión en el dispositivo actual.
     * Para cerrar sesión en todos los dispositivos, usar:
     * $request->user()->tokens()->delete();
     * 
     * @param Request $request Petición con usuario autenticado
     * @return JsonResponse Confirmación de cierre de sesión
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "Sesión cerrada exitosamente"
     * }
     * 
     * @response 401 {
     *   "success": false,
     *   "message": "No autenticado. Token inválido o expirado"
     * }
     */
    public function logout(Request $request): JsonResponse
    {
        // Eliminar el token actual del usuario
        // currentAccessToken() obtiene el token usado en esta petición
        // delete() lo elimina de la base de datos, invalidándolo
        $request->user()->currentAccessToken()->delete();

        // Retornar respuesta exitosa con código HTTP 200 (OK)
        return response()->json([
            'success' => true,
            'message' => 'Sesión cerrada exitosamente',
        ], 200);
    }
}
