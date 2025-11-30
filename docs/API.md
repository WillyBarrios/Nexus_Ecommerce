# 📡 Referencia Completa de API - Nexus Backend

## Base URL

```
http://127.0.0.1:8000/api
```

## Autenticación

La API utiliza **Laravel Sanctum** para autenticación basada en tokens.

### Headers Requeridos

```http
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

---

## 🔐 Autenticación

### Registrar Usuario

```http
POST /api/register
```

**Body:**
```json
{
  "nombre_completo": "Juan Pérez",
  "email": "juan@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Response 201:**
```json
{
  "success": true,
  "message": "Usuario registrado exitosamente",
  "data": {
    "user": {
      "id_usuario": 1,
      "nombre_completo": "Juan Pérez",
      "email": "juan@example.com",
      "id_rol": 3
    },
    "token": "1|abc123..."
  }
}
```

---

### Iniciar Sesión

```http
POST /api/login
```

**Body:**
```json
{
  "email": "juan@example.com",
  "password": "password123"
}
```

**Response 200:**
```json
{
  "success": true,
  "message": "Inicio de sesión exitoso",
  "data": {
    "user": {
      "id_usuario": 1,
      "nombre_completo": "Juan Pérez",
      "email": "juan@example.com"
    },
    "token": "2|xyz789..."
  }
}
```

---

### Obtener Usuario Actual

```http
GET /api/user
Authorization: Bearer {token}
```

**Response 200:**
```json
{
  "success": true,
  "data": {
    "id_usuario": 1,
    "nombre_completo": "Juan Pérez",
    "email": "juan@example.com",
    "id_rol": 3,
    "rol": {
      "id_rol": 3,
      "nombre_rol": "Cliente"
    }
  }
}
```

---

### Cerrar Sesión

```http
POST /api/logout
Authorization: Bearer {token}
```

**Response 200:**
```json
{
  "success": true,
  "message": "Sesión cerrada exitosamente"
}
```

---

## 🛍️ Productos

### Listar Productos

```http
GET /api/productos
```

**Query Parameters:**
- `categoria` (opcional): Filtrar por ID de categoría
- `marca` (opcional): Filtrar por ID de marca
- `buscar` (opcional): Buscar por nombre

**Response 200:**
```json
{
  "success": true,
  "data": [
    {
      "id_producto": 1,
      "nombre_producto": "iPhone 15 Pro",
      "descripcion": "Smartphone Apple",
      "precio": 1299.99,
      "stock": 50,
      "imagen_url": "iphone15.jpg",
      "categoria": {
        "id_categoria": 1,
        "nombre_categoria": "Electrónica"
      },
      "marca": {
        "id_marca": 1,
        "nombre_marca": "Apple"
      }
    }
  ]
}
```

---

### Ver Producto

```http
GET /api/productos/{id}
```

**Response 200:**
```json
{
  "success": true,
  "data": {
    "id_producto": 1,
    "nombre_producto": "iPhone 15 Pro",
    "descripcion": "Smartphone Apple con chip A17 Pro",
    "precio": 1299.99,
    "stock": 50,
    "imagen_url": "iphone15.jpg",
    "categoria": {...},
    "marca": {...}
  }
}
```

---

### Listar Categorías

```http
GET /api/categorias
```

**Response 200:**
```json
{
  "success": true,
  "data": [
    {
      "id_categoria": 1,
      "nombre_categoria": "Electrónica",
      "descripcion": "Productos electrónicos",
      "productos_count": 12
    }
  ]
}
```

---

### Listar Marcas

```http
GET /api/marcas
```

**Response 200:**
```json
{
  "success": true,
  "data": [
    {
      "id_marca": 1,
      "nombre_marca": "Apple",
      "descripcion": "Tecnología innovadora",
      "productos_count": 8
    }
  ]
}
```

---

## 🛒 Carrito de Compras

### Ver Carrito

```http
GET /api/carrito
Authorization: Bearer {token}
```

**Response 200:**
```json
{
  "success": true,
  "data": {
    "id_carrito": 1,
    "estado": "abierto",
    "items": [
      {
        "id_detalle": 1,
        "producto": {
          "id_producto": 1,
          "nombre_producto": "iPhone 15 Pro",
          "precio": 1299.99
        },
        "cantidad": 2,
        "precio_unitario": 1299.99,
        "subtotal": 2599.98
      }
    ],
    "total": 2599.98
  }
}
```

---

### Agregar Producto al Carrito

```http
POST /api/carrito/agregar
Authorization: Bearer {token}
```

**Body:**
```json
{
  "id_producto": 1,
  "cantidad": 2
}
```

**Response 201:**
```json
{
  "success": true,
  "message": "Producto agregado al carrito",
  "data": {
    "id_detalle": 1,
    "cantidad": 2,
    "subtotal": 2599.98
  }
}
```

---

### Actualizar Cantidad

```http
PUT /api/carrito/actualizar/{id_detalle}
Authorization: Bearer {token}
```

**Body:**
```json
{
  "cantidad": 3
}
```

**Response 200:**
```json
{
  "success": true,
  "message": "Cantidad actualizada",
  "data": {
    "id_detalle": 1,
    "cantidad": 3,
    "subtotal": 3899.97
  }
}
```

---

### Eliminar Producto del Carrito

```http
DELETE /api/carrito/eliminar/{id_detalle}
Authorization: Bearer {token}
```

**Response 200:**
```json
{
  "success": true,
  "message": "Producto eliminado del carrito"
}
```

---

### Vaciar Carrito

```http
DELETE /api/carrito/vaciar
Authorization: Bearer {token}
```

**Response 200:**
```json
{
  "success": true,
  "message": "Carrito vaciado exitosamente"
}
```

---

## 📦 Pedidos

### Listar Pedidos

```http
GET /api/pedidos
Authorization: Bearer {token}
```

**Response 200:**
```json
{
  "success": true,
  "data": [
    {
      "id_pedido": 1,
      "numero_pedido": "PED-20251130-001",
      "estado": "pendiente",
      "monto_total": 2599.98,
      "fecha_creacion": "2025-11-30 10:00:00",
      "items_count": 2
    }
  ]
}
```

---

### Ver Detalle de Pedido

```http
GET /api/pedidos/{id}
Authorization: Bearer {token}
```

**Response 200:**
```json
{
  "success": true,
  "data": {
    "id_pedido": 1,
    "numero_pedido": "PED-20251130-001",
    "estado": "pendiente",
    "monto_total": 2599.98,
    "fecha_creacion": "2025-11-30 10:00:00",
    "detalles": [
      {
        "producto": "iPhone 15 Pro",
        "cantidad": 2,
        "precio_unitario": 1299.99,
        "subtotal": 2599.98
      }
    ]
  }
}
```

---

### Crear Pedido desde Carrito

```http
POST /api/pedidos
Authorization: Bearer {token}
```

**Body:**
```json
{
  "direccion_envio": "Calle 123, Ciudad",
  "telefono": "555-1234"
}
```

**Response 201:**
```json
{
  "success": true,
  "message": "Pedido creado exitosamente",
  "data": {
    "id_pedido": 1,
    "numero_pedido": "PED-20251130-001",
    "monto_total": 2599.98,
    "estado": "pendiente"
  }
}
```

---

### Cancelar Pedido

```http
DELETE /api/pedidos/{id}
Authorization: Bearer {token}
```

**Response 200:**
```json
{
  "success": true,
  "message": "Pedido cancelado exitosamente"
}
```

---

## 💳 Pagos

### Crear Intención de Pago

```http
POST /api/pagos/crear
Authorization: Bearer {token}
```

**Body:**
```json
{
  "id_pedido": 1,
  "metodo_pago": "paypal"
}
```

**Métodos de pago disponibles:**
- `paypal`
- `stripe`
- `tarjeta`
- `efectivo`
- `transferencia`

**Response 201:**
```json
{
  "success": true,
  "message": "Pago creado exitosamente",
  "data": {
    "pago": {
      "id_pago": 1,
      "monto": 2599.98,
      "estado": "pendiente",
      "metodo_pago": "paypal"
    },
    "detalles_pago": {
      "tipo": "paypal",
      "client_id": "YOUR_PAYPAL_CLIENT_ID",
      "amount": 2599.98,
      "currency": "USD"
    }
  }
}
```

---

### Confirmar Pago

```http
POST /api/pagos/confirmar
Authorization: Bearer {token}
```

**Body:**
```json
{
  "id_pago": 1,
  "referencia_transaccion": "PAYPAL-ABC123XYZ"
}
```

**Response 200:**
```json
{
  "success": true,
  "message": "Pago confirmado exitosamente",
  "data": {
    "id_pago": 1,
    "estado": "completado",
    "referencia_transaccion": "PAYPAL-ABC123XYZ"
  }
}
```

---

### Ver Historial de Pagos

```http
GET /api/pagos
Authorization: Bearer {token}
```

**Response 200:**
```json
{
  "success": true,
  "data": [
    {
      "id_pago": 1,
      "metodo_pago": "paypal",
      "monto": 2599.98,
      "estado": "completado",
      "referencia_transaccion": "PAYPAL-ABC123XYZ",
      "fecha_creacion": "2025-11-30 10:00:00"
    }
  ]
}
```

---

### Ver Detalle de Pago

```http
GET /api/pagos/{id}
Authorization: Bearer {token}
```

**Response 200:**
```json
{
  "success": true,
  "data": {
    "id_pago": 1,
    "metodo_pago": "paypal",
    "monto": 2599.98,
    "estado": "completado",
    "referencia_transaccion": "PAYPAL-ABC123XYZ",
    "pedido": {
      "id_pedido": 1,
      "numero_pedido": "PED-20251130-001"
    }
  }
}
```

---

## ❌ Códigos de Error

### 400 Bad Request
```json
{
  "success": false,
  "message": "Datos inválidos",
  "errors": {
    "email": ["El email ya está registrado"]
  }
}
```

### 401 Unauthorized
```json
{
  "success": false,
  "message": "No autenticado"
}
```

### 403 Forbidden
```json
{
  "success": false,
  "message": "No tienes permiso para realizar esta acción"
}
```

### 404 Not Found
```json
{
  "success": false,
  "message": "Recurso no encontrado"
}
```

### 422 Unprocessable Entity
```json
{
  "success": false,
  "message": "Error de validación",
  "errors": {
    "campo": ["Mensaje de error"]
  }
}
```

### 500 Internal Server Error
```json
{
  "success": false,
  "message": "Error interno del servidor"
}
```

---

## 📝 Notas Importantes

1. **Autenticación**: Todos los endpoints marcados con "Authorization: Bearer {token}" requieren autenticación
2. **Formato de Fecha**: Todas las fechas están en formato `YYYY-MM-DD HH:MM:SS`
3. **Moneda**: Todos los precios están en USD con 2 decimales
4. **Paginación**: Los listados retornan todos los resultados (sin paginación por ahora)
5. **Rate Limiting**: 60 requests por minuto por IP

---

**Última actualización:** Noviembre 30, 2025  
**Versión API:** 1.0.0
