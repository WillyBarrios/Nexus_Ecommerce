<?php

require __DIR__.'/../../vendor/autoload.php';

$app = require_once __DIR__.'/../../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== VERIFICAR TABLA SANCTUM ===\n\n";

try {
    $tables = DB::select('SHOW TABLES');
    $tableNames = array_map(function($table) {
        return $table->{'Tables_in_nexus'};
    }, $tables);
    
    if (in_array('personal_access_tokens', $tableNames)) {
        echo "✓ Tabla personal_access_tokens existe\n";
    } else {
        echo "✗ Tabla personal_access_tokens NO existe\n";
        echo "\nCreando tabla...\n";
        
        // Ejecutar migración de Sanctum
        Artisan::call('migrate', ['--path' => 'vendor/laravel/sanctum/database/migrations']);
        
        echo "✓ Tabla creada exitosamente\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
