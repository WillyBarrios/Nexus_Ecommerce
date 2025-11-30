# Estructura de Base de Datos - Sistema Nexus

## Descripción General

Este documento describe la estructura completa de la base de datos del Sistema de Autenticación Nexus. La base de datos está diseñada para MySQL 8.0+ y utiliza el charset `utf8mb4` con collation `utf8mb4_unicode_ci`.

**Versión de Laravel**: 12.39.0  
**Versión de Sanctum**: 4.2.0  
**Fecha de creación**: 19 de Noviembre de 2025

## Diagrama de Relaciones

```
┌─────────────────────────────────────┐
│            users                    │
├─────────────────────────────────────┤
│ id (PK)                             │
│ name                                │
│ email (UNIQUE)                      │
│ email_verified_at                   │
│ password                            │
│ remember_token                      │
│ created_at                          │
│ updated_at                          │
└─────────────────────────────────────┘
           │
           │ 1:N (polimórfica)
           ▼
┌─────────────────────────────────────┐
│    personal_access_tokens           │
├─────────────────────────────────────┤
│ id (PK)                             │
│ tokenable_type                      │
│ tokenable_id (FK → users.id)       │
│ name                                │
│ token (UNIQUE)                      │
│ abilities                           │
│ last_used_at                        │
│ expires_at                          │
│ created_at                          │
│ updated_at                          │
└─────────────────────────────────────┘

┌─────────────────────────────────────┐
│    password_reset_tokens            │
├─────────────────────────────────────┤
│ email (PK)                          │
│ token                               │
│ created_at                          │
└─────────────────────────────────────┘
```

## Tablas Principales

### 1. Tabla: `users`

**Propósito**: Almacena la información de todos los usuarios registrados en el sistema.

**Estructura**:

| Campo              | Tipo                | Nulo | Clave | Descripción                                    |
|--------------------|---------------------|------|-------|------------------------------------------------|
| id                 | bigint(20) unsigned | NO   | PRI   | Identificador único auto-incremental           |
| name               | varchar(255)        | NO   |       | Nombre completo del usuario (2-255 caracteres) |
| email              | varchar(255)        | NO   | UNI   | Email único para login (máximo 255 caracteres) |
| email_verified_at  | timestamp           | YES  |       | Fecha de verificación de email (opcional)      |
| password           | varchar(255)        | NO   |       | Contraseña hasheada con bcrypt                 |
| remember_token     | varchar(100)        | YES  |       | Token para "recordar sesión" (opcional)        |
| created_at         | timestamp           | YES  |       | Fecha y hora de registro                       |
| updated_at         | timestamp           | YES  |       | Fecha y hora de última modificación           |

**Índices**:
- PRIMARY KEY: `id`
- UNIQUE INDEX: `email`

**Reglas de Negocio**:
- El email debe ser único en todo el sistema
- La contraseña se almacena hasheada con bcrypt (nunca en texto plano)
- Los campos `created_at` y `updated_at` se gestionan automáticamente por Laravel
- El campo `name` debe tener entre 2 y 255 caracteres
- El campo `email` debe tener formato de email válido

**Ejemplo de Registro**:
```sql
INSERT INTO users (name, email, password, created_at, updated_at)
VALUES (
    'Juan Pérez',
    'juan@example.com',
    '$2y$12$abcdefghijklmnopqrstuvwxyz...', -- Hash bcrypt
    NOW(),
    NOW()
);
```

---

### 2. Tabla: `password_reset_tokens`

**Propósito**: Almacena tokens temporales para recuperación de contraseñas. Los tokens expiran después de 60 minutos.

**Estructura**:

| Campo      | Tipo         | Nulo | Clave | Descripción                           |
|------------|--------------|------|-------|---------------------------------------|
| email      | varchar(255) | NO   | PRI   | Email del usuario (clave primaria)    |
| token      | varchar(255) | NO   |       | Token hasheado para validación        |
| created_at | timestamp    | YES  |       | Fecha de creación del token           |

