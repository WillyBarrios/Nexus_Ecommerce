<?php
/**
 * Script para actualizar las rutas de autoload en todos los tests
 */

$directorios = [
    __DIR__ . '/Integration',
    __DIR__ . '/setup'
];

foreach ($directorios as $dir) {
    $archivos = glob($dir . '/*.php');
    
    foreach ($archivos as $archivo) {
        $contenido = file_get_contents($archivo);
        
        // Actualizar rutas de autoload
        $contenido = str_replace(
            "require __DIR__.'/vendor/autoload.php';",
            "require __DIR__.'/../../vendor/autoload.php';",
            $contenido
        );
        
        $contenido = str_replace(
            "require_once __DIR__.'/vendor/autoload.php';",
            "require_once __DIR__.'/../../vendor/autoload.php';",
            $contenido
        );
        
        // Actualizar rutas de bootstrap
        $contenido = str_replace(
            "require_once __DIR__.'/bootstrap/app.php';",
            "require_once __DIR__.'/../../bootstrap/app.php';",
            $contenido
        );
        
        file_put_contents($archivo, $contenido);
        echo "✅ Actualizado: " . basename($archivo) . "\n";
    }
}

echo "\n✅ Todas las rutas actualizadas correctamente\n";
?>
