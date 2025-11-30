<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración para crear las tablas de usuarios y recuperación de contraseñas
 * 
 * Esta migración crea las tablas principales del sistema de autenticación:
 * - users: Almacena todos los usuarios registrados en el sistema
 * - password_reset_tokens: Almacena tokens temporales para recuperación de contraseñas
 * - sessions: Almacena sesiones de usuarios (para compatibilidad web)
 * 
 * @package Database\Migrations
 * @version Laravel 12.39.0
 * @date 2025-11-19
 */
return new class extends Migration
{
    /**
     * Ejecutar las migraciones
     * 
     * Crea las tablas necesarias para el sistema de autenticación.
     * Cada tabla tiene comentarios explicando el propósito de cada campo.
     * 
     * @return void
     */
    public function up(): void
    {
        // Tabla: users
        // Propósito: Almacena la información de todos los usuarios registrados en el sistema
        // Esta es la tabla principal para la autenticación y gestión de usuarios
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // ID único auto-incremental (bigint unsigned)
            $table->string('name'); // Nombre completo del usuario (varchar 255)
                                   // Validación: 2-255 caracteres
            $table->string('email')->unique(); // Email único para login (varchar 255)
                                              // Validación: formato email válido, único en el sistema
            $table->timestamp('email_verified_at')->nullable(); // Fecha de verificación de email
                                                               // NULL = email no verificado
            $table->string('password'); // Contraseña hasheada con bcrypt (varchar 255)
                                       // NUNCA se almacena en texto plano
                                       // Validación: mínimo 8 caracteres antes de hashear
            $table->rememberToken(); // Token para "recordar sesión" (varchar 100, nullable)
                                    // Usado para mantener sesión activa en navegador
            $table->timestamps(); // created_at y updated_at (timestamps)
                                 // Gestionados automáticamente por Laravel
        });

        // Tabla: password_reset_tokens
        // Propósito: Almacena tokens temporales para recuperación de contraseñas
        // Los tokens expiran después de 60 minutos desde su creación
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary(); // Email del usuario (varchar 255, clave primaria)
                                                // Solo puede existir un token activo por email
            $table->string('token'); // Token hasheado para validación (varchar 255)
                                    // Se almacena hasheado con bcrypt por seguridad
            $table->timestamp('created_at')->nullable(); // Fecha de creación del token
                                                        // Usado para calcular expiración (60 minutos)
        });

        // Tabla: sessions
        // Propósito: Almacena sesiones de usuarios para compatibilidad web
        // Nota: No se usa en API stateless, pero se mantiene para futuras funcionalidades
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary(); // ID único de la sesión (varchar 255)
            $table->foreignId('user_id')->nullable()->index(); // ID del usuario (bigint unsigned, nullable)
                                                              // NULL = sesión de invitado
                                                              // Indexado para búsquedas rápidas
            $table->string('ip_address', 45)->nullable(); // Dirección IP del usuario (varchar 45)
                                                         // Soporta IPv4 e IPv6
            $table->text('user_agent')->nullable(); // Información del navegador (text)
                                                   // User-Agent string completo
            $table->longText('payload'); // Datos de la sesión serializados (longtext)
                                        // Contiene toda la información de la sesión
            $table->integer('last_activity')->index(); // Última actividad (int, timestamp Unix)
                                                      // Indexado para limpiar sesiones antiguas
        });
    }

    /**
     * Revertir las migraciones
     * 
     * Elimina las tablas creadas en el método up().
     * Se ejecuta en orden inverso para evitar problemas de dependencias.
     * 
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
