# 📚 Documentación Nexus Backend

Bienvenido a la documentación completa del sistema Nexus Backend.

## 📖 Índice de Documentación

### 🚀 Inicio Rápido
- [**Instalación**](INSTALACION.md) - Guía paso a paso para configurar el proyecto
- [**Documentación Completa**](DOCUMENTACION_COMPLETA.md) - Referencia completa de la API

### 🏗️ Arquitectura y Diseño
- [**Arquitectura del Sistema**](ARQUITECTURA.md) - Diseño general y componentes
- [**Base de Datos**](BASE_DE_DATOS.md) - Estructura de tablas y relaciones

### 📡 API y Endpoints
- [**Referencia de API**](API.md) - Todos los endpoints disponibles
- [**Autenticación**](AUTENTICACION.md) - Sistema de auth con Laravel Sanctum

### 🛒 Módulos Funcionales
- [**Panel Administrativo**](PANEL_ADMIN.md) - Gestión de productos, categorías, marcas, pedidos y usuarios
- [**Sistema de Pagos**](SISTEMA_PAGOS.md) - Integración con PayPal y Stripe
- [**Carrito de Compras**](CARRITO.md) - Funcionalidad del carrito

### 🧪 Testing y QA
- [**Manual de Testing**](MANUAL_TESTING.md) - Checklist para QA
- [**Scripts de Prueba**](SCRIPTS_PRUEBA.md) - Guía de scripts disponibles

### 📊 Estado del Proyecto
- [**Estado Actual**](ESTADO_PROYECTO.md) - Funcionalidades completadas y pendientes
- [**Changelog**](CHANGELOG.md) - Historial de cambios

---

## 🎯 Inicio Rápido

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
# Edita DB_DATABASE=nexus, DB_USERNAME=root, DB_PASSWORD=

# 3. Instalar dependencias
composer install

# 4. Generar clave de aplicación
php artisan key:generate

# 5. Iniciar servidor
php artisan serve
```

**Accede a:** `http://127.0.0.1:8000`

---

## 📡 Endpoints Principales

### Autenticación
- `POST /api/register` - Registrar usuario
- `POST /api/login` - Iniciar sesión
- `POST /api/logout` - Cerrar sesión
- `GET /api/user` - Obtener usuario actual

### Productos
- `GET /api/productos` - Listar productos
- `GET /api/productos/{id}` - Ver producto
- `GET /api/categorias` - Listar categorías
- `GET /api/marcas` - Listar marcas

### Carrito
- `GET /api/carrito` - Ver carrito
- `POST /api/carrito/agregar` - Agregar producto
- `PUT /api/carrito/actualizar/{id}` - Actualizar cantidad
- `DELETE /api/carrito/eliminar/{id}` - Eliminar producto

### Pedidos
- `GET /api/pedidos` - Listar pedidos
- `POST /api/pedidos` - Crear pedido
- `GET /api/pedidos/{id}` - Ver detalle

### Pagos
- `POST /api/pagos/crear` - Crear intención de pago
- `POST /api/pagos/confirmar` - Confirmar pago
- `GET /api/pagos` - Historial de pagos

---

## 🏗️ Arquitectura

```
┌─────────────────────────────────────────────────────────┐
│                    Frontend (React/Vue)                  │
└────────────────────┬────────────────────────────────────┘
                     │ HTTP/JSON
                     ▼
┌─────────────────────────────────────────────────────────┐
│                  API REST (Laravel)                      │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐             │
│  │  Auth    │  │ Productos│  │  Pagos   │             │
│  │Controller│  │Controller│  │Controller│             │
│  └────┬─────┘  └────┬─────┘  └────┬─────┘             │
└───────┼─────────────┼─────────────┼────────────────────┘
        │             │             │
        ▼             ▼             ▼
┌─────────────────────────────────────────────────────────┐
│                  Eloquent Models                         │
│  User  │  Producto  │  Pedido  │  Pago  │  Carrito     │
└────────────────────┬────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────┐
│                  MySQL Database                          │
│  13 tablas con relaciones completas                     │
└─────────────────────────────────────────────────────────┘
```

---

## 🎯 Estado del Proyecto

### ✅ Completado (95%)
- ✅ Autenticación completa con Sanctum
- ✅ API REST funcional
- ✅ Panel administrativo web
- ✅ CRUD de productos, categorías, marcas
- ✅ Sistema de carrito
- ✅ Gestión de pedidos
- ✅ Sistema de pagos (PayPal + Stripe)
- ✅ Base de datos completa

### 🔄 Opcional (5%)
- 🟡 Sistema de reportes
- 🟡 Notificaciones por email
- 🟡 Dashboard avanzado

---

## 🛠️ Tecnologías

- **Framework:** Laravel 12.39.0
- **Base de Datos:** MySQL 8.0
- **Autenticación:** Laravel Sanctum
- **Frontend Admin:** Bootstrap 5.3.0
- **Iconos:** Bootstrap Icons 1.11.0
- **PHP:** 8.2+

---

## 📞 Soporte

Para más información, consulta los documentos específicos en este directorio o contacta al equipo de desarrollo.

---

**Última actualización:** Noviembre 30, 2025  
**Versión:** 1.0.0  
**Estado:** ✅ Producción Ready
