<?php

/**
 * Script para insertar datos de prueba
 */

require __DIR__.'/../../vendor/autoload.php';

$app = require_once __DIR__.'/../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== INSERTANDO DATOS DE PRUEBA ===\n\n";

try {
    // Verificar si ya hay datos
    $categoriasCount = DB::table('categorias')->count();
    $marcasCount = DB::table('marcas')->count();
    
    echo "Categorías actuales: $categoriasCount\n";
    echo "Marcas actuales: $marcasCount\n\n";
    
    if ($categoriasCount == 0) {
        echo "Insertando categorías de prueba...\n";
        DB::table('categorias')->insert([
            ['nombre_categoria' => 'Electrónica', 'descripcion' => 'Productos electrónicos'],
            ['nombre_categoria' => 'Ropa', 'descripcion' => 'Ropa y accesorios'],
            ['nombre_categoria' => 'Hogar', 'descripcion' => 'Artículos para el hogar'],
            ['nombre_categoria' => 'Deportes', 'descripcion' => 'Artículos deportivos'],
            ['nombre_categoria' => 'Libros', 'descripcion' => 'Libros y revistas'],
        ]);
        echo "✓ Categorías insertadas\n";
    } else {
        echo "✓ Ya hay categorías en la base de datos\n";
    }
    
    if ($marcasCount == 0) {
        echo "Insertando marcas de prueba...\n";
        DB::table('marcas')->insert([
            ['nombre_marca' => 'Samsung', 'descripcion' => 'Electrónica de calidad'],
            ['nombre_marca' => 'Nike', 'descripcion' => 'Ropa deportiva'],
            ['nombre_marca' => 'Adidas', 'descripcion' => 'Ropa y calzado deportivo'],
            ['nombre_marca' => 'Sony', 'descripcion' => 'Electrónica y entretenimiento'],
            ['nombre_marca' => 'LG', 'descripcion' => 'Electrodomésticos'],
        ]);
        echo "✓ Marcas insertadas\n";
    } else {
        echo "✓ Ya hay marcas en la base de datos\n";
    }
    
    echo "\n=== DATOS INSERTADOS EXITOSAMENTE ===\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
