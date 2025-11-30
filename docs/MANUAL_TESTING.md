# 🧪 Manual de Testing - Nexus Backend

## Para: Equipo de QA y Líderes Técnicos

Este documento proporciona una guía completa para probar todas las funcionalidades del sistema Nexus Backend.

---

## 📋 Checklist General

### Pre-requisitos
- [ ] Servidor XAMPP iniciado
- [ ] Base de datos `nexus` importada
- [ ] Archivo `.env` configurado correctamente
- [ ] Servidor Laravel corriendo (`php artisan serve`)
- [ ] Postman o herramienta similar instalada

---

## 🔐 Testing de Autenticación

### Test 1: Registro de Usuario

**Endpoint:** `POST /api/register`

**Datos de prueba:**
```json
{
  "nombre_completo": "Test User QA",
  "email": "testqa@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Resultado esperado:**
- ✅ Status 201
- ✅ Retorna usuario creado
- ✅ Retorna token de autenticación
- ✅ Usuario aparece en tabla `usuarios`

**Casos de error a probar:**
- Email duplicado → 422
- Password no coincide → 422
- Campos vacíos → 422

---

### Test 2: Login

**Endpoint:** `POST /api/login`

**Datos de prueba:**
```json
{
  "email": "testqa@example.com",
  "password": "password123"
}
```

**Resultado esperado:**
- ✅ Status 200
- ✅ Retorna usuario
- ✅ Retorna token válido

**Casos de error a probar:**
- Email incorrecto → 401
- Password incorrecta → 401
- Campos vacíos → 422

---

### Test 3: Obtener Usuario Actual

**Endpoint:** `GET /api/user`

**Headers:**
```
Authorization: Bearer {token_del_login}
```

**Resultado esperado:**
- ✅ Status 200
- ✅ Retorna datos del usuario autenticado
- ✅ Incluye información del rol

**Casos de error a probar:**
- Sin token → 401
- Token inválido → 401

---

### Test 4: Logout

**Endpoint:** `POST /api/logout`

**Headers:**
```
Authorization: Bearer {token}
```

**Resultado esperado:**
- ✅ Status 200
- ✅ Token se invalida
- ✅ Peticiones posteriores con ese token fallan

---

## 🛍️ Testing de Productos

### Test 5: Listar Productos

**Endpoint:** `GET /api/productos`

**Resultado esperado:**
- ✅ Status 200
- ✅ Retorna array de productos
- ✅ Cada producto incluye categoría y marca
- ✅ Precios con 2 decimales

---

### Test 6: Ver Producto Individual

**Endpoint:** `GET /api/productos/1`

**Resultado esperado:**
- ✅ Status 200
- ✅ Retorna producto completo
- ✅ Incluye relaciones (categoría, marca)

**Casos de error a probar:**
- ID inexistente → 404

---

### Test 7: Listar Categorías

**Endpoint:** `GET /api/categorias`

**Resultado esperado:**
- ✅ Status 200
- ✅ Retorna array de categorías
- ✅ Incluye contador de productos

---

### Test 8: Listar Marcas

**Endpoint:** `GET /api/marcas`

**Resultado esperado:**
- ✅ Status 200
- ✅ Retorna array de marcas
- ✅ Incluye contador de productos

---

## 🛒 Testing de Carrito

### Test 9: Ver Carrito Vacío

**Endpoint:** `GET /api/carrito`

**Headers:**
```
Authorization: Bearer {token}
```

**Resultado esperado:**
- ✅ Status 200
- ✅ Carrito con items vacíos
- ✅ Total = 0

---

### Test 10: Agregar Producto al Carrito

**Endpoint:** `POST /api/carrito/agregar`

**Headers:**
```
Authorization: Bearer {token}
```

**Datos de prueba:**
```json
{
  "id_producto": 1,
  "cantidad": 2
}
```

**Resultado esperado:**
- ✅ Status 201
- ✅ Producto agregado al carrito
- ✅ Subtotal calculado correctamente (precio × cantidad)
- ✅ Total del carrito actualizado

**Casos de error a probar:**
- Producto inexistente → 404
- Cantidad mayor que stock → 400
- Cantidad negativa → 422

---

### Test 11: Actualizar Cantidad

**Endpoint:** `PUT /api/carrito/actualizar/{id_detalle}`

**Headers:**
```
Authorization: Bearer {token}
```

**Datos de prueba:**
```json
{
  "cantidad": 5
}
```

**Resultado esperado:**
- ✅ Status 200
- ✅ Cantidad actualizada
- ✅ Subtotal recalculado
- ✅ Total del carrito actualizado

---

### Test 12: Eliminar Producto del Carrito

**Endpoint:** `DELETE /api/carrito/eliminar/{id_detalle}`

**Headers:**
```
Authorization: Bearer {token}
```

**Resultado esperado:**
- ✅ Status 200
- ✅ Producto eliminado
- ✅ Total del carrito actualizado

---

### Test 13: Vaciar Carrito

**Endpoint:** `DELETE /api/carrito/vaciar`

**Headers:**
```
Authorization: Bearer {token}
```

**Resultado esperado:**
- ✅ Status 200
- ✅ Todos los items eliminados
- ✅ Total = 0

---

## 📦 Testing de Pedidos

### Test 14: Crear Pedido desde Carrito

**Pre-requisito:** Carrito con productos

**Endpoint:** `POST /api/pedidos`

**Headers:**
```
Authorization: Bearer {token}
```

**Datos de prueba:**
```json
{
  "direccion_envio": "Calle Test 123, Ciudad QA",
  "telefono": "555-TEST"
}
```

**Resultado esperado:**
- ✅ Status 201
- ✅ Pedido creado con número único
- ✅ Estado = "pendiente"
- ✅ Monto total correcto
- ✅ Items copiados del carrito
- ✅ Carrito anterior cerrado
- ✅ Nuevo carrito creado

**Verificar en BD:**
```sql
SELECT * FROM pedidos ORDER BY id_pedido DESC LIMIT 1;
SELECT * FROM detalle_pedido WHERE id_pedido = {ultimo_id};
```

---

### Test 15: Listar Pedidos del Usuario

**Endpoint:** `GET /api/pedidos`

**Headers:**
```
Authorization: Bearer {token}
```

**Resultado esperado:**
- ✅ Status 200
- ✅ Array de pedidos del usuario
- ✅ Ordenados por fecha (más reciente primero)
- ✅ Incluye contador de items

---

### Test 16: Ver Detalle de Pedido

**Endpoint:** `GET /api/pedidos/{id}`

**Headers:**
```
Authorization: Bearer {token}
```

**Resultado esperado:**
- ✅ Status 200
- ✅ Pedido completo con todos los detalles
- ✅ Lista de productos con precios y cantidades
- ✅ Información de pago (si existe)

---

### Test 17: Cancelar Pedido

**Endpoint:** `DELETE /api/pedidos/{id}`

**Headers:**
```
Authorization: Bearer {token}
```

**Resultado esperado:**
- ✅ Status 200
- ✅ Estado cambia a "cancelado"
- ✅ Solo se puede cancelar si estado = "pendiente"

**Casos de error a probar:**
- Cancelar pedido pagado → 400
- Cancelar pedido de otro usuario → 403

---

## 💳 Testing de Pagos

### Test 18: Crear Intención de Pago (PayPal)

**Pre-requisito:** Pedido creado

**Endpoint:** `POST /api/pagos/crear`

**Headers:**
```
Authorization: Bearer {token}
```

**Datos de prueba:**
```json
{
  "id_pedido": 1,
  "metodo_pago": "paypal"
}
```

**Resultado esperado:**
- ✅ Status 201
- ✅ Pago creado con estado "pendiente"
- ✅ Retorna detalles para PayPal (client_id, amount)
- ✅ Monto coincide con el pedido

**Verificar en BD:**
```sql
SELECT * FROM pagos WHERE id_pedido = 1;
```

---

### Test 19: Crear Intención de Pago (Stripe)

**Endpoint:** `POST /api/pagos/crear`

**Datos de prueba:**
```json
{
  "id_pedido": 1,
  "metodo_pago": "stripe"
}
```

**Resultado esperado:**
- ✅ Status 201
- ✅ Retorna detalles para Stripe (publishable_key, amount)

---

### Test 20: Confirmar Pago

**Endpoint:** `POST /api/pagos/confirmar`

**Headers:**
```
Authorization: Bearer {token}
```

**Datos de prueba:**
```json
{
  "id_pago": 1,
  "referencia_transaccion": "TEST-PAYPAL-ABC123"
}
```

**Resultado esperado:**
- ✅ Status 200
- ✅ Estado del pago = "completado"
- ✅ Referencia guardada
- ✅ Estado del pedido = "pagado"

**Verificar en BD:**
```sql
SELECT * FROM pagos WHERE id_pago = 1;
SELECT estado FROM pedidos WHERE id_pedido = 1;
```

---

### Test 21: Ver Historial de Pagos

**Endpoint:** `GET /api/pagos`

**Headers:**
```
Authorization: Bearer {token}
```

**Resultado esperado:**
- ✅ Status 200
- ✅ Array de pagos del usuario
- ✅ Incluye información del pedido

---

## 🎨 Testing del Panel Admin

### Test 22: Acceder al Dashboard

**URL:** `http://127.0.0.1:8000/admin`

