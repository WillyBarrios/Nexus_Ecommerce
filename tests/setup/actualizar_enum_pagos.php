<?php

/**
 * Actualizar ENUM de metodo_pago para incluir 'stripe'
 */

require __DIR__.'/../../vendor/autoload.php';

$app = require_once __DIR__.'/../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║     ACTUALIZAR TABLA PAGOS PARA INCLUIR STRIPE              ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

try {
    echo "🔧 Actualizando columna metodo_pago...\n\n";
    
    DB::statement("
        ALTER TABLE pagos 
        MODIFY COLUMN metodo_pago 
        ENUM('tarjeta','efectivo','transferencia','paypal','stripe') 
        NOT NULL
    ");
    
    echo "✅ Columna actualizada exitosamente\n\n";
    
    echo "📊 Métodos de pago ahora soportados:\n";
    echo "   - tarjeta\n";
    echo "   - efectivo\n";
    echo "   - transferencia\n";
    echo "   - paypal\n";
    echo "   - stripe ← NUEVO\n\n";
    
    echo "╔══════════════════════════════════════════════════════════════╗\n";
    echo "║  ✅ ACTUALIZACIÓN COMPLETADA                                ║\n";
    echo "╚══════════════════════════════════════════════════════════════╝\n";
    
} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n\n";
}
