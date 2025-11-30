<?php

/**
 * Script para ejecutar pruebas de forma visual
 * 
 * Este script ejecuta las pruebas de PHPUnit y muestra
 * los resultados de forma clara para tomar capturas.
 */

echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║                                                            ║\n";
echo "║          PRUEBAS DEL SISTEMA DE AUTENTICACIÓN             ║\n";
echo "║                    NEXUS BACKEND                           ║\n";
echo "║                                                            ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";
echo "\n";

echo "📋 Información del Sistema:\n";
echo "   Framework: Laravel 12.39.0\n";
echo "   PHP Version: " . PHP_VERSION . "\n";
echo "   Base de Datos: MySQL (nexus)\n";
echo "\n";

echo "🚀 Iniciando pruebas...\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// Ejecutar PHPUnit
passthru('php artisan test tests/Feature/AuthenticationTest.php --colors=always');

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "\n✅ Pruebas completadas\n";
echo "\n";
