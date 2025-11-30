# 🚀 Guía de Instalación - Sistema Nexus

## ✅ Requisitos Previos

Antes de instalar, asegúrate de tener:

- ✅ PHP 8.2 o superior
- ✅ Composer instalado
- ✅ MySQL 8.0 o superior
- ✅ XAMPP, WAMP, o servidor web con PHP y MySQL

---

## 📦 Paso 1: Clonar/Copiar el Proyecto

```bash
# Si usas Git
git clone [url-del-repositorio]

# O simplemente copia la carpeta nexus-backend
```

---

## 🗄️ Paso 2: Configurar Base de Datos

### Opción A: Importar desde phpMyAdmin (RECOMENDADO)

1. Abre phpMyAdmin: `http://localhost/phpmyadmin`
2. Ve a la pestaña "Importar"
3. Selecciona el archivo `Nexus.sql` (en la raíz del proyecto)
4. Haz clic en "Continuar"
5. ¡Listo! La base de datos `nexus` se creará con todas las tablas

### Opción B: Desde línea de comandos

```bash
mysql -u root -p < Nexus.sql
```

---

## ⚙️ Paso 3: Configurar Variables de Entorno

1. Copia el archivo `.env.example` a `.env`:

```bash
cd nexus-backend
copy .env.example .env
```

2. Edita el archivo `.env` con tus credenciales de base de datos:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nexus
DB_USERNAME=root
DB_PASSWORD=          # Tu contraseña de MySQL
```

---

## 📚 Paso 4: Instalar Dependencias

```bash
composer install
```

---

## 🔑 Paso 5: Generar Clave de Aplicación

```bash
php artisan key:generate
```

---

## 🧪 Paso 6: Verificar Instalación

```bash
php verificar_conexion.php
```

---

## 🚀 Paso 7: Iniciar el Servidor

```bash
php artisan serve
```

**El servidor se iniciará en:** `http://127.0.0.1:8000`

---

## 🧪 Paso 8: Probar la API

Abre en tu navegador: `http://127.0.0.1:8000/test.html`

---

**Versión:** 1.0  
**Fecha:** Noviembre 2025  
**Framework:** Laravel 12.39.0
