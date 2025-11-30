# 🚀 GUÍA RÁPIDA: Registro en PayPal y Stripe

## ⚡ PARA QA Y LÍDERES TÉCNICOS

**IMPORTANTE:** El backend YA ESTÁ 100% LISTO. Solo necesitan obtener credenciales (GRATIS) y pegarlas en el archivo `.env`. **NO TOCAN CÓDIGO.**

---

## 📋 Resumen Ejecutivo

| Paso | Qué hacer | Tiempo | Costo |
|------|-----------|--------|-------|
| 1 | Registrarse en PayPal Developer | 5 min | GRATIS |
| 2 | Registrarse en Stripe | 3 min | GRATIS |
| 3 | Copiar credenciales al `.env` | 2 min | GRATIS |
| 4 | Probar el sistema | 5 min | GRATIS |

**TOTAL: 15 minutos, $0 USD**

---

## 🟦 OPCIÓN 1: PayPal (Recomendado para empezar)

### Paso 1: Crear Cuenta de Desarrollador

1. **Ir a:** https://developer.paypal.com
2. **Click en:** "Log in to Dashboard" (arriba derecha)
3. **Si no tienes cuenta:**
   - Click en "Sign Up"
   - Usar tu email personal
   - Completar el formulario (2 minutos)
4. **Si ya tienes cuenta PayPal personal:**
   - Usar esas mismas credenciales

### Paso 2: Crear una App

1. **Una vez dentro del dashboard:**
   - Click en "Apps & Credentials" (menú izquierdo)
   - Asegúrate de estar en modo **"Sandbox"** (arriba)
   - Click en "Create App" (botón azul)

2. **Llenar el formulario:**
   - App Name: `Nexus Backend Test`
   - App Type: `Merchant`
   - Click "Create App"

### Paso 3: Copiar Credenciales

Verás una pantalla con tus credenciales:

```
Client ID: AabcXYZ123... (largo)
Secret: EFGhijk456... (largo)
```

**COPIAR ESTAS DOS COSAS** ⬆️

### Paso 4: Pegar en el `.env`

1. **Abrir el archivo:** `nexus-backend/.env`
2. **Buscar estas líneas:**
   ```env
   PAYPAL_MODE=sandbox
   PAYPAL_SANDBOX_CLIENT_ID=
   PAYPAL_SANDBOX_CLIENT_SECRET=
   ```
3. **Pegar tus credenciales:**
   ```env
   PAYPAL_MODE=sandbox
   PAYPAL_SANDBOX_CLIENT_ID=AabcXYZ123...
   PAYPAL_SANDBOX_CLIENT_SECRET=EFGhijk456...
   ```
4. **Guardar el archivo**

### ✅ ¡Listo! PayPal configurado

---

## 🟪 OPCIÓN 2: Stripe (Más moderno)

### Paso 1: Crear Cuenta

1. **Ir a:** https://dashboard.stripe.com/register
2. **Llenar el formulario:**
   - Email
   - Nombre completo
   - Contraseña
   - País
3. **Click en:** "Create account"
4. **Verificar email** (revisar bandeja de entrada)

### Paso 2: Obtener Credenciales

1. **Una vez dentro del dashboard:**
   - Verás un mensaje: "Activate payments"
   - **IGNORAR** ese mensaje (es para producción)
   - Click en "Developers" (menú superior derecho)
   - Click en "API keys" (menú izquierdo)

2. **Verás dos tipos de keys:**
   - **Publishable key:** `pk_test_...` (empieza con pk_test)
   - **Secret key:** `sk_test_...` (empieza con sk_test)
   - Click en "Reveal test key" para ver la Secret key

### Paso 3: Copiar Credenciales

```
Publishable key: pk_test_51ABC...
Secret key: sk_test_51XYZ...
```

**COPIAR ESTAS DOS COSAS** ⬆️

### Paso 4: Pegar en el `.env`

1. **Abrir el archivo:** `nexus-backend/.env`
2. **Buscar estas líneas:**
   ```env
   STRIPE_PUBLISHABLE_KEY=
   STRIPE_SECRET_KEY=
   ```
3. **Pegar tus credenciales:**
   ```env
   STRIPE_PUBLISHABLE_KEY=pk_test_51ABC...
   STRIPE_SECRET_KEY=sk_test_51XYZ...
   ```
4. **Guardar el archivo**

### ✅ ¡Listo! Stripe configurado

---

## 🧪 PROBAR QUE FUNCIONA

### Opción 1: Desde el navegador

