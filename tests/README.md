# 🧪 Tests - Nexus Backend

Esta carpeta contiene todos los scripts de prueba y configuración del sistema.

## 📁 Estructura

```
tests/
├── Integration/          # Tests de integración
├── setup/               # Scripts de configuración inicial
├── logs/                # Logs de ejecución de pruebas
├── Feature/             # Tests de Laravel (features)
├── Unit/                # Tests unitarios de Laravel
└── README.md            # Este archivo
```

---

## 🔧 Scripts de Configuración (`/setup`)

Estos scripts preparan el sistema con datos iniciales.

### `insertar_roles.php`
**Propósito:** Inserta los 3 roles básicos del sistema  
**Cuándo usar:** Primera vez que configuras el sistema  
**Roles creados:**
- Administrador (id_rol = 1)
- Vendedor (id_rol = 2)
- Cliente (id_rol = 3)

**Uso:**
```bash
php tests/setup/insertar_roles.php
```

---

### `insertar_datos_productos.php`
**Propósito:** Inserta productos de ejemplo en el catálogo  
**Cuándo usar:** Para tener datos de prueba en productos  
**Datos creados:**
- 12+ productos de ejemplo
- Con categorías y marcas asociadas

**Uso:**
```bash
php tests/setup/insertar_datos_productos.php
```

---

### `insertar_datos_prueba.php`
**Propósito:** Inserta categorías y marcas de prueba  
**Cuándo usar:** Si las tablas de categorías/marcas están vacías  
**Datos creados:**
- 8 categorías
- 10 marcas

**Uso:**
```bash
php tests/setup/insertar_datos_prueba.php
```

---

### `crear_bd_pruebas.php`
**Propósito:** Crea una base de datos de pruebas separada  
**Cuándo usar:** Para testing sin afectar datos reales  
**Resultado:** Base de datos `nexus_test` creada

**Uso:**
```bash
php tests/setup/crear_bd_pruebas.php
```

---

### `crear_tabla_password_reset.php`
**Propósito:** Crea tabla para tokens de recuperación de contraseña  
**Cuándo usar:** Si la tabla no existe  
**Resultado:** Tabla `password_reset_tokens` creada

**Uso:**
```bash
php tests/setup/crear_tabla_password_reset.php
```

---

### `actualizar_enum_pagos.php`
**Propósito:** Actualiza ENUM de métodos de pago para incluir Stripe  
**Cuándo usar:** Después de implementar Stripe  
**Resultado:** Campo `metodo_pago` incluye 'stripe'

**Uso:**
```bash
php tests/setup/actualizar_enum_pagos.php
```

---

## 🧪 Tests de Integración (`/Integration`)

Estos scripts prueban funcionalidades completas del sistema.

### `test_carrito.php`
**Propósito:** Prueba completa del sistema de carrito  
**Qué prueba:**
- ✅ Ver carrito
- ✅ Agregar productos
- ✅ Actualizar cantidades
- ✅ Eliminar productos
- ✅ Vaciar carrito
- ✅ Cálculo de totales

**Uso:**
```bash
php tests/Integration/test_carrito.php
```

**Resultado esperado:**
```
✅ Todas las operaciones del carrito funcionan
✅ Totales calculados correctamente
✅ Validaciones funcionando
```

---

### `test_pagos_completo.php`
**Propósito:** Prueba completa del sistema de pagos  
**Qué prueba:**
- ✅ Crear pagos con PayPal
- ✅ Crear pagos con Stripe
- ✅ Crear pagos con otros métodos
- ✅ Confirmar pagos
- ✅ Actualizar estados
- ✅ Relaciones con pedidos

**Uso:**
```bash
php tests/Integration/test_pagos_completo.php
```

**Resultado esperado:**
```
✅ 5 pagos creados exitosamente
✅ Estados actualizados correctamente
✅ Relaciones funcionando
```

---

### `test_sistema_pagos.php`
**Propósito:** Verifica estructura del sistema de pagos  
**Qué prueba:**
- ✅ Controlador existe
- ✅ Modelo existe
- ✅ Métodos implementados
- ✅ Configuración correcta
- ✅ Tabla en BD

**Uso:**
```bash
php tests/Integration/test_sistema_pagos.php
```

---

### `test_crud_completo.php`
**Propósito:** Prueba operaciones CRUD en todas las entidades  
**Qué prueba:**
- ✅ Crear registros
- ✅ Leer registros
- ✅ Actualizar registros
- ✅ Eliminar registros
- ✅ Validaciones

**Entidades probadas:**
- Categorías
- Marcas
- Productos
- Usuarios

**Uso:**
```bash
php tests/Integration/test_crud_completo.php
```

---

### `test_admin_routes.php`
**Propósito:** Verifica que todas las rutas del panel admin existan  
**Qué prueba:**
- ✅ Controladores existen
- ✅ Modelos existen
- ✅ Vistas existen
- ✅ Tablas en BD existen

**Uso:**
```bash
php tests/Integration/test_admin_routes.php
```

---

### `test_registro.php`
**Propósito:** Prueba el registro de usuarios  
**Qué prueba:**
- ✅ Registro exitoso
- ✅ Validaciones
- ✅ Hash de contraseña
- ✅ Token generado

**Uso:**
```bash
php tests/Integration/test_registro.php
```

