<?php

/**
 * DEMOSTRACIÓN VISUAL DE IMPACTO EN BASE DE DATOS
 * 
 * Este script muestra en tiempo real cómo los datos se guardan en MySQL
 */

require __DIR__.'/../../vendor/autoload.php';

$app = require_once __DIR__.'/../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;

function imprimirLinea($char = "═", $length = 70) {
    echo str_repeat($char, $length) . "\n";
}

function imprimirTitulo($texto) {
    imprimirLinea();
    echo "  " . $texto . "\n";
    imprimirLinea();
}

echo "\n";
imprimirTitulo("🎬 DEMOSTRACIÓN VISUAL - IMPACTO EN BASE DE DATOS");
echo "\n";

try {
    // Paso 1: Mostrar estado inicial
    echo "📊 PASO 1: ESTADO INICIAL DE LA BASE DE DATOS\n\n";
    
    $categoriasInicial = Categoria::count();
    $marcasInicial = Marca::count();
    $productosInicial = Producto::count();
    
    echo "   Categorías en DB: $categoriasInicial\n";
    echo "   Marcas en DB: $marcasInicial\n";
    echo "   Productos en DB: $productosInicial\n\n";
    
    sleep(1);
    
    // Paso 2: Crear categoría
    imprimirLinea("─");
    echo "\n✍️  PASO 2: CREANDO CATEGORÍA EN MYSQL...\n\n";
    
    $timestamp = date('Y-m-d H:i:s');
    $categoria = Categoria::create([
        'nombre_categoria' => "Demo Visual - $timestamp",
        'descripcion' => 'Categoría de demostración'
    ]);
    
    echo "   ⏳ Guardando en MySQL...\n";
    sleep(1);
    echo "   ✅ ¡Guardado exitosamente!\n\n";
    echo "   📝 Detalles:\n";
    echo "      - ID asignado por MySQL: {$categoria->id_categoria}\n";
    echo "      - Nombre: {$categoria->nombre_categoria}\n";
    echo "      - Tabla: categorias\n\n";
    
    // Verificar en DB
    $verificar = DB::select("SELECT * FROM categorias WHERE id_categoria = ?", [$categoria->id_categoria]);
    echo "   🔍 Verificación directa en MySQL:\n";
    echo "      - Registro encontrado: " . (count($verificar) > 0 ? "SÍ ✅" : "NO ❌") . "\n";
    echo "      - Nombre en DB: {$verificar[0]->nombre_categoria}\n\n";
    
    sleep(1);
    
    // Paso 3: Crear marca
    imprimirLinea("─");
    echo "\n✍️  PASO 3: CREANDO MARCA EN MYSQL...\n\n";
    
    $marca = Marca::create([
        'nombre_marca' => "Demo Brand - $timestamp",
        'descripcion' => 'Marca de demostración'
    ]);
    
    echo "   ⏳ Guardando en MySQL...\n";
    sleep(1);
    echo "   ✅ ¡Guardado exitosamente!\n\n";
    echo "   📝 Detalles:\n";
    echo "      - ID asignado por MySQL: {$marca->id_marca}\n";
    echo "      - Nombre: {$marca->nombre_marca}\n";
    echo "      - Tabla: marcas\n\n";
    
    sleep(1);
    
    // Paso 4: Crear producto
    imprimirLinea("─");
    echo "\n✍️  PASO 4: CREANDO PRODUCTO EN MYSQL...\n\n";
    
    $producto = Producto::create([
        'nombre_producto' => "Demo Product - $timestamp",
        'descripcion' => 'Producto de demostración',
        'precio' => 1499.99,
        'existencia' => 75,
        'id_categoria' => $categoria->id_categoria,
        'id_marca' => $marca->id_marca,
        'estado' => 'activo'
    ]);
    
    echo "   ⏳ Guardando en MySQL...\n";
    sleep(1);
    echo "   ✅ ¡Guardado exitosamente!\n\n";
    echo "   📝 Detalles:\n";
    echo "      - ID asignado por MySQL: {$producto->id_producto}\n";
    echo "      - Nombre: {$producto->nombre_producto}\n";
    echo "      - Precio: \${$producto->precio}\n";
    echo "      - Stock: {$producto->existencia}\n";
    echo "      - Categoría ID: {$producto->id_categoria}\n";
    echo "      - Marca ID: {$producto->id_marca}\n";
    echo "      - Tabla: productos\n\n";
    
    sleep(1);
    
    // Paso 5: Mostrar estado final
    imprimirLinea("─");
    echo "\n📊 PASO 5: ESTADO FINAL DE LA BASE DE DATOS\n\n";
    
    $categoriasFinal = Categoria::count();
    $marcasFinal = Marca::count();
    $productosFinal = Producto::count();
    
    echo "   Categorías en DB: $categoriasFinal (+" . ($categoriasFinal - $categoriasInicial) . ")\n";
    echo "   Marcas en DB: $marcasFinal (+" . ($marcasFinal - $marcasInicial) . ")\n";
    echo "   Productos en DB: $productosFinal (+" . ($productosFinal - $productosInicial) . ")\n\n";
    
    sleep(1);
    
    // Paso 6: Verificación con JOIN
    imprimirLinea("─");
    echo "\n🔗 PASO 6: VERIFICANDO RELACIONES EN MYSQL\n\n";
    
    $productoCompleto = DB::select("
        SELECT 
            p.id_producto,
            p.nombre_producto,
            p.precio,
            p.existencia,
            c.nombre_categoria,
            m.nombre_marca
        FROM productos p
        INNER JOIN categorias c ON p.id_categoria = c.id_categoria
        INNER JOIN marcas m ON p.id_marca = m.id_marca
        WHERE p.id_producto = ?
    ", [$producto->id_producto]);
    
    if (count($productoCompleto) > 0) {
        $p = $productoCompleto[0];
        echo "   ✅ Relaciones funcionando correctamente:\n\n";
        echo "      Producto: {$p->nombre_producto}\n";
        echo "      Categoría: {$p->nombre_categoria}\n";
        echo "      Marca: {$p->nombre_marca}\n";
        echo "      Precio: \${$p->precio}\n";
        echo "      Stock: {$p->existencia}\n\n";
    }
    
    sleep(1);
    
    // Resumen final
    echo "\n";
    imprimirTitulo("✅ DEMOSTRACIÓN COMPLETADA");
    echo "\n";
    echo "🎉 RESULTADO: ¡TODO FUNCIONA PERFECTAMENTE!\n\n";
    echo "Los datos fueron guardados en la base de datos MySQL 'nexus'.\n";
    echo "Puedes verificarlos en:\n\n";
    echo "   1. phpMyAdmin: http://localhost/phpmyadmin\n";
    echo "   2. Panel Admin: http://127.0.0.1:8000/admin\n";
    echo "   3. Productos: http://127.0.0.1:8000/admin/productos\n\n";
    
    echo "📋 IDs creados en esta demostración:\n";
    echo "   - Categoría ID: {$categoria->id_categoria}\n";
    echo "   - Marca ID: {$marca->id_marca}\n";
    echo "   - Producto ID: {$producto->id_producto}\n\n";
    
    echo "💡 Estos registros quedan guardados permanentemente.\n";
    echo "   Puedes eliminarlos desde el panel admin si lo deseas.\n\n";
    
    imprimirLinea();
    echo "\n";
    
} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n\n";
}
