<?php

/**
 * Script para crear base de datos de pruebas
 */

echo "=== CREAR BASE DE DATOS DE PRUEBAS ===\n\n";

try {
    // Conectar a MySQL sin seleccionar base de datos
    $pdo = new PDO(
        'mysql:host=127.0.0.1',
        'root',
        '', // Cambia si tienes contraseña
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    echo "✓ Conectado a MySQL\n";
    
    // Crear base de datos de pruebas
    $pdo->exec("CREATE DATABASE IF NOT EXISTS nexus_testing CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✓ Base de datos 'nexus_testing' creada\n";
    
    // Usar la base de datos
    $pdo->exec("USE nexus_testing");
    
    // Leer y ejecutar el script SQL
    $sql = file_get_contents(__DIR__ . '/../Nexus.sql');
    
    // Remover la línea USE nexus; para usar nexus_testing
    $sql = str_replace('USE nexus;', '', $sql);
    
    // Ejecutar el script
    $pdo->exec($sql);
    echo "✓ Tablas creadas en nexus_testing\n";
    
    echo "\n=== BASE DE DATOS DE PRUEBAS LISTA ===\n";
    echo "\nAhora puedes ejecutar: php ejecutar_pruebas.php\n\n";
    
} catch (PDOException $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "\nSi tienes contraseña en MySQL, edita este archivo y agrégala.\n";
}