---

### `test_conexion.php`
**Propósito:** Prueba conexión básica a la base de datos  
**Qué prueba:**
- ✅ Conexión a MySQL
- ✅ Base de datos existe
- ✅ Tablas existen

**Uso:**
```bash
php tests/Integration/test_conexion.php
```

---

### `verificar_conexion.php`
**Propósito:** Verifica conexión y configuración de Laravel  
**Qué prueba:**
- ✅ Conexión a BD
- ✅ Variables de entorno
- ✅ Configuración correcta

**Uso:**
```bash
php tests/Integration/verificar_conexion.php
```

---

### `verificar_dashboard.php`
**Propósito:** Verifica que el dashboard funcione correctamente  
**Qué prueba:**
- ✅ Estadísticas se calculan
- ✅ Queries funcionan
- ✅ Datos se muestran

**Uso:**
```bash
php tests/Integration/verificar_dashboard.php
```

---

### `verificar_sanctum.php`
**Propósito:** Verifica configuración de Laravel Sanctum  
**Qué prueba:**
- ✅ Sanctum instalado
- ✅ Configuración correcta
- ✅ Tabla de tokens existe

**Uso:**
```bash
php tests/Integration/verificar_sanctum.php
```

---

### `prueba_impacto_db.php`
**Propósito:** Demuestra que las operaciones impactan la BD  
**Qué prueba:**
- ✅ Crear registros en BD
- ✅ Verificar con queries SQL
- ✅ Contar registros antes/después

**Uso:**
```bash
php tests/Integration/prueba_impacto_db.php
```

---

### `demo_impacto_visual.php`
**Propósito:** Demo visual del impacto en BD  
**Qué prueba:**
- ✅ Operaciones CRUD
- ✅ Muestra resultados formateados
- ✅ Evidencia para QA

**Uso:**
```bash
php tests/Integration/demo_impacto_visual.php
```

---

## 🚀 Ejecutar Todas las Pruebas

### Opción 1: Script Automatizado
```bash
php tests/ejecutar_pruebas.php
```

### Opción 2: Manual (Orden Recomendado)

**1. Configuración inicial:**
```bash
php tests/setup/insertar_roles.php
php tests/setup/insertar_datos_productos.php
```

**2. Verificación de conexión:**
```bash
php tests/Integration/verificar_conexion.php
php tests/Integration/test_conexion.php
```

**3. Tests funcionales:**
```bash
php tests/Integration/test_registro.php
php tests/Integration/test_carrito.php
php tests/Integration/test_pagos_completo.php
php tests/Integration/test_crud_completo.php
```

**4. Verificación del panel admin:**
```bash
php tests/Integration/test_admin_routes.php
php tests/Integration/verificar_dashboard.php
```

---

## 📊 Logs

Los logs de ejecución se guardan en `/tests/logs/`:

```
tests/logs/
├── test_carrito_20251130_100000.log
├── test_pagos_20251130_100500.log
└── ...
```

**Formato de log:**
```json
{
  "test": "test_carrito",
  "timestamp": "2025-11-30T10:00:00Z",
  "duration": 2.5,
  "status": "passed",
  "output": "...",
  "errors": []
}
```

---

## 🐛 Troubleshooting

### Error: "Connection refused"
**Solución:** Verifica que MySQL esté corriendo en XAMPP

### Error: "Database not found"
**Solución:** Importa `nexus.sql` en phpMyAdmin

### Error: "Class not found"
**Solución:** Ejecuta `composer install`

### Error: "Permission denied"
**Solución:** Verifica permisos de la carpeta `/tests/logs`

---

## 📝 Crear Nuevos Tests

### Test de Integración

Crea un archivo en `/tests/Integration/`:

```php
<?php
// tests/Integration/test_mi_funcionalidad.php

require_once __DIR__ . '/../../vendor/autoload.php';

use Illuminate\Support\Facades\DB;

echo "🧪 Probando Mi Funcionalidad...\n\n";

try {
    // Tu código de prueba aquí
    
    echo "✅ Prueba exitosa\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
```

### Test Unitario de Laravel

Crea un archivo en `/tests/Unit/`:

```php
<?php
// tests/Unit/MiTest.php

namespace Tests\Unit;

use Tests\TestCase;

class MiTest extends TestCase
{
    public function test_mi_funcionalidad()
    {
        $this->assertTrue(true);
    }
}
```

Ejecuta con:
```bash
php artisan test tests/Unit/MiTest.php
```

---

## ✅ Checklist de Testing

### Pre-deployment
- [ ] Todos los tests de setup ejecutados
- [ ] Todos los tests de integración pasan
- [ ] Tests unitarios de Laravel pasan
- [ ] No hay errores en logs
- [ ] Base de datos tiene datos de prueba

### Post-deployment
- [ ] Verificar conexión en producción
- [ ] Ejecutar smoke tests
- [ ] Verificar logs de errores
- [ ] Monitorear performance

---

## 📞 Soporte

Para más información sobre testing:
- [Manual de Testing](../docs/MANUAL_TESTING.md)
- [Documentación de API](../docs/API.md)
- [Arquitectura del Sistema](../docs/ARQUITECTURA.md)

---

**Última actualización:** Noviembre 30, 2025  
**Mantenido por:** Equipo de Desarrollo Nexus
