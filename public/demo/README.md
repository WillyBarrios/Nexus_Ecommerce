# Nexus E-commerce - Demo Interactivo

Sistema de demostración completo del backend de Nexus E-commerce con interfaz web interactiva.

## Páginas Disponibles

### 1. **index.html** - Página Principal
- Introducción al sistema
- Enlaces a todas las secciones
- Información técnica
- Credenciales de prueba

### 2. **auth.html** - Autenticación
- Formulario de login
- Formulario de registro
- Visualización de código de peticiones
- Manejo de tokens

### 3. **shop.html** - Tienda
- Catálogo de productos
- Filtros por categoría y marca
- Búsqueda de productos
- Agregar productos al carrito
- Contador de items en carrito

### 4. **cart.html** - Carrito de Compras
- Ver productos en el carrito
- Modificar cantidades
- Eliminar productos
- Vaciar carrito completo
- Resumen de total
- Botón para proceder al checkout

### 5. **checkout.html** - Proceso de Pago
- Selección de método de pago
- Resumen del pedido
- Creación de pedido desde carrito
- Redirección a pedidos

### 6. **orders.html** - Mis Pedidos
- Historial de pedidos
- Estados de pedidos
- Ver detalles de cada pedido
- Información de productos comprados

## Características

- **Autenticación persistente**: Los tokens se guardan en localStorage
- **Navegación fluida**: Menú de navegación en todas las páginas
- **Visualización de código**: Cada petición muestra el código en tiempo real
- **Diseño responsive**: Funciona en desktop y móvil
- **Manejo de errores**: Alertas visuales para éxito y errores
- **Protección de rutas**: Páginas protegidas requieren autenticación

## Cómo Usar

1. **Iniciar el servidor Laravel**:
   ```bash
   cd nexus-backend
   php artisan serve
   ```

2. **Acceder al demo**:
   ```
   http://127.0.0.1:8000/demo/index.html
   ```

3. **Flujo recomendado**:
   - Registrarse o iniciar sesión en `auth.html`
   - Explorar productos en `shop.html`
   - Agregar productos al carrito
   - Ver carrito en `cart.html`
   - Proceder al checkout en `checkout.html`
   - Ver pedidos en `orders.html`

## Credenciales de Prueba

**Usuario Admin:**
- Email: `admin@test.com`
- Password: `admin123`

O registra tu propio usuario en la sección de autenticación.

## Archivos del Sistema

- `style.css` - Estilos compartidos
- `app.js` - Funciones JavaScript compartidas (Auth, API, Utils)
- `*.html` - Páginas del demo

## Tecnologías

- HTML5
- CSS3
- JavaScript Vanilla (sin frameworks)
- Bootstrap Icons
- Laravel Backend API
- Laravel Sanctum (autenticación)

## Endpoints Utilizados

- `POST /api/register` - Registro de usuarios
- `POST /api/login` - Inicio de sesión
- `POST /api/logout` - Cerrar sesión
- `GET /api/user` - Obtener usuario autenticado
- `GET /api/productos` - Listar productos
- `GET /api/categorias` - Listar categorías
- `GET /api/marcas` - Listar marcas
- `GET /api/carrito` - Ver carrito
- `POST /api/carrito/agregar` - Agregar al carrito
- `PUT /api/carrito/actualizar/{id}` - Actualizar cantidad
- `DELETE /api/carrito/eliminar/{id}` - Eliminar del carrito
- `DELETE /api/carrito/vaciar` - Vaciar carrito
- `GET /api/pedidos` - Listar pedidos
- `GET /api/pedidos/{id}` - Ver detalle de pedido
- `POST /api/pedidos` - Crear pedido

## Notas

- El sistema guarda el token en `localStorage` con la clave `nexus_token`
- El usuario se guarda en `localStorage` con la clave `nexus_user`
- Todas las peticiones protegidas incluyen el header `Authorization: Bearer {token}`
- El código de cada petición se muestra en tiempo real en cada página