**Índices**:
- PRIMARY KEY: `email`

**Reglas de Negocio**:
- Solo puede existir un token activo por email
- Los tokens expiran después de 60 minutos desde `created_at`
- El token se almacena hasheado con bcrypt
- Al crear un nuevo token, se elimina el anterior (si existe)
- El token se elimina después de ser usado exitosamente

**Ejemplo de Registro**:
```sql
-- Insertar o actualizar token de recuperación
REPLACE INTO password_reset_tokens (email, token, created_at)
VALUES (
    'juan@example.com',
    '$2y$12$xyz123abc456...', -- Hash del token
    NOW()
);
```

**Validación de Expiración**:
```sql
-- Verificar si un token es válido (no expirado)
SELECT * FROM password_reset_tokens
WHERE email = 'juan@example.com'
AND created_at > DATE_SUB(NOW(), INTERVAL 60 MINUTE);
```

---

### 3. Tabla: `personal_access_tokens`

**Propósito**: Tabla de Laravel Sanctum que almacena los tokens de autenticación de usuarios. Cada token representa una sesión activa.

**Estructura**:

| Campo          | Tipo                | Nulo | Clave | Descripción                                      |
|----------------|---------------------|------|-------|--------------------------------------------------|
| id             | bigint(20) unsigned | NO   | PRI   | Identificador único del token                    |
| tokenable_type | varchar(255)        | NO   | MUL   | Tipo de modelo (ej: "App\Models\User")          |
| tokenable_id   | bigint(20) unsigned | NO   |       | ID del modelo relacionado (ej: user_id)         |
| name           | text                | NO   |       | Nombre descriptivo del token                     |
| token          | varchar(64)         | NO   | UNI   | Token hasheado único (64 caracteres)            |
| abilities      | text                | YES  |       | Permisos del token en formato JSON (opcional)    |
| last_used_at   | timestamp           | YES  |       | Fecha de último uso del token                    |
| expires_at     | timestamp           | YES  | MUL   | Fecha de expiración (opcional, indexada)        |
| created_at     | timestamp           | YES  |       | Fecha de creación del token                      |
| updated_at     | timestamp           | YES  |       | Fecha de última actualización                    |

**Índices**:
- PRIMARY KEY: `id`
- UNIQUE INDEX: `token`
- INDEX: `tokenable_type`, `tokenable_id` (compuesto)
- INDEX: `expires_at`

**Reglas de Negocio**:
- Cada token es único en todo el sistema
- Los tokens se almacenan hasheados (SHA-256)
- Un usuario puede tener múltiples tokens activos (diferentes dispositivos)
- Los tokens pueden tener fecha de expiración opcional
- El campo `last_used_at` se actualiza en cada petición autenticada
- Los tokens se eliminan al hacer logout

**Relación Polimórfica**:
- `tokenable_type`: Nombre completo de la clase del modelo (ej: "App\Models\User")
- `tokenable_id`: ID del registro en la tabla del modelo

**Ejemplo de Registro**:
```sql
INSERT INTO personal_access_tokens (
    tokenable_type,
    tokenable_id,
    name,
    token,
    abilities,
    created_at,
    updated_at
)
VALUES (
    'App\\Models\\User',
    1, -- ID del usuario
    'auth-token',
    'abc123def456...', -- Hash SHA-256 del token
    NULL, -- Sin permisos específicos
    NOW(),
    NOW()
);
```

---

## Tablas Auxiliares

### 4. Tabla: `sessions`

**Propósito**: Almacena sesiones de usuarios para compatibilidad web. No se usa en API stateless, pero se mantiene para futuras funcionalidades.

**Estructura**:

