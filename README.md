# 🚀 Sistema Nexus - Backend

Sistema completo de e-commerce con Laravel 12, MySQL y API REST. Incluye autenticación, gestión de productos, carrito de compras, pedidos y sistema de pagos con PayPal y Stripe.

## ✨ Características Principales

- ✅ **Autenticación completa** con Laravel Sanctum
- ✅ **API REST** con 25+ endpoints
- ✅ **Panel administrativo web** con Bootstrap 5
- ✅ **Sistema de carrito** completamente funcional
- ✅ **Gestión de pedidos** con múltiples estados
- ✅ **Sistema de pagos** (PayPal + Stripe)
- ✅ **Base de datos** MySQL con 13 tablas relacionadas
- ✅ **Documentación completa** para desarrolladores y QA

## 🚀 Inicio Rápido

### Requisitos Previos
- PHP 8.2+
- MySQL 8.0+
- Composer 2.x
- XAMPP o servidor similar

### Instalación en 5 Pasos

```bash
# 1. Importar base de datos
# Abre phpMyAdmin e importa nexus.sql

# 2. Configurar variables de entorno
cp .env.example .env
# Edita: DB_DATABASE=nexus, DB_USERNAME=root, DB_PASSWORD=

# 3. Instalar dependencias
composer install

# 4. Generar clave de aplicación
php artisan key:generate

# 5. Iniciar servidor
php artisan serve
```

**Accede a:**
- API: `http://127.0.0.1:8000/api`
- Panel Admin: `http://127.0.0.1:8000/admin`
- Página de pruebas: `http://127.0.0.1:8000/test.html`

## 📚 Documentación Completa

### 📖 Guías Principales
- **[Índice de Documentación](docs/README.md)** - Punto de entrada a toda la documentación
- **[Guía de Instalación](docs/INSTALACION.md)** - Instalación paso a paso
- **[Referencia de API](docs/API.md)** - Todos los endpoints disponibles
- **[Arquitectura del Sistema](docs/ARQUITECTURA.md)** - Diseño y componentes

### 🧪 Testing y QA
- **[Manual de Testing](docs/MANUAL_TESTING.md)** - Checklist completo para QA
- **[FAQ de Pagos](docs/FAQ_PAGOS.md)** - Preguntas frecuentes sobre pagos

### 📊 Estado del Proyecto
- **[Estado Actual](docs/ESTADO_PROYECTO.md)** - Funcionalidades completadas
- **[Changelog](docs/CHANGELOG.md)** - Historial de cambios

### 🛠️ Módulos Específicos
- **[Panel Administrativo](docs/PANEL_ADMIN.md)** - Guía del panel admin
- **[Sistema de Pagos](docs/SISTEMA_PAGOS.md)** - Integración PayPal/Stripe

## 📡 Endpoints Principales

### Autenticación
```
POST   /api/register          - Registrar usuario
POST   /api/login             - Iniciar sesión
GET    /api/user              - Obtener usuario actual
POST   /api/logout            - Cerrar sesión
```

### Productos
```
GET    /api/productos         - Listar productos
GET    /api/productos/{id}    - Ver producto
GET    /api/categorias        - Listar categorías
GET    /api/marcas            - Listar marcas
```

### Carrito (requiere auth)
```
GET    /api/carrito                    - Ver carrito
POST   /api/carrito/agregar            - Agregar producto
PUT    /api/carrito/actualizar/{id}    - Actualizar cantidad
DELETE /api/carrito/eliminar/{id}      - Eliminar producto
DELETE /api/carrito/vaciar             - Vaciar carrito
```

### Pedidos (requiere auth)
```
GET    /api/pedidos           - Listar pedidos
POST   /api/pedidos           - Crear pedido
GET    /api/pedidos/{id}      - Ver detalle
DELETE /api/pedidos/{id}      - Cancelar pedido
```

### Pagos (requiere auth)
```
POST   /api/pagos/crear       - Crear intención de pago
POST   /api/pagos/confirmar   - Confirmar pago
GET    /api/pagos             - Historial de pagos
GET    /api/pagos/{id}        - Ver detalle de pago
```

