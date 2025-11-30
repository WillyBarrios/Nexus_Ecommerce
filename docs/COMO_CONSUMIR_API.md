# Cómo Consumir la API del Backend Nexus

## Información General

**URL Base:** `http://127.0.0.1:8000/api`

**Formato:** JSON

**Autenticación:** Laravel Sanctum (Bearer Token)

---

## Iniciar el Servidor

Antes de consumir la API:

```bash
cd nexus-backend
php artisan serve
```

El servidor se iniciará en: `http://127.0.0.1:8000`

---

## Endpoints Disponibles

### 1. Registro de Usuario

**POST** `/api/register`

Registra un nuevo usuario en el sistema.

**Body:**
```json
{
  "name": "Juan Pérez",
  "email": "juan@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Respuesta (201):**
```json
{
  "success": true,
  "message": "Usuario registrado exitosamente",
  "data": {
    "user": {
      "id": 1,
      "name": "Juan Pérez",
      "email": "juan@example.com",
      "created_at": "2025-11-25T02:00:00.000000Z"
    },
    "token": "1|abc123def456..."
  }
}
```

---

### 2. Iniciar Sesión

**POST** `/api/login`

Inicia sesión con email y contraseña.

**Body:**
```json
{
  "email": "juan@example.com",
  "password": "password123"
}
```

**Respuesta (200):**
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
    "token": "2|xyz789abc123..."
  }
}
```

---

### 3. Obtener Usuario Autenticado

**GET** `/api/user`

Obtiene los datos del usuario autenticado.

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Respuesta (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Juan Pérez",
    "email": "juan@example.com",
    "telefono": null,
    "direccion": null,
    "id_rol": 3
  }
}
```

---

### 4. Cerrar Sesión

**POST** `/api/logout`

Cierra la sesión actual e invalida el token.

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Respuesta (200):**
```json
{
  "success": true,
  "message": "Sesión cerrada exitosamente"
}
```

---

### 5. Recuperar Contraseña

**POST** `/api/password/forgot`

Solicita un token para recuperar la contraseña.

**Body:**
```json
{
  "email": "juan@example.com"
}
```

**Respuesta (200):**
```json
{
  "success": true,
  "message": "Si el email existe, recibirás un enlace de recuperación",
  "data": {
    "token": "abc123def456..."
  }
}
```

---

### 6. Restablecer Contraseña

**POST** `/api/password/reset`

Restablece la contraseña usando el token de recuperación.

**Body:**
```json
{
  "email": "juan@example.com",
  "token": "abc123def456...",
  "password": "nuevaPassword123",
  "password_confirmation": "nuevaPassword123"
}
```

**Respuesta (200):**
```json
{
  "success": true,
  "message": "Contraseña restablecida exitosamente"
}
```

---

## Autenticación con Token

### Cómo funciona:

1. El usuario hace login o se registra
2. El servidor retorna un token
3. El frontend guarda el token (localStorage, sessionStorage, etc.)
4. Para endpoints protegidos, envía el token en el header `Authorization`

### Formato del Header:
```
Authorization: Bearer {token}
```

---

## Ejemplos de Consumo

### JavaScript (Fetch API)

#### Registro:
```javascript
async function registrarUsuario() {
  const response = await fetch('http://127.0.0.1:8000/api/register', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      name: 'Juan Pérez',
      email: 'juan@example.com',
      password: 'password123',
      password_confirmation: 'password123'
    })
  });
  
  const data = await response.json();
  
  if (data.success) {
    localStorage.setItem('token', data.data.token);
    console.log('Usuario registrado:', data.data.user);
  }
}
```

#### Login:
```javascript
async function iniciarSesion() {
  const response = await fetch('http://127.0.0.1:8000/api/login', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      email: 'juan@example.com',
      password: 'password123'
    })
  });
  
  const data = await response.json();
  
  if (data.success) {
    localStorage.setItem('token', data.data.token);
    console.log('Login exitoso');
  }
}
```

#### Obtener Usuario (con token):
```javascript
async function obtenerUsuario() {
  const token = localStorage.getItem('token');
  
  const response = await fetch('http://127.0.0.1:8000/api/user', {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${token}`
    }
  });
  
  const data = await response.json();
  console.log('Usuario:', data.data);
}
```

#### Logout:
```javascript
async function cerrarSesion() {
  const token = localStorage.getItem('token');
  
  const response = await fetch('http://127.0.0.1:8000/api/logout', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${token}`
    }
  });
  
  if (response.ok) {
    localStorage.removeItem('token');
    console.log('Sesión cerrada');
  }
}
```

---

### JavaScript (Axios)

```javascript
// Configuración base
const api = axios.create({
  baseURL: 'http://127.0.0.1:8000/api',
  headers: {
    'Content-Type': 'application/json'
  }
});