| Campo         | Tipo         | Nulo | Clave | Descripción                    |
|---------------|--------------|------|-------|--------------------------------|
| id            | varchar(255) | NO   | PRI   | ID único de la sesión          |
| user_id       | bigint(20)   | YES  | MUL   | ID del usuario (opcional)      |
| ip_address    | varchar(45)  | YES  |       | Dirección IP del usuario       |
| user_agent    | text         | YES  |       | Información del navegador      |
| payload       | longtext     | NO   |       | Datos de la sesión             |
| last_activity | int(11)      | NO   | MUL   | Última actividad (timestamp)   |

**Nota**: Esta tabla no se utiliza en el sistema de API actual, pero está disponible para futuras implementaciones web.

---

### 5. Tabla: `cache`

**Propósito**: Almacena datos en caché del sistema.

**Estructura**:

| Campo      | Tipo         | Nulo | Clave | Descripción                |
|------------|--------------|------|-------|----------------------------|
| key        | varchar(255) | NO   | PRI   | Clave única del caché      |
| value      | mediumtext   | NO   |       | Valor almacenado           |
| expiration | int(11)      | NO   |       | Timestamp de expiración    |

---

### 6. Tabla: `cache_locks`

**Propósito**: Gestiona bloqueos de caché para operaciones concurrentes.

**Estructura**:

| Campo      | Tipo         | Nulo | Clave | Descripción                |
|------------|--------------|------|-------|----------------------------|
| key        | varchar(255) | NO   | PRI   | Clave única del bloqueo    |
| owner      | varchar(255) | NO   |       | Propietario del bloqueo    |
| expiration | int(11)      | NO   |       | Timestamp de expiración    |

---

### 7. Tabla: `jobs`

**Propósito**: Cola de trabajos en segundo plano.

**Estructura**:

| Campo          | Tipo                | Nulo | Clave | Descripción                    |
|----------------|---------------------|------|-------|--------------------------------|
| id             | bigint(20) unsigned | NO   | PRI   | ID único del trabajo           |
| queue          | varchar(255)        | NO   | MUL   | Nombre de la cola              |
| payload        | longtext            | NO   |       | Datos del trabajo              |
| attempts       | tinyint(3) unsigned | NO   |       | Número de intentos             |
| reserved_at    | int(10) unsigned    | YES  |       | Timestamp de reserva           |
| available_at   | int(10) unsigned    | NO   |       | Timestamp de disponibilidad    |
| created_at     | int(10) unsigned    | NO   |       | Timestamp de creación          |

---

### 8. Tabla: `failed_jobs`

**Propósito**: Almacena trabajos que fallaron durante su ejecución.

**Estructura**:

| Campo      | Tipo                | Nulo | Clave | Descripción                |
|------------|---------------------|------|-------|----------------------------|
| id         | bigint(20) unsigned | NO   | PRI   | ID único del trabajo       |
| uuid       | varchar(255)        | NO   | UNI   | UUID único del trabajo     |
| connection | text                | NO   |       | Conexión utilizada         |
| queue      | text                | NO   |       | Cola utilizada             |
| payload    | longtext            | NO   |       | Datos del trabajo          |
| exception  | longtext            | NO   |       | Excepción generada         |
| failed_at  | timestamp           | NO   |       | Fecha del fallo            |

---

## Convenciones y Buenas Prácticas

### Nomenclatura

1. **Nombres de tablas**: En plural, minúsculas, usando guión bajo para separar palabras
   - Ejemplo: `users`, `password_reset_tokens`

2. **Nombres de columnas**: En singular, minúsculas, usando guión bajo
   - Ejemplo: `user_id`, `created_at`

3. **Claves primarias**: Siempre llamadas `id` (auto-incremental)

4. **Claves foráneas**: Formato `{tabla_singular}_id`
   - Ejemplo: `user_id`, `role_id`

5. **Timestamps**: Laravel gestiona automáticamente `created_at` y `updated_at`

### Tipos de Datos

