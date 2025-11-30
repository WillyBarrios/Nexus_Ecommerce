# Guía de Pruebas - Sistema de Autenticación Nexus

Esta guía te muestra cómo probar el sistema de autenticación de tres formas diferentes.

## 📋 Tabla de Contenidos

1. [Prueba Automática con Script PHP](#1-prueba-automática-con-script-php)
2. [Prueba Manual con Postman/Thunder Client](#2-prueba-manual-con-postmanthunder-client)
3. [Prueba con cURL desde Terminal](#3-prueba-con-curl-desde-terminal)

---

## 1. Prueba Automática con Script PHP

### Paso 1: Iniciar el servidor de desarrollo

```bash
cd nexus-backend
php artisan serve
```

El servidor se iniciará en `http://127.0.0.1:8000`

### Paso 2: Ejecutar el script de prueba

En otra terminal, ejecuta:

```bash
php test_flujo_completo.php
```

Este script probará automáticamente:
- ✓ Registro de usuario
- ✓ Acceso a rutas protegidas
- ✓ Logout
- ✓ Login
- ✓ Recuperación de contraseña
- ✓ Restablecimiento de contraseña
- ✓ Verificaciones de seguridad

---

## 2. Prueba Manual con Postman/Thunder Client

### Configuración Inicial

**URL Base:** `http://127.0.0.1:8000/api`

### 2.1. Registro de Usuario

**Endpoint:** `POST /register`

**Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Body (JSON):**
```json
{
  "name": "Juan Pérez",
  "email": "juan@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Respuesta Esperada (201):**
```json
{
  "success": true,
  "message": "Usuario registrado exitosamente",
  "data": {
    "user": {
      "id": 1,
      "name": "Juan Pérez",
      "email": "juan@example.com",
      "created_at": "2025-11-19T20:00:00.000000Z",
      "updated_at": "2025-11-19T20:00:00.000000Z"
    },
    "token": "1|abc123def456..."
  }
}
```

**⚠️ IMPORTANTE:** Guarda el `token` de la respuesta, lo necesitarás para las siguientes pruebas.

---

### 2.2. Login

**Endpoint:** `POST /login`

**Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Body (JSON):**
```json
{
  "email": "juan@example.com",
  "password": "password123"
}
```

**Respuesta Esperada (200):**
```json
{
  "success": true,
  "message": "Inicio de sesión exitoso",
  "data": {
    "user": {
      "id": 1,
      "name": "Juan Pérez",
      "email": "juan@example.com"
    },
    "token": "2|xyz789ghi012..."
  }
}
```

---

### 2.3. Obtener Datos del Usuario Autenticado

**Endpoint:** `GET /user`

**Headers:**
```
Content-Type: application/json
Accept: application/json
Authorization: Bearer {TU_TOKEN_AQUI}
```

**Respuesta Esperada (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Juan Pérez",
    "email": "juan@example.com",
    "created_at": "2025-11-19T20:00:00.000000Z",
    "updated_at": "2025-11-19T20:00:00.000000Z"
  }
}
```

---

### 2.4. Logout

**Endpoint:** `POST /logout`

**Headers:**
```
Content-Type: application/json
Accept: application/json
Authorization: Bearer {TU_TOKEN_AQUI}
```

**Respuesta Esperada (200):**
```json
{
  "success": true,
  "message": "Sesión cerrada exitosamente"
}
```

---

### 2.5. Solicitar Recuperación de Contraseña

**Endpoint:** `POST /password/forgot`

**Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Body (JSON):**
```json
{
  "email": "juan@example.com"
}
```

**Respuesta Esperada (200):**
```json
{
  "success": true,
  "message": "Si el email existe, recibirás un enlace de recuperación",
  "data": {
    "token": "abc123def456..."
  }
}
```

**⚠️ IMPORTANTE:** Guarda el `token` de la respuesta para el siguiente paso.

---

### 2.6. Restablecer Contraseña

**Endpoint:** `POST /password/reset`

**Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Body (JSON):**
```json
{
  "email": "juan@example.com",
  "token": "abc123def456...",
  "password": "newpassword123",
  "password_confirmation": "newpassword123"
}
```

**Respuesta Esperada (200):**
```json
{
  "success": true,
  "message": "Contraseña restablecida exitosamente"
}
```

---

## 3. Prueba con cURL desde Terminal

### 3.1. Registro

```bash
curl -X POST http://127.0.0.1:8000/api/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{\"name\":\"Juan Pérez\",\"email\":\"juan@example.com\",\"password\":\"password123\",\"password_confirmation\":\"password123\"}"
```

### 3.2. Login

```bash
curl -X POST http://127.0.0.1:8000/api/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{\"email\":\"juan@example.com\",\"password\":\"password123\"}"
```

### 3.3. Obtener Usuario (con token)

```bash
curl -X GET http://127.0.0.1:8000/api/user \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer TU_TOKEN_AQUI"
```

### 3.4. Logout

```bash
curl -X POST http://127.0.0.1:8000/api/logout \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer TU_TOKEN_AQUI"
```

### 3.5. Recuperación de Contraseña

```bash
curl -X POST http://127.0.0.1:8000/api/password/forgot \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{\"email\":\"juan@example.com\"}"
```

### 3.6. Restablecer Contraseña

```bash
curl -X POST http://127.0.0.1:8000/api/password/reset \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{\"email\":\"juan@example.com\",\"token\":\"TOKEN_DE_RECUPERACION\",\"password\":\"newpassword123\",\"password_confirmation\":\"newpassword123\"}"
```

---

## 📝 Casos de Error a Probar

### Error de Validación (422)

**Registro con email inválido:**
```json
{
  "name": "Test",
  "email": "email-invalido",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Respuesta:**
```json
{
  "success": false,
  "message": "Los datos proporcionados no son válidos",
  "errors": {
    "email": ["El email debe tener un formato válido."]
  }
}
```

### Error de Autenticación (401)

**Login con credenciales incorrectas:**
```json
{
  "email": "juan@example.com",
  "password": "contraseña_incorrecta"
}
```

**Respuesta:**
```json
{
  "success": false,
  "message": "Las credenciales proporcionadas son incorrectas"
}
```

### Error de Ruta No Encontrada (404)

**GET /api/ruta-inexistente**

**Respuesta:**
```json
{
  "success": false,
  "message": "Ruta no encontrada"
}
```

---

## 🎯 Flujo Completo Recomendado

1. **Registro** → Obtén el token
2. **Acceder a /user** → Verifica que el token funciona
3. **Logout** → Invalida el token
4. **Intentar acceder a /user** → Debe fallar con 401
5. **Login** → Obtén un nuevo token
6. **Solicitar recuperación** → Obtén token de recuperación
7. **Restablecer contraseña** → Cambia la contraseña
8. **Login con nueva contraseña** → Verifica que funciona
9. **Intentar login con contraseña antigua** → Debe fallar con 401

---

## 🔧 Solución de Problemas

### El servidor no inicia

```bash
# Verificar que no haya otro proceso usando el puerto 8000
netstat -ano | findstr :8000

# Iniciar en otro puerto
php artisan serve --port=8001
```

### Error de conexión a base de datos

```bash
# Verificar que XAMPP esté corriendo
# Verificar credenciales en .env
# Ejecutar migraciones
php artisan migrate:fresh
```

### Token inválido o expirado

- Asegúrate de copiar el token completo
- Verifica que el header Authorization tenga el formato: `Bearer {token}`
- El token se invalida después del logout

---

## 📚 Recursos Adicionales

- **Documentación de Laravel Sanctum:** https://laravel.com/docs/12.x/sanctum
- **Postman:** https://www.postman.com/downloads/
- **Thunder Client (VS Code):** Extensión disponible en VS Code Marketplace

---

¡Listo! Ahora puedes probar tu sistema de autenticación de forma completa. 🚀