// Interceptor para agregar token automáticamente
api.interceptors.request.use(config => {
  const token = localStorage.getItem('token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

// Registro
async function registrar() {
  try {
    const response = await api.post('/register', {
      name: 'Juan Pérez',
      email: 'juan@example.com',
      password: 'password123',
      password_confirmation: 'password123'
    });
    
    localStorage.setItem('token', response.data.data.token);
  } catch (error) {
    console.error('Error:', error.response.data);
  }
}

// Login
async function login() {
  try {
    const response = await api.post('/login', {
      email: 'juan@example.com',
      password: 'password123'
    });
    
    localStorage.setItem('token', response.data.data.token);
  } catch (error) {
    console.error('Error:', error.response.data);
  }
}

// Obtener usuario
async function getUser() {
  try {
    const response = await api.get('/user');
    console.log('Usuario:', response.data.data);
  } catch (error) {
    console.error('Error:', error.response.data);
  }
}

// Logout
async function logout() {
  try {
    await api.post('/logout');
    localStorage.removeItem('token');
  } catch (error) {
    console.error('Error:', error.response.data);
  }
}
```

---

### PHP (cURL)

```php
<?php

// Registro
function registrarUsuario() {
    $url = 'http://127.0.0.1:8000/api/register';
    
    $data = [
        'name' => 'Juan Pérez',
        'email' => 'juan@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123'
    ];
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    $result = json_decode($response, true);
    
    if ($result['success']) {
        return $result['data']['token'];
    }
}

// Login
function login() {
    $url = 'http://127.0.0.1:8000/api/login';
    
    $data = [
        'email' => 'juan@example.com',
        'password' => 'password123'
    ];
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response, true);
}

// Obtener usuario (con token)
function obtenerUsuario($token) {
    $url = 'http://127.0.0.1:8000/api/user';
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token
    ]);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response, true);
}
?>
```

---

### Python (Requests)

```python
import requests

BASE_URL = 'http://127.0.0.1:8000/api'

# Registro
def registrar():
    url = f'{BASE_URL}/register'
    data = {
        'name': 'Juan Pérez',
        'email': 'juan@example.com',
        'password': 'password123',
        'password_confirmation': 'password123'
    }
    
    response = requests.post(url, json=data)
    result = response.json()
    
    if result['success']:
        token = result['data']['token']
        return token

# Login
def login():
    url = f'{BASE_URL}/login'
    data = {
        'email': 'juan@example.com',
        'password': 'password123'
    }
    
    response = requests.post(url, json=data)
    result = response.json()
    
    if result['success']:
        return result['data']['token']

# Obtener usuario
def obtener_usuario(token):
    url = f'{BASE_URL}/user'
    headers = {
        'Authorization': f'Bearer {token}'
    }
    
    response = requests.get(url, headers=headers)
    result = response.json()
    
    if result['success']:
        print(result['data'])

# Logout
def logout(token):
    url = f'{BASE_URL}/logout'
    headers = {
        'Authorization': f'Bearer {token}'
    }
    
    response = requests.post(url, headers=headers)
    result = response.json()
    
    if result['success']:
        print('Sesión cerrada')
```

---

## Probar con Postman

### Registro
- **Método:** POST
- **URL:** `http://127.0.0.1:8000/api/register`
- **Headers:** `Content-Type: application/json`
- **Body (raw JSON):**
```json
{
  "name": "Juan Pérez",
  "email": "juan@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

### Login
- **Método:** POST
- **URL:** `http://127.0.0.1:8000/api/login`
- **Headers:** `Content-Type: application/json`
- **Body (raw JSON):**
```json
{
  "email": "juan@example.com",
  "password": "password123"
}
```

### Obtener Usuario
- **Método:** GET
- **URL:** `http://127.0.0.1:8000/api/user`
- **Headers:** 
  - `Content-Type: application/json`
  - `Authorization: Bearer {tu_token}`

### Logout
- **Método:** POST
- **URL:** `http://127.0.0.1:8000/api/logout`
- **Headers:** 
  - `Content-Type: application/json`
  - `Authorization: Bearer {tu_token}`

---

## Errores Comunes

### Error 401: Unauthorized
**Causa:** Token inválido o no enviado

**Solución:** 
- Verifica que estás enviando el header `Authorization: Bearer {token}`
- Verifica que el token sea válido
- Haz login nuevamente para obtener un nuevo token

### Error 422: Unprocessable Entity
**Causa:** Datos de validación incorrectos

**Solución:**
- Verifica que todos los campos requeridos estén presentes
- Verifica que el email tenga formato válido
- Verifica que la contraseña tenga mínimo 8 caracteres
- Verifica que `password_confirmation` coincida con `password`

### Error 500: Internal Server Error
**Causa:** Error del servidor

**Solución:**
- Verifica que el servidor esté corriendo
- Verifica que la base de datos esté conectada
- Revisa los logs: `storage/logs/laravel.log`

---

## Flujo Típico de Uso

```
1. Usuario se registra → Recibe token
2. Frontend guarda token en localStorage
3. Usuario navega por la app
4. Para cada petición protegida, envía el token
5. Usuario hace logout → Token se invalida
6. Frontend elimina token de localStorage
```

---

## Notas Importantes

- Los tokens no expiran automáticamente
- Se invalidan al hacer logout
- En producción, usa HTTPS
- La API tiene límite de 60 peticiones por minuto
- Los usuarios registrados tienen rol de "Cliente" (id_rol: 3) por defecto

---

Versión: 1.0  
Última actualización: Noviembre 2025
