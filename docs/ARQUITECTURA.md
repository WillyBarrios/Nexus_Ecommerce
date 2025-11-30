# 🏗️ Arquitectura del Sistema Nexus Backend

## Visión General

Nexus Backend es una API REST construida con Laravel que proporciona servicios de e-commerce incluyendo autenticación, gestión de productos, carrito de compras, pedidos y pagos.

## Arquitectura de Alto Nivel

```
┌─────────────────────────────────────────────────────────────────┐
│                         FRONTEND LAYER                           │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐         │
│  │   React/Vue  │  │  Mobile App  │  │  Panel Admin │         │
│  └──────┬───────┘  └──────┬───────┘  └──────┬───────┘         │
└─────────┼──────────────────┼──────────────────┼────────────────┘
          │                  │                  │
          │    HTTP/JSON     │                  │
          ▼                  ▼                  ▼
┌─────────────────────────────────────────────────────────────────┐
│                         API LAYER (Laravel)                      │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │                    Routes (api.php)                       │  │
│  └────────────────────────┬─────────────────────────────────┘  │
│                           │                                     │
│  ┌────────────────────────┴─────────────────────────────────┐  │
│  │                    Middleware                             │  │
│  │  - CORS  - Auth (Sanctum)  - Validation  - Rate Limit   │  │
│  └────────────────────────┬─────────────────────────────────┘  │
│                           │                                     │
│  ┌────────────────────────┴─────────────────────────────────┐  │
│  │                    Controllers                            │  │
│  │  ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐   │  │
│  │  │   Auth   │ │ Producto │ │ Carrito  │ │   Pago   │   │  │
│  │  └────┬─────┘ └────┬─────┘ └────┬─────┘ └────┬─────┘   │  │
│  └───────┼────────────┼────────────┼────────────┼──────────┘  │
└──────────┼────────────┼────────────┼────────────┼─────────────┘
           │            │            │            │
           ▼            ▼            ▼            ▼
┌─────────────────────────────────────────────────────────────────┐
│                      BUSINESS LOGIC LAYER                        │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │                    Eloquent Models                        │  │
│  │  User │ Producto │ Carrito │ Pedido │ Pago │ Categoria  │  │
│  └────────────────────────┬─────────────────────────────────┘  │
│                           │                                     │
│  ┌────────────────────────┴─────────────────────────────────┐  │
│  │                    Relationships                          │  │
│  │  - User → Carritos → DetalleCarrito → Productos         │  │
│  │  - User → Pedidos → DetallePedido → Productos           │  │
│  │  - Pedido → Pago                                         │  │
│  │  - Producto → Categoria, Marca                           │  │
│  └────────────────────────┬─────────────────────────────────┘  │
└────────────────────────────┼────────────────────────────────────┘
                             │
                             ▼
┌─────────────────────────────────────────────────────────────────┐
│                      DATA LAYER (MySQL)                          │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │  usuarios │ productos │ carritos │ pedidos │ pagos       │  │
│  │  categorias │ marcas │ detalle_carrito │ detalle_pedido │  │
│  └──────────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────────┘
                             │
                             ▼
┌─────────────────────────────────────────────────────────────────┐
│                    EXTERNAL SERVICES                             │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐         │
│  │    PayPal    │  │    Stripe    │  │    Email     │         │
│  └──────────────┘  └──────────────┘  └──────────────┘         │
└─────────────────────────────────────────────────────────────────┘
```

---

## Componentes Principales

### 1. API Layer

#### Routes (`routes/api.php`)
Define todos los endpoints disponibles y aplica middleware correspondiente.

**Grupos de rutas:**
- `/api/register`, `/api/login` - Autenticación pública
- `/api/productos`, `/api/categorias`, `/api/marcas` - Catálogo público
- `/api/carrito/*` - Carrito (requiere auth)
- `/api/pedidos/*` - Pedidos (requiere auth)
- `/api/pagos/*` - Pagos (requiere auth)

