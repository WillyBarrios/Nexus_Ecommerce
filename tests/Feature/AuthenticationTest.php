<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * Pruebas de Autenticación
 * 
 * Este archivo contiene todas las pruebas para el sistema de autenticación:
 * - Registro de usuarios
 * - Login
 * - Logout
 * - Obtener usuario autenticado
 * - Recuperación de contraseña
 * - Restablecimiento de contraseña
 * 
 * NOTA: Estas pruebas NO borran datos de la BD.
 * Usan emails únicos con timestamp para evitar conflictos.
 */
class AuthenticationTest extends TestCase
{
    /**
     * Email único para cada ejecución
     */
    private static $timestamp;

    /**
     * Configuración inicial antes de cada prueba
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Generar timestamp único para esta ejecución
        if (!self::$timestamp) {
            self::$timestamp = time();
        }
    }
    
    /**
     * Generar email único para pruebas
     */
    private function uniqueEmail($prefix = 'test')
    {
        return $prefix . self::$timestamp . '@example.com';
    }

    /**
     * TEST 1: Registro exitoso de usuario
     * 
     * Verifica que un usuario puede registrarse correctamente
     * con datos válidos y recibe un token de autenticación.
     */
    public function test_usuario_puede_registrarse_exitosamente()
    {
        echo "\n TEST 1: Registro de Usuario\n";
        echo "================================\n";
        
        $email = $this->uniqueEmail('registro');
        
        $response = $this->postJson('/api/register', [
            'name' => 'Usuario Prueba',
            'email' => $email,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        echo "✓ Enviando datos de registro...\n";
        echo "  Nombre: Usuario Prueba\n";
        echo "  Email: {$email}\n";
        
        $response->assertStatus(201);
        echo "✓ Status: 201 Created\n";
        
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'user' => ['id', 'name', 'email'],
                'token'
            ]
        ]);
        echo "✓ Respuesta tiene estructura correcta\n";
        
