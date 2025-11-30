<?php

require __DIR__.'/../../vendor/autoload.php';

$app = require_once __DIR__.'/../../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== VERIFICACIÓN DE CONEXIÓN Y DATOS ===\n\n";

try {
    // Verificar conexión
    $dbName = DB::connection()->getDatabaseName();
    echo "✓ Conectado a: {$dbName}\n\n";
    
    // Contar roles
    $rolesCount = DB::table('roles')->count();
    echo "✓ Roles en BD: {$rolesCount}\n";
    
    if ($rolesCount > 0) {
        $roles = DB::table('roles')->get();
        foreach ($roles as $rol) {
            echo "  - ID: {$rol->id_rol}, Nombre: {$rol->nombre_rol}\n";
        }
    }
    
    // Contar usuarios
    $usuariosCount = DB::table('usuarios')->count();
    echo "\n✓ Usuarios en BD: {$usuariosCount}\n";
    
    if ($usuariosCount > 0) {
        $usuarios = DB::table('usuarios')->get();
        foreach ($usuarios as $usuario) {
            echo "  - ID: {$usuario->id_usuario}, Nombre: {$usuario->nombre_completo}, Email: {$usuario->correo_electronico}\n";
        }
    }
    
    echo "\n=== TODO CONECTADO CORRECTAMENTE ===\n";
    echo "\n📝 Puedes probar la API en: http://localhost:8000/test.html\n";
    echo "🚀 Inicia el servidor con: php artisan serve\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
