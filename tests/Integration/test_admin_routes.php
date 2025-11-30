<?php

/**
 * Script de prueba para verificar las rutas del admin
 * 
 * Este script verifica que todas las rutas del panel administrativo
 * estén correctamente configuradas y que los controladores existan.
 */

require __DIR__.'/../../vendor/autoload.php';

$app = require_once __DIR__.'/../../bootstrap/app.php';

echo "=== PRUEBA DE RUTAS DEL PANEL ADMINISTRATIVO ===\n\n";

// Verificar que los controladores existan
$controllers = [
    'DashboardController' => 'App\Http\Controllers\Admin\DashboardController',
    'ProductoAdminController' => 'App\Http\Controllers\Admin\ProductoAdminController',
    'CategoriaAdminController' => 'App\Http\Controllers\Admin\CategoriaAdminController',
    'MarcaAdminController' => 'App\Http\Controllers\Admin\MarcaAdminController',
    'PedidoAdminController' => 'App\Http\Controllers\Admin\PedidoAdminController',
    'UsuarioAdminController' => 'App\Http\Controllers\Admin\UsuarioAdminController',
];

echo "1. Verificando controladores...\n";
foreach ($controllers as $name => $class) {
    if (class_exists($class)) {
        echo "   ✓ $name existe\n";
    } else {
        echo "   ✗ $name NO existe\n";
    }
}

echo "\n2. Verificando modelos...\n";
$models = [
    'Producto' => 'App\Models\Producto',
    'Categoria' => 'App\Models\Categoria',
    'Marca' => 'App\Models\Marca',
    'Pedido' => 'App\Models\Pedido',
    'User' => 'App\Models\User',
];

foreach ($models as $name => $class) {
    if (class_exists($class)) {
        echo "   ✓ $name existe\n";
    } else {
        echo "   ✗ $name NO existe\n";
    }
}

echo "\n3. Verificando vistas...\n";
$views = [
    'admin.dashboard',
    'admin.productos.index',
    'admin.categorias.index',
    'admin.marcas.index',
    'admin.pedidos.index',
    'admin.usuarios.index',
];

foreach ($views as $view) {
    $path = str_replace('.', '/', $view) . '.blade.php';
    $fullPath = __DIR__ . '/resources/views/' . $path;
    if (file_exists($fullPath)) {
        echo "   ✓ $view existe\n";
    } else {
        echo "   ✗ $view NO existe\n";
    }
}

echo "\n4. Verificando conexión a base de datos...\n";
try {
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    $pdo = DB::connection()->getPdo();
    echo "   ✓ Conexión a base de datos exitosa\n";
    echo "   Base de datos: " . DB::connection()->getDatabaseName() . "\n";
} catch (Exception $e) {
    echo "   ✗ Error de conexión: " . $e->getMessage() . "\n";
}

echo "\n5. Verificando tablas en base de datos...\n";
try {
    $tables = ['productos', 'categorias', 'marcas', 'pedidos', 'usuarios'];
    foreach ($tables as $table) {
        $exists = DB::select("SHOW TABLES LIKE '$table'");
        if (!empty($exists)) {
            $count = DB::table($table)->count();
            echo "   ✓ Tabla '$table' existe ($count registros)\n";
        } else {
            echo "   ✗ Tabla '$table' NO existe\n";
        }
    }
} catch (Exception $e) {
    echo "   ✗ Error al verificar tablas: " . $e->getMessage() . "\n";
}

echo "\n=== PRUEBA COMPLETADA ===\n";