#### Middleware
- **CORS**: Permite peticiones desde frontend
- **Sanctum Auth**: Valida tokens de autenticación
- **Validation**: Valida datos de entrada
- **Rate Limiting**: 60 requests/minuto

#### Controllers
Manejan la lógica de negocio y retornan respuestas JSON.

**Principales controladores:**
- `AuthController` - Registro, login, logout
- `ProductoController` - CRUD de productos
- `CarritoController` - Gestión del carrito
- `PedidoController` - Gestión de pedidos
- `PagoController` - Procesamiento de pagos

---

### 2. Business Logic Layer

#### Eloquent Models
Representan las entidades del sistema y sus relaciones.

**Modelos principales:**
```php
User
├── hasMany(Carrito)
├── hasMany(Pedido)
└── hasMany(Pago)

Producto
├── belongsTo(Categoria)
├── belongsTo(Marca)
├── hasMany(DetalleCarrito)
└── hasMany(DetallePedido)

Carrito
├── belongsTo(User)
└── hasMany(DetalleCarrito)

Pedido
├── belongsTo(User)
├── hasOne(Pago)
└── hasMany(DetallePedido)

Pago
├── belongsTo(User)
└── belongsTo(Pedido)
```

#### Relaciones Clave

**Usuario → Carrito:**
- Un usuario tiene un carrito activo (estado='abierto')
- Al crear pedido, el carrito se cierra y se crea uno nuevo

**Carrito → Productos:**
- Relación many-to-many a través de `detalle_carrito`
- Almacena cantidad y precio unitario al momento de agregar

**Pedido → Productos:**
- Relación many-to-many a través de `detalle_pedido`
- Copia los datos del carrito al crear el pedido

**Pedido → Pago:**
- Relación one-to-one
- Un pedido puede tener un pago asociado

---

### 3. Data Layer

#### Base de Datos MySQL

**13 Tablas principales:**

1. **usuarios** - Información de usuarios
2. **roles** - Roles del sistema (Admin, Vendedor, Cliente)
3. **productos** - Catálogo de productos
4. **categorias** - Categorías de productos
5. **marcas** - Marcas de productos
6. **carritos** - Carritos de compra
7. **detalle_carrito** - Items del carrito
8. **pedidos** - Pedidos realizados
9. **detalle_pedido** - Items del pedido
10. **pagos** - Pagos procesados
11. **bitacora** - Log de acciones
12. **password_reset_tokens** - Tokens de recuperación
13. **personal_access_tokens** - Tokens de Sanctum

#### Índices y Optimizaciones

```sql
-- Índices en usuarios
INDEX idx_email (email)
INDEX idx_rol (id_rol)

-- Índices en productos
INDEX idx_categoria (id_categoria)
INDEX idx_marca (id_marca)
INDEX idx_nombre (nombre_producto)

-- Índices en carritos
INDEX idx_usuario (id_usuario)
INDEX idx_estado (estado)

-- Índices en pedidos
INDEX idx_usuario (id_usuario)
INDEX idx_estado (estado)
INDEX idx_fecha (fecha_creacion)

-- Índices en pagos
INDEX idx_usuario (id_usuario)
INDEX idx_pedido (id_pedido)
INDEX idx_estado (estado)
```

---

## Flujos de Datos

### Flujo de Registro y Autenticación

```
1. Usuario envía credenciales
   ↓
2. RegisterRequest valida datos
   ↓
3. AuthController::register()
   ↓
4. User::create() con password hasheado
   ↓
5. createToken() genera token Sanctum
   ↓
6. Retorna user + token
```

### Flujo de Compra Completa

```
1. Usuario agrega productos al carrito
   POST /api/carrito/agregar
   ↓
2. CarritoController verifica stock
   ↓
3. Crea/actualiza DetalleCarrito
   ↓
4. Usuario crea pedido
   POST /api/pedidos
   ↓
5. PedidoController copia items del carrito
   ↓
6. Crea Pedido + DetallePedido
   ↓
7. Cierra carrito actual
   ↓
8. Usuario crea intención de pago
   POST /api/pagos/crear
   ↓
9. PagoController crea registro de pago
   ↓
10. Retorna datos para PayPal/Stripe
    ↓
11. Usuario completa pago en gateway
    ↓
12. Frontend confirma pago
    POST /api/pagos/confirmar
    ↓
13. PagoController actualiza estado
    ↓
14. Pedido cambia a estado "pagado"
    ↓
15. ¡Compra completada! 🎉
```

