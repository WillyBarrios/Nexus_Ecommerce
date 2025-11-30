<?php

require __DIR__.'/../../vendor/autoload.php';

$app = require_once __DIR__.'/../../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== INSERTAR ROLES INICIALES ===\n\n";

try {
    // Verificar si ya existen roles
    $rolesExistentes = DB::table('roles')->count();
    
    if ($rolesExistentes > 0) {
        echo "✓ Ya existen {$rolesExistentes} roles en la base de datos.\n";
        echo "No es necesario insertar roles.\n";
    } else {
        // Insertar roles predeterminados
        DB::table('roles')->insert([
            [
                'nombre_rol' => 'Administrador',
                'descripcion' => 'Acceso completo al sistema'
            ],
            [
                'nombre_rol' => 'Vendedor',
                'descripcion' => 'Gestión de ventas y productos'
            ],
            [
                'nombre_rol' => 'Cliente',
                'descripcion' => 'Usuario final del sistema'
            ]
        ]);
        
        echo "✓ Roles insertados exitosamente:\n";
        echo "  1. Administrador - Acceso completo al sistema\n";
        echo "  2. Vendedor - Gestión de ventas y productos\n";
        echo "  3. Cliente - Usuario final del sistema\n";
    }
    
    echo "\n=== PROCESO COMPLETADO ===\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
