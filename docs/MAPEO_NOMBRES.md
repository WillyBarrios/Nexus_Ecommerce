# 🔄 Mapeo de Nombres - API vs Base de Datos

## 📋 Resumen

Este documento explica cómo los nombres de campos en la API (en inglés) se mapean a los nombres de columnas en la base de datos (en español).

---

## 🗄️ Tabla: usuarios

### Mapeo de Campos

| API (Request/Response) | Base de Datos | Tipo | Descripción |
|------------------------|---------------|------|-------------|
| `id` | `id_usuario` | INT | ID único del usuario |
| `name` | `nombre_completo` | VARCHAR(150) | Nombre completo |
| `email` | `correo_electronico` | VARCHAR(150) | Email único |
| `password` | `contrasena` | VARCHAR(255) | Contraseña hasheada |
| `telefono` | `telefono` | VARCHAR(30) | Teléfono (opcional) |
| `direccion` | `direccion` | VARCHAR(255) | Dirección (opcional) |
| `id_rol` | `id_rol` | INT | ID del rol |
| `created_at` | `fecha_creacion` | DATETIME | Fecha de creación |
| `updated_at` | `fecha_actualizacion` | DATETIME | Fecha de actualización |

---

## 📝 Ejemplos

### Ejemplo 1: Registro de Usuario

**Request JSON (API):**
```json
{
  "name": "Juan Pérez",
  "email": "juan@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Se guarda en BD como:**
```sql
INSERT INTO usuarios (
    nombre_completo,
    correo_electronico,
    contrasena,
    id_rol,
    fecha_creacion
) VALUES (
    'Juan Pérez',
    'juan@example.com',
    '$2y$10$...',  -- hasheado
    3,              -- Cliente
    NOW()
);
```

**Response JSON (API):**
```json
{
  "success": true,
  "message": "Usuario registrado exitosamente",
  "data": {
    "user": {
      "id": 1,
      "name": "Juan Pérez",
      "email": "juan@example.com",
      "telefono": null,
      "direccion": null,
      "id_rol": 3,
      "created_at": "2025-11-21T01:56:55.000000Z",
      "updated_at": "2025-11-21T01:56:55.000000Z"
    },
    "token": "1|abc123..."
  }
}
```

---

### Ejemplo 2: Login

**Request JSON (API):**
```json
{
  "email": "juan@example.com",
  "password": "password123"
}
```

**Consulta en BD:**
```sql
SELECT * FROM usuarios 
WHERE correo_electronico = 'juan@example.com';
```

**Verificación de contraseña:**
```php
Hash::check($request->password, $user->contrasena)
```

---

## 🔧 Implementación Técnica

### Modelo User.php

El modelo `User` maneja automáticamente el mapeo:

```php
class User extends Authenticatable
{
    // Configuración de tabla
    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_actualizacion';

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'nombre_completo',
        'correo_electronico',
        'contrasena',
        'telefono',
        'direccion',
        'id_rol',
    ];

    // Serialización a JSON (convierte nombres a inglés)
    public function toArray()
    {
        return [
            'id' => $this->id_usuario,
            'name' => $this->nombre_completo,
            'email' => $this->correo_electronico,
            'telefono' => $this->telefono,
            'direccion' => $this->direccion,
            'id_rol' => $this->id_rol,
            'created_at' => $this->fecha_creacion,
            'updated_at' => $this->fecha_actualizacion,
        ];
    }
}
```

---

### Controladores

Los controladores usan los nombres de BD internamente:

```php
// AuthController.php - Registro
$user = User::create([
    'nombre_completo' => $request->name,      // API → BD
    'correo_electronico' => $request->email,  // API → BD
    'contrasena' => $request->password,       // API → BD
    'id_rol' => 3,
]);

// AuthController.php - Login
$user = User::where('correo_electronico', $request->email)->first();

if (!Hash::check($request->password, $user->contrasena)) {
    // Contraseña incorrecta
}
```

---

## 🔍 Validaciones

### RegisterRequest.php

```php
public function rules(): array
{
    return [
        'name' => 'required|string|min:2|max:255',
        'email' => 'required|email|unique:usuarios,correo_electronico|max:255',
        'password' => 'required|string|min:8|confirmed',
    ];
}
```

**Nota:** La validación `unique` usa el nombre real de la tabla y columna:
- `unique:usuarios,correo_electronico` ✅ Correcto
- `unique:users,email` ❌ Incorrecto

---

## 📊 Flujo Completo

```
1. Cliente envía JSON con nombres en inglés
   { "name": "Juan", "email": "juan@example.com", "password": "..." }
   ↓
2. Laravel valida con RegisterRequest
   ↓
3. Controlador mapea a nombres en español
   nombre_completo = "Juan"
   correo_electronico = "juan@example.com"
   contrasena = "..." (hasheado)
   ↓
4. Se guarda en tabla 'usuarios' con nombres en español
   ↓
5. Modelo serializa a JSON con nombres en inglés
   { "id": 1, "name": "Juan", "email": "juan@example.com", ... }
   ↓
6. Cliente recibe JSON con nombres en inglés
```

---

## ✅ Ventajas de Este Enfoque

1. **API en inglés** - Estándar internacional para APIs REST
2. **Base de datos en español** - Más clara para el equipo de desarrollo
3. **Compatibilidad** - Funciona con librerías y frameworks estándar
4. **Mantenibilidad** - Cambios en BD no afectan la API
5. **Documentación clara** - Cada capa tiene su propio vocabulario

---

## 🎯 Puntos Clave

- ✅ La API siempre usa nombres en inglés (`name`, `email`, `password`)
- ✅ La base de datos usa nombres en español (`nombre_completo`, `correo_electronico`, `contrasena`)
- ✅ El modelo `User` hace el mapeo automáticamente
- ✅ Los controladores usan nombres de BD internamente
- ✅ Las validaciones usan nombres de BD para `unique`
- ✅ Las respuestas JSON usan nombres en inglés

---

## 📚 Referencias

- Modelo: `app/Models/User.php`
- Controladores: `app/Http/Controllers/Api/`
- Validaciones: `app/Http/Requests/`
- Documentación completa: `DOCUMENTACION_COMPLETA.md`

---

**Versión:** 1.0  
**Fecha:** Noviembre 2025