- **IDs**: `bigint(20) unsigned` - Permite hasta 18,446,744,073,709,551,615 registros
- **Strings cortos**: `varchar(255)` - Para emails, nombres, etc.
- **Strings largos**: `text` o `longtext` - Para contenido extenso
- **Contraseñas**: `varchar(255)` - Suficiente para hash bcrypt
- **Fechas**: `timestamp` - Incluye fecha y hora
- **Booleanos**: `tinyint(1)` - 0 o 1

### Índices

- **PRIMARY KEY**: En campo `id` de cada tabla
- **UNIQUE**: En campos que deben ser únicos (email, token)
- **INDEX**: En campos usados frecuentemente en WHERE, JOIN, ORDER BY
- **FOREIGN KEY**: En relaciones entre tablas (futuras implementaciones)

### Charset y Collation

- **Charset**: `utf8mb4` - Soporta emojis y caracteres especiales
- **Collation**: `utf8mb4_unicode_ci` - Case-insensitive, compatible con MySQL 8.0

## Comandos Útiles

### Ver todas las tablas
```bash
php artisan migrate:status
```

### Ejecutar migraciones
```bash
php artisan migrate
```

### Revertir última migración
```bash
php artisan migrate:rollback
```

### Revertir todas las migraciones
```bash
php artisan migrate:reset
```

### Refrescar base de datos (eliminar y recrear)
```bash
php artisan migrate:fresh
```

### Ver estructura de una tabla (MySQL)
```sql
DESCRIBE users;
SHOW CREATE TABLE users;
```

### Ver índices de una tabla
```sql
SHOW INDEX FROM users;
```

## Seguridad

### Contraseñas

- **Nunca** almacenar contraseñas en texto plano
- Usar siempre `Hash::make()` para hashear contraseñas
- Usar `Hash::check()` para verificar contraseñas
- Laravel usa bcrypt con cost factor de 12 por defecto

### Tokens

- Los tokens de autenticación se almacenan hasheados (SHA-256)
- Los tokens de recuperación se almacenan hasheados (bcrypt)
- Los tokens deben ser generados con `Str::random(64)` o similar
- Los tokens de recuperación expiran en 60 minutos

### SQL Injection

- Laravel Eloquent previene SQL injection automáticamente
- Usar siempre query builder o Eloquent, nunca queries raw sin binding
- Si es necesario usar raw queries, usar bindings: `DB::raw('... WHERE id = ?', [$id])`

## Mantenimiento

### Limpieza de Tokens Expirados

```sql
-- Eliminar tokens de recuperación expirados (más de 60 minutos)
DELETE FROM password_reset_tokens
WHERE created_at < DATE_SUB(NOW(), INTERVAL 60 MINUTE);
```

### Limpieza de Tokens de Autenticación Expirados

```sql
-- Eliminar tokens de autenticación expirados
DELETE FROM personal_access_tokens
WHERE expires_at IS NOT NULL
AND expires_at < NOW();
```

### Limpieza de Sesiones Antiguas

```sql
-- Eliminar sesiones inactivas (más de 24 horas)
DELETE FROM sessions
WHERE last_activity < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 24 HOUR));
```

## Respaldo y Restauración

### Crear respaldo de la base de datos

```bash
# Desde línea de comandos
C:\xampp\mysql\bin\mysqldump.exe -u root nexus > backup_nexus.sql

# Con fecha
C:\xampp\mysql\bin\mysqldump.exe -u root nexus > backup_nexus_%date:~-4,4%%date:~-10,2%%date:~-7,2%.sql
```

### Restaurar base de datos desde respaldo

```bash
C:\xampp\mysql\bin\mysql.exe -u root nexus < backup_nexus.sql
```

---

**Última actualización**: 19 de Noviembre de 2025  
**Versión de Laravel**: 12.39.0  
**Versión de Sanctum**: 4.2.0  
**Versión de MySQL**: 8.0.33
