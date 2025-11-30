<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authService;
    
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $resultado = $this->authService->registrarUsuario($request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Usuario registrado exitosamente',
                'data' => $resultado,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $resultado = $this->authService->iniciarSesion($request->email, $request->password);
            
            return response()->json([
                'success' => true,
                'message' => 'Inicio de sesión exitoso',
                'data' => $resultado,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 401);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        $this->authService->cerrarSesion($request->user());

        return response()->json([
            'success' => true,
            'message' => 'Sesión cerrada exitosamente',
        ], 200);
    }
}
