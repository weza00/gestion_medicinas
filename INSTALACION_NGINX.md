# Configuración para nginx - Sistema MediPlus

Esta guía te ayudará a configurar correctamente el sistema MediPlus para funcionar con nginx.

## Archivos modificados

### 1. **nginx.conf** - Configuración del servidor web
- Configuración completa para nginx con PHP-FPM
- Manejo de URLs amigables sin .htaccess
- Configuración de seguridad básica
- Cache para archivos estáticos

### 2. **app/core/UrlHelper.php** - Helper para URLs
- Funciones para generar URLs consistentes: `url()`, `asset()`, `redirect()`
- Compatible tanto con Apache como nginx
- Manejo robusto de URLs base

### 3. **config/config.php** - Configuración actualizada
- BASE_URL sin trailing slash para compatibilidad
- Configuraciones adicionales para nginx
- Ejemplos comentados para producción

### 4. **app/core/App.php** - Enrutamiento mejorado
- Detección automatizada de URLs para nginx
- Compatibilidad con diferentes métodos de obtención de rutas
- Manejo de PATH_INFO y REQUEST_URI

## Instrucciones de instalación

### 1. Configurar nginx

```bash
# Copiar la configuración
sudo cp nginx.conf /etc/nginx/sites-available/mediplus

# Activar el sitio
sudo ln -s /etc/nginx/sites-available/mediplus /etc/nginx/sites-enabled/

# Desactivar sitio por defecto (opcional)
sudo rm /etc/nginx/sites-enabled/default

# Verificar configuración
sudo nginx -t

# Reiniciar nginx
sudo systemctl restart nginx
```

### 2. Configurar PHP-FPM

```bash
# Instalar PHP-FPM si no está instalado
sudo apt install php8.0-fpm php8.0-mysql php8.0-mbstring php8.0-curl

# Verificar que esté corriendo
sudo systemctl status php8.0-fpm
```

### 4. Configurar las variables de entorno

```bash
# Crear archivo de configuración
cd /var/www/html/gestion_medicinas
cp .env.example .env

# Editar configuración (IMPORTANTE)
nano .env
```

Configurar al menos estas variables:
```bash
# Base de datos
DB_HOST=localhost
DB_USER=tu_usuario_mysql
DB_PASS=tu_contraseña_segura
DB_NAME=gestion_medicinas

# Aplicación (para producción)
APP_ENV=production  
APP_DEBUG=false
BASE_URL=https://tu-dominio.com
FORCE_HTTPS=true
```

### 5. Ajustar permisos

```bash
# Propietario de archivos (cambiar 'www-data' por su usuario web)
sudo chown -R www-data:www-data /var/www/html/gestion_medicinas/

# Permisos de directorios
sudo find /var/www/html/gestion_medicinas/ -type d -exec chmod 755 {} \;

# Permisos de archivos
sudo find /var/www/html/gestion_medicinas/ -type f -exec chmod 644 {} \;

# Permisos especiales para uploads (crear si no existe)
sudo mkdir -p /var/www/html/gestion_medicinas/public/uploads
sudo chmod 775 /var/www/html/gestion_medicinas/public/uploads

# Permisos especiales para .env (seguridad - IMPORTANTE)
sudo chmod 600 /var/www/html/gestion_medicinas/.env
sudo chown www-data:www-data /var/www/html/gestion_medicinas/.env

# Crear directorio de logs
sudo mkdir -p /var/www/html/gestion_medicinas/logs
sudo chmod 775 /var/www/html/gestion_medicinas/logs
```

**⚠️ SEGURIDAD**: El archivo `.env` contiene contraseñas y debe tener permisos restrictivos (600).

### 6. Configurar archivos de log (opcional)

```bash
# Crear directorios de log si no existen
sudo mkdir -p /var/log/nginx
sudo touch /var/log/nginx/gestion_medicinas_access.log
sudo touch /var/log/nginx/gestion_medicinas_error.log
sudo chown www-data:www-data /var/log/nginx/gestion_medicinas_*
```

## Configuraciones importantes

### 1. **Variables de entorno (.env) - MÁS IMPORTANTE**

El archivo `.env` es donde se configuran todos los parámetros del sistema:

**Desarrollo local:**
```bash
APP_ENV=development
APP_DEBUG=true
BASE_URL=http://localhost
FORCE_HTTPS=false

DB_HOST=localhost
DB_USER=root
DB_PASS=
DB_NAME=gestion_medicinas
```

