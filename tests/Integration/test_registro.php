<?php

require __DIR__.'/../../vendor/autoload.php';

$app = require_once __DIR__.'/../../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "=== TEST DE REGISTRO ===\n\n";

try {
    // Intentar crear un usuario
    $user = User::create([
        'nombre_completo' => 'Usuario de Prueba',
        'correo_electronico' => 'test@example.com',
        'contrasena' => 'password123',
        'id_rol' => 3,
    ]);
    
    echo "✓ Usuario creado exitosamente:\n";
    echo "  ID: {$user->id_usuario}\n";
    echo "  Nombre: {$user->nombre_completo}\n";
    echo "  Email: {$user->correo_electronico}\n";
    echo "  Rol: {$user->id_rol}\n";
    
    // Probar crear token
    $token = $user->createToken('test-token')->plainTextToken;
    echo "\n✓ Token creado: " . substr($token, 0, 20) . "...\n";
    
    // Probar serialización a JSON
    echo "\n✓ Serialización a JSON:\n";
    echo json_encode($user->toArray(), JSON_PRETTY_PRINT) . "\n";
    
    echo "\n=== TODO FUNCIONA CORRECTAMENTE ===\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "\nStack trace:\n" . $e->getTraceAsString() . "\n";
}