1. **Iniciar el servidor:**
   ```bash
   cd nexus-backend
   php artisan serve
   ```

2. **Abrir:** http://127.0.0.1:8000/test.html

3. **Hacer una prueba de pago:**
   - Registrarse como usuario
   - Agregar productos al carrito
   - Crear pedido
   - Intentar pagar

### Opción 2: Script de prueba

```bash
cd nexus-backend
php tests/Integration/test_pagos_completo.php
```

**Resultado esperado:**
```
✅ PayPal configurado correctamente
✅ Stripe configurado correctamente
✅ 5 pagos de prueba creados
```

---

## 🎯 TARJETAS DE PRUEBA

### Para Stripe (modo test)

Usar estas tarjetas FALSAS para probar:

| Tarjeta | Número | Resultado |
|---------|--------|-----------|
| Visa exitosa | `4242 4242 4242 4242` | ✅ Aprobada |
| Visa declinada | `4000 0000 0000 0002` | ❌ Rechazada |
| Mastercard | `5555 5555 5555 4444` | ✅ Aprobada |

**CVV:** Cualquier 3 dígitos (ej: 123)  
**Fecha:** Cualquier fecha futura (ej: 12/25)  
**ZIP:** Cualquier código (ej: 12345)

### Para PayPal (modo sandbox)

PayPal te da cuentas de prueba automáticamente:

1. En el dashboard de PayPal Developer
2. Click en "Sandbox" → "Accounts"
3. Verás 2 cuentas:
   - **Personal** (comprador)
   - **Business** (vendedor)
4. Usar la cuenta Personal para hacer pagos de prueba

---

## ❌ PROBLEMAS COMUNES

### "No puedo ver mis credenciales"

**PayPal:**
- Asegúrate de estar en modo "Sandbox" (no "Live")
- Si no ves el botón "Create App", refresca la página

**Stripe:**
- Asegúrate de estar viendo "Test mode" (switch arriba derecha)
- Si no ves las keys, click en "Developers" → "API keys"

### "El sistema dice que las credenciales son inválidas"

1. Verifica que copiaste TODO el texto (son muy largos)
2. Verifica que no haya espacios al inicio o final
3. Verifica que estés en modo TEST/SANDBOX
4. Reinicia el servidor: `php artisan serve`

### "No sé dónde está el archivo .env"

```
nexus-backend/
└── .env  ← AQUÍ
```

Si no lo ves, puede estar oculto. En Windows:
- Abrir carpeta en explorador
- Ver → Mostrar → Elementos ocultos

---

## 📞 CONTACTO DE EMERGENCIA

Si algo no funciona:

1. **Verificar que el servidor esté corriendo:**
   ```bash
   php artisan serve
   ```

2. **Ver logs de errores:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

3. **Ejecutar script de diagnóstico:**
   ```bash
   php tests/Integration/test_sistema_pagos.php
   ```

---

## ✅ CHECKLIST FINAL

Antes de decir "está listo":

- [ ] Cuenta de PayPal Developer creada
- [ ] App de PayPal creada
- [ ] Client ID y Secret copiados al `.env`
- [ ] Cuenta de Stripe creada
- [ ] Publishable y Secret key copiados al `.env`
- [ ] Servidor Laravel corriendo
- [ ] Script de prueba ejecutado exitosamente
- [ ] Pago de prueba realizado desde el navegador

---

## 🎉 MENSAJE PARA EL EQUIPO

**El backend está 100% completo y funcional.**

Lo único que necesitan es:
1. Registrarse en PayPal/Stripe (GRATIS, 5 minutos cada uno)
2. Copiar las credenciales al `.env`
3. Probar

**NO necesitan:**
- ❌ Modificar código
- ❌ Instalar nada adicional
- ❌ Configurar servidores
- ❌ Pagar nada

**TODO el trabajo duro ya está hecho.** Solo falta este paso administrativo de 15 minutos.

---

## 📚 DOCUMENTACIÓN ADICIONAL

- [FAQ de Pagos](FAQ_PAGOS.md) - Preguntas frecuentes
- [Sistema de Pagos Completo](SISTEMA_PAGOS.md) - Documentación técnica
- [Manual de Testing](MANUAL_TESTING.md) - Guía de pruebas
- [API Reference](API.md) - Endpoints de pagos

---

**Última actualización:** Noviembre 30, 2025  
**Tiempo estimado:** 15 minutos  
**Costo:** $0 USD  
**Dificultad:** ⭐ Muy fácil