**Resultado esperado:**
- ✅ Página carga correctamente
- ✅ Muestra estadísticas:
  - Total de productos
  - Total de pedidos
  - Total de usuarios
  - Ingresos totales
- ✅ Muestra últimos pedidos

---

### Test 23: CRUD de Productos

**Crear Producto:**
1. Ir a `/admin/productos`
2. Click en "Nuevo Producto"
3. Llenar formulario
4. Guardar

**Resultado esperado:**
- ✅ Producto aparece en la lista
- ✅ Producto existe en BD
- ✅ Validaciones funcionan

**Editar Producto:**
1. Click en "Editar"
2. Modificar datos
3. Guardar

**Resultado esperado:**
- ✅ Cambios se reflejan
- ✅ BD actualizada

**Eliminar Producto:**
1. Click en "Eliminar"
2. Confirmar

**Resultado esperado:**
- ✅ Producto eliminado
- ✅ No aparece en lista

---

### Test 24: CRUD de Categorías

**URL:** `http://127.0.0.1:8000/admin/categorias`

**Probar:**
- ✅ Crear categoría
- ✅ Editar categoría
- ✅ Eliminar categoría (solo si no tiene productos)
- ✅ Validación de nombre único

---

### Test 25: CRUD de Marcas

**URL:** `http://127.0.0.1:8000/admin/marcas`

