<?php

/**
 * Script para insertar datos de prueba de productos
 * 
 * Este script inserta categorías, marcas y productos de ejemplo
 * para poder probar el sistema completo.
 */

require __DIR__.'/../../vendor/autoload.php';

$app = require_once __DIR__.'/../../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Producto;
use App\Models\ImagenProducto;
use App\Models\MovimientoInventario;

echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║                                                            ║\n";
echo "║          INSERTAR DATOS DE PRUEBA - PRODUCTOS             ║\n";
echo "║                    NEXUS BACKEND                           ║\n";
echo "║                                                            ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";
echo "\n";

try {
    // 1. INSERTAR CATEGORÍAS
    echo " Insertando Categorías...\n";
    
    $categorias = [
        ['nombre_categoria' => 'Electrónica', 'descripcion' => 'Dispositivos electrónicos y tecnología'],
        ['nombre_categoria' => 'Ropa', 'descripcion' => 'Ropa y accesorios de moda'],
        ['nombre_categoria' => 'Hogar', 'descripcion' => 'Artículos para el hogar'],
        ['nombre_categoria' => 'Deportes', 'descripcion' => 'Equipamiento deportivo'],
        ['nombre_categoria' => 'Libros', 'descripcion' => 'Libros y material de lectura'],
        ['nombre_categoria' => 'Juguetes', 'descripcion' => 'Juguetes y juegos'],
        ['nombre_categoria' => 'Alimentos', 'descripcion' => 'Alimentos y bebidas'],
        ['nombre_categoria' => 'Belleza', 'descripcion' => 'Productos de belleza y cuidado personal'],
    ];
    
    foreach ($categorias as $cat) {
        Categoria::firstOrCreate(
            ['nombre_categoria' => $cat['nombre_categoria']],
            $cat
        );
    }
    echo "✓ " . count($categorias) . " categorías insertadas\n\n";
    
    // 2. INSERTAR MARCAS
    echo "  Insertando Marcas...\n";
    
    $marcas = [
        ['nombre_marca' => 'Samsung', 'descripcion' => 'Tecnología y electrónica'],
        ['nombre_marca' => 'Apple', 'descripcion' => 'Dispositivos premium'],
        ['nombre_marca' => 'Nike', 'descripcion' => 'Ropa y calzado deportivo'],
        ['nombre_marca' => 'Adidas', 'descripcion' => 'Equipamiento deportivo'],
        ['nombre_marca' => 'Sony', 'descripcion' => 'Electrónica y entretenimiento'],
        ['nombre_marca' => 'LG', 'descripcion' => 'Electrodomésticos y tecnología'],
        ['nombre_marca' => 'Zara', 'descripcion' => 'Moda y accesorios'],
        ['nombre_marca' => 'HP', 'descripcion' => 'Computadoras y tecnología'],
        ['nombre_marca' => 'Dell', 'descripcion' => 'Equipos de cómputo'],
        ['nombre_marca' => 'Genérica', 'descripcion' => 'Productos sin marca específica'],
    ];
    
    foreach ($marcas as $marca) {
        Marca::firstOrCreate(
            ['nombre_marca' => $marca['nombre_marca']],
            $marca
        );
    }
    echo "✓ " . count($marcas) . " marcas insertadas\n\n";
    
    // 3. INSERTAR PRODUCTOS
    echo " Insertando Productos...\n";
    
    $productos = [
        // Electrónica
        [
            'nombre_producto' => 'Samsung Galaxy S23',
            'descripcion' => 'Smartphone de última generación con cámara de 50MP',
            'precio' => 899.99,
            'existencia' => 50,
            'categoria' => 'Electrónica',
            'marca' => 'Samsung',
            'imagenes' => ['https://via.placeholder.com/400x400?text=Galaxy+S23']
        ],
        [
            'nombre_producto' => 'iPhone 15 Pro',
            'descripcion' => 'iPhone con chip A17 Pro y cámara profesional',
            'precio' => 1199.99,
            'existencia' => 30,
            'categoria' => 'Electrónica',
            'marca' => 'Apple',
            'imagenes' => ['https://via.placeholder.com/400x400?text=iPhone+15']
        ],
        [
            'nombre_producto' => 'Laptop HP Pavilion',
            'descripcion' => 'Laptop con Intel i5, 8GB RAM, 512GB SSD',
            'precio' => 699.99,
            'existencia' => 25,
            'categoria' => 'Electrónica',
            'marca' => 'HP',
            'imagenes' => ['https://via.placeholder.com/400x400?text=HP+Pavilion']
        ],
        [
            'nombre_producto' => 'Dell XPS 13',
            'descripcion' => 'Ultrabook premium con pantalla InfinityEdge',
            'precio' => 1299.99,
            'existencia' => 15,
            'categoria' => 'Electrónica',
            'marca' => 'Dell',
            'imagenes' => ['https://via.placeholder.com/400x400?text=Dell+XPS']
        ],
        [
            'nombre_producto' => 'Smart TV LG 55"',
            'descripcion' => 'Televisor 4K UHD con webOS',
            'precio' => 599.99,
            'existencia' => 20,
            'categoria' => 'Electrónica',
            'marca' => 'LG',
            'imagenes' => ['https://via.placeholder.com/400x400?text=LG+TV']
        ],
        
        // Ropa
        [
            'nombre_producto' => 'Zapatillas Nike Air Max',
            'descripcion' => 'Zapatillas deportivas con tecnología Air',
            'precio' => 129.99,
            'existencia' => 100,
            'categoria' => 'Ropa',
            'marca' => 'Nike',
            'imagenes' => ['https://via.placeholder.com/400x400?text=Nike+Air+Max']
        ],
        [
            'nombre_producto' => 'Sudadera Adidas',
            'descripcion' => 'Sudadera con capucha, algodón premium',
            'precio' => 59.99,
            'existencia' => 80,
            'categoria' => 'Ropa',
            'marca' => 'Adidas',
            'imagenes' => ['https://via.placeholder.com/400x400?text=Adidas+Hoodie']
        ],
        [
            'nombre_producto' => 'Jeans Zara',
            'descripcion' => 'Jeans slim fit de mezclilla',
            'precio' => 49.99,
            'existencia' => 60,
            'categoria' => 'Ropa',
            'marca' => 'Zara',
            'imagenes' => ['https://via.placeholder.com/400x400?text=Zara+Jeans']
        ],
        
        // Deportes
        [
            'nombre_producto' => 'Balón de Fútbol Adidas',
            'descripcion' => 'Balón oficial tamaño 5',
            'precio' => 29.99,
            'existencia' => 150,
            'categoria' => 'Deportes',
            'marca' => 'Adidas',
            'imagenes' => ['https://via.placeholder.com/400x400?text=Soccer+Ball']
        ],
        [
            'nombre_producto' => 'Raqueta de Tenis',
            'descripcion' => 'Raqueta profesional de grafito',
            'precio' => 89.99,
            'existencia' => 40,
            'categoria' => 'Deportes',
            'marca' => 'Genérica',
            'imagenes' => ['https://via.placeholder.com/400x400?text=Tennis+Racket']
        ],
        
        // Hogar
        [
            'nombre_producto' => 'Cafetera',
            'descripcion' => 'Cafetera automática programable',
            'precio' => 79.99,
            'existencia' => 35,
            'categoria' => 'Hogar',
            'marca' => 'Genérica',
            'imagenes' => ['https://via.placeholder.com/400x400?text=Coffee+Maker']
        ],
        [
            'nombre_producto' => 'Aspiradora LG',
            'descripcion' => 'Aspiradora robot con mapeo inteligente',
            'precio' => 299.99,
            'existencia' => 20,
            'categoria' => 'Hogar',
            'marca' => 'LG',
            'imagenes' => ['https://via.placeholder.com/400x400?text=Robot+Vacuum']
        ],
    ];
    
    $productosCreados = 0;
    
    foreach ($productos as $prod) {
        $categoria = Categoria::where('nombre_categoria', $prod['categoria'])->first();
        $marca = Marca::where('nombre_marca', $prod['marca'])->first();
        
        if ($categoria && $marca) {
            $producto = Producto::firstOrCreate(
                ['nombre_producto' => $prod['nombre_producto']],
                [
                    'descripcion' => $prod['descripcion'],
                    'precio' => $prod['precio'],
                    'existencia' => $prod['existencia'],
                    'id_categoria' => $categoria->id_categoria,
                    'id_marca' => $marca->id_marca,
                    'estado' => 'activo'
                ]
            );
            
            // Agregar imágenes
            foreach ($prod['imagenes'] as $url) {
                ImagenProducto::firstOrCreate([
                    'id_producto' => $producto->id_producto,
                    'url_imagen' => $url
                ]);
            }
            
            // Registrar movimiento de inventario
            MovimientoInventario::firstOrCreate([
                'id_producto' => $producto->id_producto,
                'tipo_movimiento' => 'entrada',
                'cantidad' => $prod['existencia'],
                'descripcion' => 'Stock inicial'
            ]);
            
            $productosCreados++;
        }
    }
    
    echo "✓ " . $productosCreados . " productos insertados\n\n";
    
    // RESUMEN
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "✅ Datos insertados exitosamente\n\n";
    echo "📊 Resumen:\n";
    echo "   - Categorías: " . Categoria::count() . "\n";
    echo "   - Marcas: " . Marca::count() . "\n";
    echo "   - Productos: " . Producto::count() . "\n";
    echo "   - Imágenes: " . ImagenProducto::count() . "\n";
    echo "\n";
    echo "🚀 Ahora puedes probar la API:\n";
    echo "   GET  http://localhost:8000/api/productos\n";
    echo "   GET  http://localhost:8000/api/categorias\n";
    echo "   GET  http://localhost:8000/api/marcas\n";
    echo "\n";
    
} catch (\Exception $e) {
    echo "\n❌ Error: " . $e->getMessage() . "\n";
    echo "Archivo: " . $e->getFile() . "\n";
    echo "Línea: " . $e->getLine() . "\n\n";
}
