<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PasswordResetController extends Controller
{
    public function sendResetLink(ForgotPasswordRequest $request): JsonResponse
    {
        // Buscar usuario por email
        $user = User::where('correo_electronico', $request->email)->first();

        // Si el usuario no existe, retornar mensaje genérico por seguridad
        if (!$user) {
            return response()->json([
                'success' => true,
                'message' => 'Si el email existe, recibirás un token de recuperación',
            ], 200);
        }

        // Generar token aleatorio seguro
        $token = Str::random(64);

        // Eliminar tokens anteriores del mismo email
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        // Almacenar nuevo token hasheado
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => Carbon::now(),
        ]);

        // Retornar token (solo para desarrollo - en producción se enviaría por email)
        return response()->json([
            'success' => true,
            'message' => 'Token de recuperación generado',
            'data' => [
                'token' => $token, // Solo para desarrollo
            ],
        ], 200);
    }

    public function reset(ResetPasswordRequest $request): JsonResponse
    {
        // Buscar token en la base de datos
        $passwordReset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        // Verificar que existe un token
        if (!$passwordReset) {
            return response()->json([
                'success' => false,
                'message' => 'Token inválido o expirado',
            ], 400);
        }

        // Verificar que el token no haya expirado (60 minutos)
        $tokenCreatedAt = Carbon::parse($passwordReset->created_at);
        if ($tokenCreatedAt->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return response()->json([
                'success' => false,
                'message' => 'El token ha expirado',
            ], 400);
        }

        // Verificar que el token coincida
        if (!Hash::check($request->token, $passwordReset->token)) {
            return response()->json([
                'success' => false,
                'message' => 'Token inválido',
            ], 400);
        }

        // Buscar usuario
        $user = User::where('correo_electronico', $request->email)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado',
            ], 404);
        }

        // Actualizar contraseña
        $user->contrasena = $request->password;
        $user->save();

        // Eliminar token usado
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Contraseña restablecida exitosamente',
        ], 200);
    }
}
