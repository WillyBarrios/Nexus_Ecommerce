<?php

require __DIR__.'/../../vendor/autoload.php';

$app = require_once __DIR__.'/../../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== CREAR TABLA PASSWORD RESET TOKENS ===\n\n";

try {
    // Verificar si la tabla ya existe
    $tables = DB::select('SHOW TABLES');
    $tableNames = array_map(function($table) {
        return $table->{'Tables_in_nexus'};
    }, $tables);
    
    if (in_array('password_reset_tokens', $tableNames)) {
        echo "✓ Tabla password_reset_tokens ya existe\n";
    } else {
        echo "Creando tabla password_reset_tokens...\n";
        
        DB::statement("
            CREATE TABLE password_reset_tokens (
                email VARCHAR(255) NOT NULL,
                token VARCHAR(255) NOT NULL,
                created_at TIMESTAMP NULL,
                PRIMARY KEY (email)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
        
        echo "✓ Tabla password_reset_tokens creada exitosamente\n";
    }
    
    echo "\n=== COMPLETADO ===\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