**Ver documentación completa:** [docs/API.md](docs/API.md)

## 🗄️ Base de Datos

13 tablas principales con relaciones completas:
- `usuarios` - Usuarios del sistema
- `roles` - Roles (Administrador, Vendedor, Cliente)
- `productos` - Catálogo de productos
- `categorias` - Categorías de productos
- `marcas` - Marcas de productos
- `carritos` - Carritos de compra
- `detalle_carrito` - Items del carrito
- `pedidos` - Pedidos realizados
- `detalle_pedido` - Items del pedido
- `pagos` - Pagos procesados
- `bitacora` - Registro de acciones
- `password_reset_tokens` - Tokens de recuperación
- `personal_access_tokens` - Tokens de Sanctum

## 🛠️ Tecnologías

- **Framework:** Laravel 12.39.0
- **Base de Datos:** MySQL 8.0
- **Autenticación:** Laravel Sanctum 4.x
- **Frontend Admin:** Bootstrap 5.3.0
- **Iconos:** Bootstrap Icons 1.11.0
- **PHP:** 8.2+
- **Composer:** 2.x

## 📁 Estructura del Proyecto

```
nexus-backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/           # Controladores de la API
│   │   │   └── Admin/         # Controladores del panel admin
│   │   └── Requests/          # Validaciones
│   ├── Models/                # Modelos Eloquent
│   └── Services/              # Lógica de negocio
├── config/
│   └── payment.php            # Configuración de pagos
├── database/
│   └── migrations/            # Migraciones
├── docs/                      # 📚 Documentación completa
│   ├── README.md              # Índice de documentación
│   ├── API.md                 # Referencia de API
│   ├── ARQUITECTURA.md        # Diseño del sistema
│   ├── MANUAL_TESTING.md      # Guía para QA
│   └── ...
├── public/
│   ├── test.html              # Página de pruebas
│   └── index.html             # Landing page
├── routes/
│   ├── api.php                # Rutas de la API
│   └── web.php                # Rutas web (admin)
├── tests/                     # Tests automatizados
├── nexus.sql                  # Script de base de datos
└── README.md                  # Este archivo
```

## 🧪 Pruebas

### Opción 1: Página Web (Recomendado)
```bash
php artisan serve
# Abre http://127.0.0.1:8000/test.html
```

### Opción 2: Scripts PHP
```bash
php verificar_conexion.php      # Verificar conexión a BD
php test_carrito.php            # Probar carrito
php test_pagos_completo.php     # Probar sistema de pagos
php test_crud_completo.php      # Probar CRUD completo
```

### Opción 3: Postman/cURL
Ver [Manual de Testing](docs/MANUAL_TESTING.md)

## 🔒 Seguridad

- ✅ Contraseñas hasheadas con bcrypt
- ✅ Tokens seguros con Laravel Sanctum
- ✅ Validación de datos en todas las peticiones
- ✅ Protección contra SQL injection
- ✅ Rate limiting (60 requests/minuto)
- ✅ Prevención de enumeración de usuarios
- ✅ CORS configurado correctamente

## 🎯 Estado del Proyecto

**Versión:** 1.0.0  
**Estado:** ✅ Producción Ready  
**Completado:** 95%

### ✅ Funcionalidades Implementadas
- Autenticación completa
- API REST funcional
- Panel administrativo
- Sistema de carrito
- Gestión de pedidos
- Sistema de pagos (PayPal + Stripe)

### 🔄 Opcional (5%)
- Sistema de reportes
- Notificaciones por email
- Dashboard avanzado

Ver [Estado del Proyecto](docs/ESTADO_PROYECTO.md) para más detalles.

## 📞 Soporte

Para más información, consulta la [documentación completa](docs/README.md) o contacta al equipo de desarrollo.

---

**Última actualización:** Noviembre 30, 2025  
**Mantenido por:** Equipo de Desarrollo Nexus
