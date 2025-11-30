<?php

require __DIR__.'/../../vendor/autoload.php';

$app = require_once __DIR__.'/../../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TEST DE CONEXIÓN A BASE DE DATOS NEXUS ===\n\n";

try {
    // Probar conexión
    $dbName = DB::connection()->getDatabaseName();
    echo "✓ Conectado a la base de datos: {$dbName}\n\n";
    
    // Listar todas las tablas
    $tables = DB::select('SHOW TABLES');
    echo "✓ Tablas encontradas:\n";
    foreach ($tables as $table) {
        $tableName = $table->{'Tables_in_' . $dbName};
        echo "  - {$tableName}\n";
    }
    
    echo "\n✓ Conteo de registros por tabla:\n";
    
    // Contar registros en cada tabla
    $tablesToCheck = ['roles', 'usuarios', 'categorias', 'marcas', 'productos', 
                      'carritos', 'pedidos', 'pagos'];
    
    foreach ($tablesToCheck as $table) {
        $count = DB::table($table)->count();
        echo "  - {$table}: {$count} registros\n";
    }
    
    echo "\n=== CONEXIÓN EXITOSA ===\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
