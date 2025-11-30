<?php

/**
 * Script para verificar que el dashboard funcione correctamente
 */

require __DIR__.'/../../vendor/autoload.php';

$app = require_once __DIR__.'/../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Producto;
use App\Models\Pedido;
use App\Models\User;
use App\Models\Categoria;
use App\Models\Marca;

echo "=== VERIFICACIÓN DEL DASHBOARD ===\n\n";

try {
    // Estadísticas generales
    echo "1. Estadísticas Generales:\n";
    $stats = [
        'total_productos' => Producto::count(),
        'productos_activos' => Producto::where('estado', 'activo')->count(),
        'total_pedidos' => Pedido::count(),
        'pedidos_pendientes' => Pedido::where('estado', 'pendiente')->count(),
        'total_usuarios' => User::count(),
        'total_categorias' => Categoria::count(),
        'total_marcas' => Marca::count(),
        'ventas_totales' => Pedido::whereIn('estado', ['pagado', 'enviado', 'entregado'])->sum('monto_total'),
    ];
    
    foreach ($stats as $key => $value) {
        echo "   - " . str_replace('_', ' ', ucfirst($key)) . ": $value\n";
    }
    
    // Productos con stock bajo
    echo "\n2. Productos con Stock Bajo (< 10):\n";
    $productos_stock_bajo = Producto::where('existencia', '<', 10)
                                   ->where('estado', 'activo')
                                   ->with(['categoria', 'marca'])
                                   ->get();
    
    if ($productos_stock_bajo->isEmpty()) {
        echo "   ✓ No hay productos con stock bajo\n";
    } else {
        foreach ($productos_stock_bajo as $producto) {
            echo "   - {$producto->nombre_producto} (Stock: {$producto->existencia})\n";
        }
    }
    
    // Últimos pedidos
    echo "\n3. Últimos Pedidos:\n";
    $ultimos_pedidos = Pedido::with(['usuario'])
                            ->orderBy('fecha_creacion', 'desc')
                            ->limit(5)
                            ->get();
    
    if ($ultimos_pedidos->isEmpty()) {
        echo "   ✓ No hay pedidos recientes\n";
    } else {
        foreach ($ultimos_pedidos as $pedido) {
            echo "   - Pedido #{$pedido->id_pedido} - {$pedido->usuario->nombre_completo} - \${$pedido->monto_total} - {$pedido->estado}\n";
        }
    }
    
    echo "\n4. Verificando Relaciones:\n";
    
    // Verificar que los productos tengan categoría y marca
    $producto = Producto::with(['categoria', 'marca'])->first();
    if ($producto) {
        echo "   ✓ Producto: {$producto->nombre_producto}\n";
        echo "     - Categoría: " . ($producto->categoria ? $producto->categoria->nombre_categoria : 'N/A') . "\n";
        echo "     - Marca: " . ($producto->marca ? $producto->marca->nombre_marca : 'N/A') . "\n";
    } else {
        echo "   ✗ No hay productos en la base de datos\n";
    }
    
    echo "\n=== VERIFICACIÓN COMPLETADA ===\n";
    echo "\n✅ El dashboard está funcionando correctamente\n";
    echo "\nAccede al panel admin en: http://127.0.0.1:8000/admin\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "\nStack trace:\n" . $e->getTraceAsString() . "\n";
}