**Producción:**
```bash
APP_ENV=production
APP_DEBUG=false
BASE_URL=https://su-dominio.com
FORCE_HTTPS=true

DB_HOST=tu-servidor-db
DB_USER=usuario_seguro
DB_PASS=contraseña_muy_segura
DB_NAME=gestion_medicinas

SESSION_LIFETIME=7200
MAX_LOGIN_ATTEMPTS=3
```

**Con subdirectorio:**
```bash
BASE_URL=https://empresa.com/mediplus
```

### 2. **nginx.conf a personalizar**
```nginx
server_name localhost;  # Cambiar por tu dominio
```

### 2. **Ruta del proyecto**
```nginx
root /var/www/html/gestion_medicinas/public;  # Ajustar a tu ruta
```

### 3. **Versión de PHP**
```nginx
fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;  # Ajustar versión
```

### 4. **SSL (para producción)**
Descomenta y configura la sección HTTPS en nginx.conf:
```nginx
server {
    listen 443 ssl http2;
    ssl_certificate /path/to/certificate.crt;
    ssl_certificate_key /path/to/private.key;
    # ... resto de configuración
}
```

## Solución de problemas comunes

### 1. **Error 404 en rutas**
- Verificar que nginx.conf esté correctamente configurado
- Comprobar que la directiva `try_files` esté presente
- Verificar permisos del directorio

### 2. **Error 502 Bad Gateway**
- Verificar que PHP-FPM esté corriendo: `sudo systemctl status php8.0-fmp`
- Comprobar la ruta del socket en nginx.conf
- Revisar logs: `sudo tail -f /var/log/nginx/error.log`

### 3. **CSS/JS no cargan**
- Verificar permisos del directorio `public/`
- Comprobar que los archivos existan en `public/css/` y `public/js/`
- Verificar la configuración de archivos estáticos en nginx.conf

### 4. **Uploads no funcionan**
- Crear directorio: `mkdir -p public/uploads`
- Ajustar permisos: `chmod 775 public/uploads`
- Verificar configuración PHP para uploads

### 5. **URLs no funcionan correctamente**
- Verificar BASE_URL en `config/config.php`
- Comprobar que no tenga trailing slash
- Verificar que coincida con el dominio configurado

## Verificación de funcionamiento

1. **Página principal:** `http://tu-dominio/` debe cargar la página de inicio
2. **Login:** `http://tu-dominio/auth/login` debe mostrar el formulario de login
3. **CSS:** Verificar que los estilos se carguen correctamente
4. **Admin:** Probar acceso al panel de hospital
5. **Uploads:** Probar subida de archivos de receta

## Logs útiles para debugging

```bash
# Logs de nginx
sudo tail -f /var/log/nginx/gestion_medicinas_error.log
sudo tail -f /var/log/nginx/gestion_medicinas_access.log

# Logs de PHP
sudo tail -f /var/log/php8.0-fpm.log

# Logs del sistema
sudo journalctl -u nginx -f
sudo journalctl -u php8.0-fpm -f
```

## Configuración de desarrollo vs producción

### Desarrollo
```bash
# .env para desarrollo
APP_ENV=development
APP_DEBUG=true
APP_TIMEZONE=America/Bogota

BASE_URL=http://localhost
FORCE_HTTPS=false

DB_HOST=localhost
DB_USER=root
DB_PASS=
DB_NAME=gestion_medicinas

LOG_LEVEL=debug
ENABLE_DB_LOGGING=true
```

### Producción  
```bash
# .env para producción
APP_ENV=production
APP_DEBUG=false
APP_TIMEZONE=America/Bogota

BASE_URL=https://su-dominio.com
FORCE_HTTPS=true

DB_HOST=servidor-produccion
DB_USER=usuario_seguro
DB_PASS=contraseña_muy_segura
DB_NAME=gestion_medicinas

SESSION_LIFETIME=7200
MAX_LOGIN_ATTEMPTS=3
LOGIN_LOCKOUT_TIME=600

LOG_LEVEL=error
ENABLE_DB_LOGGING=false
```

**⚠️ Importante**: Nunca subir el archivo `.env` al repositorio. Usar `.env.example` como plantilla.

---

**Nota importante:** Después de hacer cambios en nginx.conf, siempre ejecutar `sudo nginx -t` para verificar la sintaxis antes de reiniciar el servicio.