---

## Seguridad

### Autenticación
- **Laravel Sanctum**: Tokens de acceso personal
- **Bcrypt**: Hash de contraseñas
- **Token Expiration**: Tokens no expiran (configurable)

### Validación
- **Form Requests**: Validación centralizada
- **Reglas de negocio**: En controladores y modelos
- **Sanitización**: Automática por Eloquent

### Autorización
- **Ownership**: Usuarios solo ven sus propios datos
- **Role-based**: Verificación de roles en controladores
- **Middleware**: Protección de rutas sensibles

### Prevención de Ataques
- **SQL Injection**: Eloquent usa prepared statements
- **XSS**: Escape automático en Blade
- **CSRF**: Token en formularios web
- **Rate Limiting**: 60 requests/minuto

---

## Escalabilidad

### Optimizaciones Actuales
- Eager loading de relaciones
- Índices en columnas frecuentes
- Caché de configuración

### Mejoras Futuras
- Redis para caché y sesiones
- Queue para emails y notificaciones
- CDN para imágenes de productos
- Replicación de base de datos
- Load balancer para múltiples instancias

---

## Monitoreo y Logs

### Logs de Laravel
```
storage/logs/laravel.log
```

Registra:
- Errores de aplicación
- Queries lentas
- Excepciones no manejadas

### Bitácora de Acciones
Tabla `bitacora` registra:
- Acciones de usuarios
- Cambios en datos críticos
- Intentos de acceso

---

## Tecnologías y Versiones

- **Framework**: Laravel 12.39.0
- **PHP**: 8.2+
- **Base de Datos**: MySQL 8.0
- **Autenticación**: Laravel Sanctum 4.x
- **Servidor Web**: Apache 2.4 (XAMPP)
- **Composer**: 2.x

---

## Patrones de Diseño

### MVC (Model-View-Controller)
- **Model**: Eloquent models
- **View**: JSON responses (API)
- **Controller**: Business logic

### Repository Pattern
- Modelos Eloquent actúan como repositorios
- Queries complejas en métodos del modelo

### Service Layer
- Lógica de negocio compleja en servicios
- Ejemplo: PagoController usa servicios de PayPal/Stripe

### Request/Response Pattern
- Form Requests para validación
- Respuestas JSON estandarizadas

---

## Convenciones de Código

### Nombres de Archivos
- Controllers: `NombreController.php`
- Models: `Nombre.php`
- Requests: `NombreRequest.php`

### Nombres de Métodos
- CRUD: `index`, `show`, `store`, `update`, `destroy`
- Custom: verbos descriptivos (`confirmarPago`, `vaciarCarrito`)

### Respuestas JSON
```json
{
  "success": true|false,
  "message": "Mensaje descriptivo",
  "data": {...}|[...],
  "errors": {...} // solo en errores
}
```

---

## Diagrama de Despliegue

```
┌─────────────────────────────────────────────────────────┐
│                    SERVIDOR XAMPP                        │
│  ┌───────────────────────────────────────────────────┐  │
│  │              Apache Web Server                     │  │
│  │              Port: 8000                            │  │
│  └────────────────────┬──────────────────────────────┘  │
│                       │                                  │
│  ┌────────────────────┴──────────────────────────────┐  │
│  │              Laravel Application                   │  │
│  │              /xampp/htdocs/nexus-backend          │  │
│  └────────────────────┬──────────────────────────────┘  │
│                       │                                  │
│  ┌────────────────────┴──────────────────────────────┐  │
│  │              MySQL Server                          │  │
│  │              Port: 3306                            │  │
│  │              Database: nexus                       │  │
│  └───────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────┘
```

---

**Última actualización:** Noviembre 30, 2025  
**Versión:** 1.0.0