**Probar:**
- ✅ Crear marca
- ✅ Editar marca
- ✅ Eliminar marca (solo si no tiene productos)
- ✅ Validación de nombre único

---

### Test 26: Gestión de Pedidos

**URL:** `http://127.0.0.1:8000/admin/pedidos`

**Probar:**
- ✅ Ver lista de pedidos
- ✅ Filtrar por estado
- ✅ Ver detalle de pedido
- ✅ Actualizar estado de pedido
- ✅ Ver productos del pedido

---

### Test 27: Gestión de Usuarios

**URL:** `http://127.0.0.1:8000/admin/usuarios`

**Probar:**
- ✅ Ver lista de usuarios
- ✅ Filtrar por rol
- ✅ Crear usuario
- ✅ Editar usuario
- ✅ Eliminar usuario
- ✅ Validación de email único

---

## 🔒 Testing de Seguridad

### Test 28: Protección de Rutas

**Probar:**
- ✅ Acceder a `/api/carrito` sin token → 401
- ✅ Acceder a `/api/pedidos` sin token → 401
- ✅ Ver pedido de otro usuario → 403
- ✅ Modificar carrito de otro usuario → 403

---

### Test 29: Validación de Datos

**Probar:**
- ✅ Enviar email inválido → 422
- ✅ Enviar cantidad negativa → 422
- ✅ Enviar campos vacíos → 422
- ✅ Enviar tipos de datos incorrectos → 422

