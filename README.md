# Nexus E-commerce - Backend API

Sistema completo de e-commerce desarrollado con Laravel 12, MySQL y API REST. Incluye autenticación, gestión de productos, carrito de compras, pedidos y sistema de pagos integrado con PayPal y Stripe.

---

## Tabla de Contenidos

- [Características](#características)
- [Requisitos del Sistema](#requisitos-del-sistema)
- [Instalación](#instalación)
- [Configuración](#configuración)
- [Estructura del Proyecto](#estructura-del-proyecto)
- [Endpoints de la API](#endpoints-de-la-api)
- [Base de Datos](#base-de-datos)
- [Documentación](#documentación)
- [Testing](#testing)
- [Seguridad](#seguridad)
- [Estado del Proyecto](#estado-del-proyecto)

---

## Características

### Funcionalidades Principales

- **Autenticación completa** con Laravel Sanctum
- **API REST** con 77 endpoints funcionales
- **Panel administrativo** con interfaz Bootstrap
- **Sistema de carrito** completamente funcional
- **Gestión de pedidos** con múltiples estados
- **Sistema de pagos** integrado (PayPal y Stripe)
- **Base de datos** MySQL con 13 tablas relacionadas
- **Documentación completa** para desarrolladores y QA
- **Sistema de emails** con Gmail SMTP
- **Subida de imágenes** con almacenamiento local

### Módulos Implementados

1. Autenticación y autorización
2. Gestión de productos, categorías y marcas
3. Carrito de compras
4. Procesamiento de pedidos
5. Sistema de pagos múltiples
6. Panel administrativo
7. Gestión de usuarios y roles
8. Bitácora de acciones
9. Sistema de emails transaccionales

---

## Requisitos del Sistema

### Requisitos Mínimos

- PHP 8.2 o superior
- MySQL 8.0 o superior
- Composer 2.x
- Servidor web (Apache/Nginx) o XAMPP
- Extensiones PHP requeridas:
  - OpenSSL
  - PDO
  - Mbstring
  - Tokenizer
  - XML
  - Ctype
  - JSON
  - BCMath

### Requisitos Recomendados

- PHP 8.3
- MySQL 8.0.30+
- 2GB RAM mínimo
- 500MB espacio en disco

---

## Instalación

### Paso 1: Clonar el Repositorio

```bash
git clone https://github.com/WillyBarrios/Nexus_Ecommerce.git
cd Nexus_Ecommerce/nexus-backend
```

### Paso 2: Instalar Dependencias

```bash
composer install
```

### Paso 3: Configurar Variables de Entorno

```bash
cp .env.example .env
```

Editar el archivo `.env` con tus credenciales:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nexus
DB_USERNAME=root
DB_PASSWORD=tu_password
```

### Paso 4: Generar Clave de Aplicación

```bash
php artisan key:generate
```

### Paso 5: Ejecutar Migraciones

```bash
php artisan migrate
```

### Paso 6: Crear Enlace de Storage

```bash
php artisan storage:link
```

### Paso 7: Iniciar Servidor

```bash
php artisan serve
```

El servidor estará disponible en: `http://127.0.0.1:8000`

---

## Configuración

### Configuración de Base de Datos

Asegúrate de tener MySQL corriendo y crear la base de datos:

```sql
CREATE DATABASE nexus CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Configuración de Emails (Gmail SMTP)

Para habilitar el envío de emails, configura las siguientes variables en `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu-email@gmail.com
MAIL_PASSWORD=tu-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@nexus.com"
MAIL_FROM_NAME="Nexus E-commerce"
```

**Nota:** Necesitas generar una "Contraseña de aplicación" en tu cuenta de Gmail con verificación en 2 pasos habilitada.

### Configuración de Pagos

#### PayPal

```env
PAYPAL_MODE=sandbox
PAYPAL_SANDBOX_CLIENT_ID=tu_client_id
PAYPAL_SANDBOX_CLIENT_SECRET=tu_client_secret
PAYPAL_CURRENCY=USD
```

#### Stripe

```env
STRIPE_PUBLISHABLE_KEY=tu_publishable_key
STRIPE_SECRET_KEY=tu_secret_key
STRIPE_WEBHOOK_SECRET=tu_webhook_secret
STRIPE_CURRENCY=usd
```

---

## Estructura del Proyecto

```
nexus-backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/              # Controladores de la API REST
│   │   │   │   ├── AuthController.php
│   │   │   │   ├── ProductoController.php
│   │   │   │   ├── CarritoController.php
│   │   │   │   ├── PedidoController.php
│   │   │   │   └── PagoController.php
│   │   │   └── Admin/            # Controladores del panel admin
│   │   │       ├── DashboardController.php
│   │   │       ├── ProductoAdminController.php
│   │   │       └── PedidoAdminController.php
│   │   ├── Middleware/           # Middlewares personalizados
│   │   └── Requests/             # Validaciones de formularios
│   ├── Models/                   # Modelos Eloquent
│   │   ├── User.php
│   │   ├── Producto.php
│   │   ├── Carrito.php
│   │   ├── Pedido.php
│   │   └── Pago.php
│   └── Services/                 # Lógica de negocio
│       ├── ProductoService.php
│       ├── CarritoService.php
│       ├── PedidoService.php
│       ├── PagoService.php
│       └── EmailService.php
├── config/
│   ├── payment.php               # Configuración de pagos
│   └── mail.php                  # Configuración de emails
├── database/
│   └── migrations/               # Migraciones de base de datos
├── docs/                         # Documentación completa
│   ├── README.md
│   ├── API.md
│   ├── ARQUITECTURA.md
│   ├── INSTALACION.md
│   └── MANUAL_TESTING.md
├── public/
│   ├── storage/                  # Archivos públicos (imágenes)
│   ├── test.html                 # Página de pruebas
│   └── index.html                # Landing page
├── resources/
│   └── views/
│       ├── admin/                # Vistas del panel admin
│       └── emails/               # Plantillas de email
├── routes/
│   ├── api.php                   # Rutas de la API
│   └── web.php                   # Rutas web
├── storage/
│   └── app/
│       └── public/
│           └── productos/        # Imágenes de productos
├── tests/                        # Tests automatizados
├── .env.example                  # Plantilla de variables de entorno
├── composer.json                 # Dependencias PHP
└── README.md                     # Este archivo
```

---

## Endpoints de la API

### Autenticación

| Método | Endpoint | Descripción | Auth |
|--------|----------|-------------|------|
| POST | `/api/register` | Registrar nuevo usuario | No |
| POST | `/api/login` | Iniciar sesión | No |
| GET | `/api/user` | Obtener usuario actual | Sí |
| POST | `/api/logout` | Cerrar sesión | Sí |

### Productos

| Método | Endpoint | Descripción | Auth |
|--------|----------|-------------|------|
| GET | `/api/productos` | Listar productos con filtros | No |
| GET | `/api/productos/{id}` | Ver detalle de producto | No |
| GET | `/api/categorias` | Listar categorías | No |
| GET | `/api/marcas` | Listar marcas | No |

### Carrito de Compras

| Método | Endpoint | Descripción | Auth |
|--------|----------|-------------|------|
| GET | `/api/carrito` | Ver carrito actual | Sí |
| POST | `/api/carrito/agregar` | Agregar producto al carrito | Sí |
| PUT | `/api/carrito/actualizar/{id}` | Actualizar cantidad | Sí |
| DELETE | `/api/carrito/eliminar/{id}` | Eliminar producto | Sí |
| DELETE | `/api/carrito/vaciar` | Vaciar carrito completo | Sí |

### Pedidos

| Método | Endpoint | Descripción | Auth |
|--------|----------|-------------|------|
| GET | `/api/pedidos` | Listar pedidos del usuario | Sí |
| POST | `/api/pedidos` | Crear nuevo pedido | Sí |
| GET | `/api/pedidos/{id}` | Ver detalle de pedido | Sí |
| DELETE | `/api/pedidos/{id}` | Cancelar pedido | Sí |

### Pagos

| Método | Endpoint | Descripción | Auth |
|--------|----------|-------------|------|
| POST | `/api/pagos/crear` | Crear intención de pago | Sí |
| POST | `/api/pagos/confirmar` | Confirmar pago procesado | Sí |
| GET | `/api/pagos` | Historial de pagos | Sí |
| GET | `/api/pagos/{id}` | Ver detalle de pago | Sí |

**Documentación completa de la API:** [docs/API.md](docs/API.md)

---

## Base de Datos

### Esquema de Base de Datos

El sistema utiliza 13 tablas principales:

#### Tablas de Usuarios y Seguridad

- **usuarios** - Información de usuarios del sistema
- **roles** - Roles del sistema (Administrador, Vendedor, Cliente)
- **password_reset_tokens** - Tokens para recuperación de contraseña
- **personal_access_tokens** - Tokens de autenticación Sanctum

#### Tablas de Productos

- **productos** - Catálogo de productos
- **categorias** - Categorías de productos
- **marcas** - Marcas de productos
- **imagenes_producto** - Imágenes de productos

#### Tablas de Ventas

- **carritos** - Carritos de compra activos
- **detalle_carrito** - Items dentro de cada carrito
- **pedidos** - Pedidos realizados
- **detalle_pedido** - Items de cada pedido
- **pagos** - Pagos procesados

#### Tablas de Sistema

- **movimientos_inventario** - Historial de cambios en inventario
- **bitacora** - Registro de acciones del sistema

### Relaciones Principales

```
usuarios (1) -----> (N) carritos
usuarios (1) -----> (N) pedidos
usuarios (1) -----> (N) pagos

productos (1) -----> (N) detalle_carrito
productos (1) -----> (N) detalle_pedido
productos (N) -----> (1) categorias
productos (N) -----> (1) marcas

pedidos (1) -----> (N) detalle_pedido
pedidos (1) -----> (1) pagos
```

---

## Documentación

### Documentación Disponible

La carpeta `docs/` contiene documentación completa del proyecto:

- **[README.md](docs/README.md)** - Índice de toda la documentación
- **[API.md](docs/API.md)** - Referencia completa de endpoints
- **[ARQUITECTURA.md](docs/ARQUITECTURA.md)** - Diseño y arquitectura del sistema
- **[INSTALACION.md](docs/INSTALACION.md)** - Guía detallada de instalación
- **[MANUAL_TESTING.md](docs/MANUAL_TESTING.md)** - Checklist para QA
- **[ESTADO_PROYECTO.md](docs/ESTADO_PROYECTO.md)** - Estado actual del desarrollo
- **[CHANGELOG.md](docs/CHANGELOG.md)** - Historial de cambios

### Acceso Rápido

- **API Base URL:** `http://127.0.0.1:8000/api`
- **Panel Admin:** `http://127.0.0.1:8000/admin`
- **Página de Pruebas:** `http://127.0.0.1:8000/test.html`

---

## Testing

### Pruebas Manuales

#### Opción 1: Página Web de Pruebas

```bash
php artisan serve
```

Abrir en el navegador: `http://127.0.0.1:8000/test.html`

#### Opción 2: Scripts PHP

```bash
php verificar_conexion.php      # Verificar conexión a base de datos
php test_carrito.php            # Probar funcionalidad del carrito
php test_pagos_completo.php     # Probar sistema de pagos
php test_crud_completo.php      # Probar operaciones CRUD
```

#### Opción 3: Herramientas Externas

- **Postman:** Importar colección desde `docs/API.md`
- **cURL:** Ejemplos disponibles en la documentación
- **Thunder Client:** Extensión de VS Code

### Pruebas Automatizadas

```bash
php artisan test
```

### Checklist de Testing

Ver el [Manual de Testing](docs/MANUAL_TESTING.md) para un checklist completo de pruebas.

---

## Seguridad

### Medidas de Seguridad Implementadas

- **Contraseñas hasheadas** con bcrypt
- **Tokens seguros** con Laravel Sanctum
- **Validación de datos** en todas las peticiones
- **Protección contra SQL injection** mediante Eloquent ORM
- **Rate limiting** configurado (60 requests por minuto)
- **Prevención de enumeración de usuarios**
- **CORS configurado** correctamente
- **Sanitización de inputs** en todos los formularios
- **Protección CSRF** en formularios web

### Recomendaciones de Producción

1. Cambiar `APP_DEBUG=false` en producción
2. Configurar HTTPS obligatorio
3. Implementar firewall de aplicación web (WAF)
4. Configurar backups automáticos de base de datos
5. Monitorear logs de seguridad regularmente
6. Mantener Laravel y dependencias actualizadas

---

## Estado del Proyecto

### Información General

- **Versión:** 1.0.0
- **Estado:** Producción Ready
- **Completado:** 95%
- **Última actualización:** Diciembre 2024

### Funcionalidades Completadas

**Core del Sistema (100%)**
- Autenticación y autorización
- API REST completa
- Panel administrativo
- Sistema de carrito
- Gestión de pedidos
- Sistema de pagos (PayPal y Stripe)
- Gestión de productos, categorías y marcas
- Subida de imágenes
- Sistema de emails

**Funcionalidades Opcionales (5%)**
- Sistema de reportes avanzados
- Notificaciones push
- Dashboard con gráficos en tiempo real

### Próximas Mejoras

1. Implementar sistema de cupones de descuento
2. Agregar sistema de reseñas de productos
3. Implementar búsqueda avanzada con filtros
4. Agregar sistema de wishlist
5. Implementar notificaciones en tiempo real

---

## Tecnologías Utilizadas

### Backend

- **Framework:** Laravel 12.39.0
- **Lenguaje:** PHP 8.2+
- **Base de Datos:** MySQL 8.0
- **Autenticación:** Laravel Sanctum 4.x
- **Gestor de Dependencias:** Composer 2.x

### Frontend (Panel Admin)

- **Framework CSS:** Bootstrap 5.3.0
- **Iconos:** Bootstrap Icons 1.11.0
- **JavaScript:** Vanilla JS

### Servicios Externos

- **Pagos:** PayPal API, Stripe API
- **Emails:** Gmail SMTP
- **Almacenamiento:** Local Storage (Laravel)

---

## Contribución

Este proyecto es parte de un trabajo académico. Para contribuir:

1. Fork el repositorio
2. Crear una rama para tu feature (`git checkout -b feature/nueva-funcionalidad`)
3. Commit tus cambios (`git commit -m 'Agregar nueva funcionalidad'`)
4. Push a la rama (`git push origin feature/nueva-funcionalidad`)
5. Abrir un Pull Request

---

## Licencia

Este proyecto es de uso académico y educativo.

---

## Contacto y Soporte

Para más información o soporte técnico:

- **Repositorio:** https://github.com/WillyBarrios/Nexus_Ecommerce
- **Documentación:** [docs/README.md](docs/README.md)
- **Issues:** https://github.com/WillyBarrios/Nexus_Ecommerce/issues

---

**Desarrollado por:** Equipo de Desarrollo Nexus  
**Última actualización:** Diciembre 2025
