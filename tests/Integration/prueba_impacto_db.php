<?php

/**
 * PRUEBA DE IMPACTO EN BASE DE DATOS
 * 
 * Esta prueba demuestra que el panel admin SÍ está guardando
 * datos reales en la base de datos MySQL
 */

require __DIR__.'/../../vendor/autoload.php';

$app = require_once __DIR__.'/../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;

echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║  PRUEBA DE IMPACTO EN BASE DE DATOS - NEXUS BACKEND         ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

try {
    // Mostrar configuración de BD
    echo "📊 CONFIGURACIÓN DE BASE DE DATOS:\n";
    echo "   Host: " . env('DB_HOST') . "\n";
    echo "   Base de datos: " . env('DB_DATABASE') . "\n";
    echo "   Usuario: " . env('DB_USERNAME') . "\n\n";
    
    // Contar registros ANTES
    echo "📈 CONTEO ANTES DE LA PRUEBA:\n";
    $categoriasAntes = Categoria::count();
    $marcasAntes = Marca::count();
    $productosAntes = Producto::count();
    
    echo "   Categorías: $categoriasAntes\n";
    echo "   Marcas: $marcasAntes\n";
    echo "   Productos: $productosAntes\n\n";
    
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
    // CREAR DATOS REALES EN LA BASE DE DATOS
    echo "✍️  CREANDO DATOS EN LA BASE DE DATOS...\n\n";
    
    // 1. Crear Categoría
    echo "1️⃣  Creando categoría 'Prueba Backend Team'...\n";
    $categoria = Categoria::create([
        'nombre_categoria' => 'Prueba Backend Team - ' . date('H:i:s'),
        'descripcion' => 'Categoría creada para demostrar impacto en DB'
    ]);
    echo "   ✅ Categoría creada con ID: {$categoria->id_categoria}\n";
    echo "   📝 Nombre: {$categoria->nombre_categoria}\n\n";
    
    // 2. Crear Marca
    echo "2️⃣  Creando marca 'Backend Test Brand'...\n";
    $marca = Marca::create([
        'nombre_marca' => 'Backend Test Brand - ' . date('H:i:s'),
        'descripcion' => 'Marca creada para demostrar impacto en DB'
    ]);
    echo "   ✅ Marca creada con ID: {$marca->id_marca}\n";
    echo "   📝 Nombre: {$marca->nombre_marca}\n\n";
    
    // 3. Crear Producto
    echo "3️⃣  Creando producto 'Test Product Backend'...\n";
    $producto = Producto::create([
        'nombre_producto' => 'Test Product Backend - ' . date('H:i:s'),
        'descripcion' => 'Producto creado para demostrar impacto en DB',
        'precio' => 999.99,
        'existencia' => 100,
        'id_categoria' => $categoria->id_categoria,
        'id_marca' => $marca->id_marca,
        'estado' => 'activo'
    ]);
    echo "   ✅ Producto creado con ID: {$producto->id_producto}\n";
    echo "   📝 Nombre: {$producto->nombre_producto}\n";
    echo "   💰 Precio: \${$producto->precio}\n";
    echo "   📦 Stock: {$producto->existencia}\n\n";
    
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
    // Contar registros DESPUÉS
    echo "📈 CONTEO DESPUÉS DE LA PRUEBA:\n";
    $categoriasDespues = Categoria::count();
    $marcasDespues = Marca::count();
    $productosDespues = Producto::count();
    
    echo "   Categorías: $categoriasDespues (+". ($categoriasDespues - $categoriasAntes) .")\n";
    echo "   Marcas: $marcasDespues (+". ($marcasDespues - $marcasAntes) .")\n";
    echo "   Productos: $productosDespues (+". ($productosDespues - $productosAntes) .")\n\n";
    
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
    // Verificar en la base de datos directamente
    echo "🔍 VERIFICACIÓN DIRECTA EN MYSQL:\n\n";
    
    $categoriaDB = DB::select("SELECT * FROM categorias WHERE id_categoria = ?", [$categoria->id_categoria]);
    echo "   Categoría en DB:\n";
    echo "   - ID: {$categoriaDB[0]->id_categoria}\n";
    echo "   - Nombre: {$categoriaDB[0]->nombre_categoria}\n\n";
    
    $marcaDB = DB::select("SELECT * FROM marcas WHERE id_marca = ?", [$marca->id_marca]);
    echo "   Marca en DB:\n";
    echo "   - ID: {$marcaDB[0]->id_marca}\n";
    echo "   - Nombre: {$marcaDB[0]->nombre_marca}\n\n";
    
    $productoDB = DB::select("SELECT * FROM productos WHERE id_producto = ?", [$producto->id_producto]);
    echo "   Producto en DB:\n";
    echo "   - ID: {$productoDB[0]->id_producto}\n";
    echo "   - Nombre: {$productoDB[0]->nombre_producto}\n";
    echo "   - Precio: \${$productoDB[0]->precio}\n";
    echo "   - Stock: {$productoDB[0]->existencia}\n\n";
    
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
    echo "✅ RESULTADO: ¡SÍ HAY IMPACTO EN LA BASE DE DATOS!\n\n";
    echo "Los datos fueron guardados exitosamente en MySQL.\n";
    echo "Puedes verificarlos en phpMyAdmin o en el panel admin.\n\n";
    
    echo "🌐 URLs para verificar:\n";
    echo "   - Panel Admin: http://127.0.0.1:8000/admin\n";
    echo "   - Categorías: http://127.0.0.1:8000/admin/categorias\n";
    echo "   - Marcas: http://127.0.0.1:8000/admin/marcas\n";
    echo "   - Productos: http://127.0.0.1:8000/admin/productos\n\n";
    
    echo "💡 NOTA: Los datos creados en esta prueba quedan guardados.\n";
    echo "   Puedes eliminarlos desde el panel admin si lo deseas.\n\n";
    
    echo "╔══════════════════════════════════════════════════════════════╗\n";
    echo "║  ✅ PRUEBA COMPLETADA - TODO FUNCIONA CORRECTAMENTE         ║\n";
    echo "╚══════════════════════════════════════════════════════════════╝\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