        $this->assertDatabaseHas('usuarios', [
            'correo_electronico' => $email,
        ]);
        echo "✓ Usuario guardado en base de datos\n";
        echo "✅ TEST PASADO\n\n";
    }

    /**
     * TEST 2: Registro falla con email duplicado
     * 
     * Verifica que no se puede registrar un usuario
     * con un email que ya existe en el sistema.
     */
    public function test_registro_falla_con_email_duplicado()
    {
        echo "   TEST 2: Email Duplicado\n";
        echo "================================\n";
        
        $email = $this->uniqueEmail('duplicado');
        
        // Crear usuario existente
        User::create([
            'nombre_completo' => 'Usuario Existente',
            'correo_electronico' => $email,
            'contrasena' => 'password123',
            'id_rol' => 3,
        ]);
        echo "✓ Usuario existente creado\n";

        // Intentar registrar con mismo email
        $response = $this->postJson('/api/register', [
            'name' => 'Otro Usuario',
            'email' => $email,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        echo "✓ Intentando registrar email duplicado...\n";
        
        $response->assertStatus(422);
        echo "✓ Status: 422 Unprocessable Entity\n";
        
        $response->assertJsonValidationErrors(['email']);
        echo "✓ Error de validación en campo email\n";
        echo "✅ TEST PASADO\n\n";
    }

    /**
     * TEST 3: Login exitoso
     * 
     * Verifica que un usuario puede iniciar sesión
     * con credenciales válidas y recibe un token.
     */
    public function test_usuario_puede_hacer_login()
    {
        echo "🧪 TEST 3: Login Exitoso\n";
        echo "================================\n";
        
        $email = $this->uniqueEmail('login');
        
        // Crear usuario
        User::create([
            'nombre_completo' => 'Usuario Login',
            'correo_electronico' => $email,
            'contrasena' => 'password123',
            'id_rol' => 3,
        ]);
        echo "✓ Usuario creado para prueba\n";

        // Hacer login
        $response = $this->postJson('/api/login', [
            'email' => $email,
            'password' => 'password123',
        ]);

        echo "✓ Enviando credenciales...\n";
        
        $response->assertStatus(200);
        echo "✓ Status: 200 OK\n";
        
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'user',
                'token'
            ]
        ]);
        echo "✓ Token recibido correctamente\n";
        echo "✅ TEST PASADO\n\n";
    }

    /**
     * TEST 4: Login falla con credenciales incorrectas
     * 
     * Verifica que el login falla cuando se proporcionan
     * credenciales incorrectas.
     */
    public function test_login_falla_con_credenciales_incorrectas()
    {
        echo "🧪 TEST 4: Credenciales Incorrectas\n";
        echo "================================\n";
        
        $email = $this->uniqueEmail('fail');
        
        // Crear usuario
        User::create([
            'nombre_completo' => 'Usuario Test',
            'correo_electronico' => $email,
            'contrasena' => 'password123',
            'id_rol' => 3,
        ]);

        // Intentar login con contraseña incorrecta
        $response = $this->postJson('/api/login', [
            'email' => $email,
            'password' => 'contraseña_incorrecta',
        ]);

        echo "✓ Intentando login con contraseña incorrecta...\n";
        
        $response->assertStatus(401);
        echo "✓ Status: 401 Unauthorized\n";
        
        $response->assertJson([
            'success' => false,
        ]);
        echo "✓ Acceso denegado correctamente\n";
        echo "✅ TEST PASADO\n\n";
    }

    /**
     * TEST 5: Obtener usuario autenticado
     * 
     * Verifica que un usuario autenticado puede
     * obtener sus propios datos.
     */
    public function test_usuario_autenticado_puede_obtener_sus_datos()
    {
        echo "🧪 TEST 5: Obtener Usuario Autenticado\n";
        echo "================================\n";
        
        $email = $this->uniqueEmail('auth');
        
        // Crear usuario
        $user = User::create([
            'nombre_completo' => 'Usuario Auth',
            'correo_electronico' => $email,
            'contrasena' => 'password123',
            'id_rol' => 3,
        ]);
        echo "✓ Usuario creado\n";

        // Crear token
        $token = $user->createToken('test-token')->plainTextToken;
        echo "✓ Token generado\n";

        // Obtener datos del usuario
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/user');

        echo "✓ Solicitando datos con token...\n";
        
        $response->assertStatus(200);
        echo "✓ Status: 200 OK\n";
        
        $response->assertJson([
            'success' => true,
            'data' => [
                'email' => $email,
            ]
        ]);
        echo "✓ Datos del usuario recibidos\n";
        echo "✅ TEST PASADO\n\n";
    }

    /**
     * TEST 6: Acceso denegado sin token
     * 
     * Verifica que las rutas protegidas no son accesibles
     * sin un token de autenticación válido.
     */
    public function test_acceso_denegado_sin_token()
    {
        echo "🧪 TEST 6: Acceso Sin Token\n";
        echo "================================\n";
        
        $response = $this->getJson('/api/user');

        echo "✓ Intentando acceder sin token...\n";
        
        $response->assertStatus(401);
        echo "✓ Status: 401 Unauthorized\n";
        echo "✓ Acceso denegado correctamente\n";
        echo "✅ TEST PASADO\n\n";
    }

    /**
     * TEST 7: Logout exitoso
     * 
     * Verifica que un usuario puede cerrar sesión
     * y su token se invalida correctamente.
     */
    public function test_usuario_puede_hacer_logout()
    {
        echo "🧪 TEST 7: Logout\n";
        echo "================================\n";
        
        $email = $this->uniqueEmail('logout');
        
        // Crear usuario y token
        $user = User::create([
            'nombre_completo' => 'Usuario Logout',
            'correo_electronico' => $email,
            'contrasena' => 'password123',
            'id_rol' => 3,
        ]);
        
        $token = $user->createToken('test-token')->plainTextToken;
        echo "✓ Usuario y token creados\n";

        // Hacer logout
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/logout');

        echo "✓ Cerrando sesión...\n";
        
        $response->assertStatus(200);
        echo "✓ Status: 200 OK\n";
        
        $response->assertJson([
            'success' => true,
            'message' => 'Sesión cerrada exitosamente',
        ]);
        echo "✓ Sesión cerrada correctamente\n";
        
        // Verificar que el token fue eliminado
        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id_usuario,
        ]);
        echo "✓ Token eliminado de la base de datos\n";
        echo "✅ TEST PASADO\n\n";
    }

    /**
     * TEST 8: Solicitar recuperación de contraseña
     * 
     * Verifica que se puede solicitar un token de
     * recuperación de contraseña.
     */
    public function test_puede_solicitar_recuperacion_de_contrasena()
    {
        echo "🧪 TEST 8: Recuperación de Contraseña\n";
        echo "================================\n";
        
        $email = $this->uniqueEmail('forgot');
        
        // Crear usuario
        User::create([
            'nombre_completo' => 'Usuario Reset',
            'correo_electronico' => $email,
            'contrasena' => 'password123',
            'id_rol' => 3,
        ]);
        echo "✓ Usuario creado\n";

        // Solicitar recuperación
        $response = $this->postJson('/api/password/forgot', [
            'email' => $email,
        ]);

        echo "✓ Solicitando token de recuperación...\n";
        
        $response->assertStatus(200);
        echo "✓ Status: 200 OK\n";
        
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => ['token']
        ]);
        echo "✓ Token de recuperación generado\n";
        
        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => $email,
        ]);
        echo "✓ Token guardado en base de datos\n";
        echo "✅ TEST PASADO\n\n";
    }

    /**
     * TEST 9: Restablecer contraseña con token válido
     * 
     * Verifica que se puede restablecer la contraseña
     * usando un token válido.
     */
    public function test_puede_restablecer_contrasena_con_token_valido()
    {
        echo "🧪 TEST 9: Restablecer Contraseña\n";
        echo "================================\n";
        
        $email = $this->uniqueEmail('reset');
        
        // Crear usuario
        $user = User::create([
            'nombre_completo' => 'Usuario Reset',
            'correo_electronico' => $email,
            'contrasena' => 'password123',
            'id_rol' => 3,
        ]);
        echo "✓ Usuario creado\n";

        // Generar token de recuperación
        $token = \Str::random(64);
        \DB::table('password_reset_tokens')->insert([
            'email' => $email,
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);
        echo "✓ Token de recuperación generado\n";

        // Restablecer contraseña
        $response = $this->postJson('/api/password/reset', [
            'email' => $email,
            'token' => $token,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        echo "✓ Restableciendo contraseña...\n";
        
        $response->assertStatus(200);
        echo "✓ Status: 200 OK\n";
        
        $response->assertJson([
            'success' => true,
            'message' => 'Contraseña restablecida exitosamente',
        ]);
        echo "✓ Contraseña actualizada\n";
        
        // Verificar que el token fue eliminado
        $this->assertDatabaseMissing('password_reset_tokens', [
            'email' => $email,
        ]);
        echo "✓ Token de recuperación eliminado\n";
        echo "✅ TEST PASADO\n\n";
    }

    /**
     * TEST 10: Validación de campos requeridos
     * 
     * Verifica que todos los campos requeridos
     * son validados correctamente.
     */
    public function test_validacion_de_campos_requeridos()
    {
        echo " TEST 10: Validación de Campos\n";
        echo "================================\n";
        
        // Intentar registro sin datos
        $response = $this->postJson('/api/register', []);

        echo "✓ Enviando registro sin datos...\n";
        
        $response->assertStatus(422);
        echo "✓ Status: 422 Unprocessable Entity\n";
        
        $response->assertJsonValidationErrors(['name', 'email', 'password']);
        echo "✓ Errores de validación detectados:\n";
        echo "  - name requerido\n";
        echo "  - email requerido\n";
        echo "  - password requerido\n";
        echo " TEST PASADO\n\n";
    }
}
