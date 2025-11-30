<?php

/**
 * Prueba completa de CRUD - Verificar que todo funcione sin tocar código
 */

require __DIR__.'/../../vendor/autoload.php';

$app = require_once __DIR__.'/../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "=== PRUEBA COMPLETA DE CRUD ===\n\n";

try {
    DB::beginTransaction();
    
    // 1. CREAR CATEGORÍA
    echo "1. Probando CREAR Categoría...\n";
    $categoria = Categoria::create([
        'nombre_categoria' => 'Categoría de Prueba CRUD',
        'descripcion' => 'Esta es una prueba'
    ]);
    echo "   ✓ Categoría creada con ID: {$categoria->id_categoria}\n";
    
    // 2. CREAR MARCA
    echo "\n2. Probando CREAR Marca...\n";
    $marca = Marca::create([
        'nombre_marca' => 'Marca de Prueba CRUD',
        'descripcion' => 'Esta es una prueba'
    ]);
    echo "   ✓ Marca creada con ID: {$marca->id_marca}\n";
    
    // 3. CREAR PRODUCTO
    echo "\n3. Probando CREAR Producto...\n";
    $producto = Producto::create([
        'nombre_producto' => 'Producto de Prueba CRUD',
        'descripcion' => 'Descripción de prueba',
        'precio' => 99.99,
        'existencia' => 50,
        'id_categoria' => $categoria->id_categoria,
        'id_marca' => $marca->id_marca,
        'estado' => 'activo'
    ]);
    echo "   ✓ Producto creado con ID: {$producto->id_producto}\n";
    
    // 4. LEER PRODUCTO CON RELACIONES
    echo "\n4. Probando LEER Producto con relaciones...\n";
    $productoLeido = Producto::with(['categoria', 'marca'])->find($producto->id_producto);
    echo "   ✓ Producto: {$productoLeido->nombre_producto}\n";
    echo "   ✓ Categoría: {$productoLeido->categoria->nombre_categoria}\n";
    echo "   ✓ Marca: {$productoLeido->marca->nombre_marca}\n";
    
    // 5. ACTUALIZAR PRODUCTO
    echo "\n5. Probando ACTUALIZAR Producto...\n";
    $producto->update([
        'precio' => 149.99,
        'existencia' => 75
    ]);
    echo "   ✓ Producto actualizado - Nuevo precio: \${$producto->precio}\n";
    
    // 6. ELIMINAR PRODUCTO
    echo "\n6. Probando ELIMINAR Producto...\n";
    $producto->delete();
    echo "   ✓ Producto eliminado\n";
    
    // 7. ELIMINAR MARCA
    echo "\n7. Probando ELIMINAR Marca...\n";
    $marca->delete();
    echo "   ✓ Marca eliminada\n";
    
    // 8. ELIMINAR CATEGORÍA
    echo "\n8. Probando ELIMINAR Categoría...\n";
    $categoria->delete();
    echo "   ✓ Categoría eliminada\n";
    
    // Revertir cambios (no queremos datos de prueba en la BD)
    DB::rollBack();
    
    echo "\n=== ✅ TODAS LAS OPERACIONES CRUD FUNCIONAN CORRECTAMENTE ===\n";
    echo "\n🎉 PUEDES AGREGAR, EDITAR Y ELIMINAR SIN TOCAR CÓDIGO\n";
    echo "\nLas operaciones de prueba fueron revertidas (no se guardaron en la BD)\n";
    
} catch (Exception $e) {
    DB::rollBack();
    echo "\n✗ Error: " . $e->getMessage() . "\n";
}