---

### Test 30: Rate Limiting

**Probar:**
- ✅ Hacer más de 60 requests en 1 minuto
- ✅ Debe retornar 429 (Too Many Requests)

---

## 📊 Testing de Integridad de Datos

### Test 31: Relaciones en Base de Datos

**Verificar en BD:**

```sql
-- Productos tienen categoría y marca válidas
SELECT p.* FROM productos p
LEFT JOIN categorias c ON p.id_categoria = c.id_categoria
LEFT JOIN marcas m ON p.id_marca = m.id_marca
WHERE c.id_categoria IS NULL OR m.id_marca IS NULL;
-- Debe retornar 0 filas

-- Pedidos tienen usuario válido
SELECT p.* FROM pedidos p
LEFT JOIN usuarios u ON p.id_usuario = u.id_usuario
WHERE u.id_usuario IS NULL;
-- Debe retornar 0 filas

-- Pagos tienen pedido válido
SELECT pg.* FROM pagos pg
LEFT JOIN pedidos pd ON pg.id_pedido = pd.id_pedido
WHERE pd.id_pedido IS NULL;
-- Debe retornar 0 filas
```

---

## 🧪 Scripts de Prueba Automatizados

### Ejecutar Scripts PHP

```bash
# Test de conexión
php verificar_conexion.php

# Test de carrito
php test_carrito.php

# Test de CRUD completo
php test_crud_completo.php

# Test de sistema de pagos
php test_sistema_pagos.php

# Test completo de pagos
php test_pagos_completo.php

# Verificar dashboard
php verificar_dashboard.php

# Test de rutas admin
php test_admin_routes.php
```

---

## 📝 Reporte de Bugs

### Formato de Reporte

```markdown
**Título:** [Módulo] Descripción breve

**Severidad:** Crítica | Alta | Media | Baja

**Pasos para reproducir:**
1. Paso 1
2. Paso 2
3. Paso 3

**Resultado esperado:**
Descripción de lo que debería pasar

**Resultado actual:**
Descripción de lo que pasa

**Evidencia:**
- Screenshots
- Logs
- Queries SQL

**Ambiente:**
- OS: Windows/Mac/Linux
- PHP: 8.2
- MySQL: 8.0
- Laravel: 12.39.0
```

---

## ✅ Checklist Final

### Funcionalidades Core
- [ ] Registro de usuarios funciona
- [ ] Login funciona
- [ ] Productos se listan correctamente
- [ ] Carrito funciona (agregar, actualizar, eliminar)
- [ ] Pedidos se crean correctamente
- [ ] Pagos se procesan correctamente

### Panel Admin
- [ ] Dashboard muestra estadísticas
- [ ] CRUD de productos funciona
- [ ] CRUD de categorías funciona
- [ ] CRUD de marcas funciona
- [ ] Gestión de pedidos funciona
- [ ] Gestión de usuarios funciona

### Seguridad
- [ ] Rutas protegidas requieren autenticación
- [ ] Usuarios solo ven sus propios datos
- [ ] Validaciones funcionan correctamente
- [ ] Rate limiting activo

### Base de Datos
- [ ] Todas las relaciones son válidas
- [ ] No hay datos huérfanos
- [ ] Índices funcionan correctamente

---

**Última actualización:** Noviembre 30, 2025  
**Versión:** 1.0.0  
**Estado:** ✅ Listo para Testing
