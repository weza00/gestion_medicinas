# Configuración con Variables de Entorno - MediPlus

## 📝 Descripción
El sistema MediPlus ahora soporta configuración a través de variables de entorno usando archivos `.env`, lo que permite mayor flexibilidad y seguridad en el deployment.

## 🚀 Configuración Inicial

### 1. Crear archivo de configuración
```bash
# Copiar el archivo de ejemplo
cp .env.example .env

# Editar con sus valores específicos
nano .env  # o el editor de su preferencia
```

### 2. Configurar variables principales
```bash
# Base de datos
DB_HOST=localhost
DB_USER=tu_usuario
DB_PASS=tu_contraseña_segura
DB_NAME=gestion_medicinas

# Aplicación
APP_ENV=production
APP_DEBUG=false
```

## 📋 Variables Disponibles

### 🔧 Base de Datos
| Variable | Descripción | Valor por defecto |
|----------|-------------|-------------------|
| `DB_HOST` | Servidor de base de datos | `localhost` |
| `DB_USER` | Usuario de base de datos | `root` |
| `DB_PASS` | Contraseña de base de datos | _(vacío)_ |
| `DB_NAME` | Nombre de la base de datos | `gestion_medicinas` |

### 🌐 Aplicación
| Variable | Descripción | Valor por defecto |
|----------|-------------|-------------------|
| `APP_NAME` | Nombre de la aplicación | `MediPlus - Farmacia Hospitalaria` |
| `APP_ENV` | Entorno (`development`, `production`) | `development` |
| `APP_DEBUG` | Habilitar debug | `true` |
| `APP_TIMEZONE` | Zona horaria | `America/Bogota` |

### 🔗 URLs y Dominios
| Variable | Descripción | Valor por defecto |
|----------|-------------|-------------------|
| `BASE_URL` | URL base de la aplicación | _(detectado automáticamente)_ |
| `FORCE_HTTPS` | Forzar HTTPS | `false` |

### 📁 Archivos y Uploads
| Variable | Descripción | Valor por defecto |
|----------|-------------|-------------------|
| `MAX_UPLOAD_SIZE` | Tamaño máximo de archivo (bytes) | `10485760` (10MB) |
| `ALLOWED_UPLOAD_TYPES` | Tipos de archivo permitidos | `pdf,jpg,jpeg,png` |
| `UPLOADS_PATH` | Directorio de uploads | `public/uploads` |

### 🔐 Sesión y Seguridad
| Variable | Descripción | Valor por defecto |
|----------|-------------|-------------------|
| `SESSION_LIFETIME` | Duración de sesión (segundos) | `3600` (1 hora) |
| `SESSION_NAME` | Nombre de la cookie de sesión | `mediplus_session` |
| `ENABLE_RATE_LIMITING` | Habilitar limitación de intentos | `true` |
| `MAX_LOGIN_ATTEMPTS` | Máximos intentos de login | `5` |
| `LOGIN_LOCKOUT_TIME` | Tiempo de bloqueo (segundos) | `300` (5 min) |

### 📝 Logs y Cache
| Variable | Descripción | Valor por defecto |
|----------|-------------|-------------------|
| `LOG_LEVEL` | Nivel de logs | `debug` |
| `LOG_PATH` | Directorio de logs | `logs/` |
| `ENABLE_DB_LOGGING` | Logs en base de datos | `true` |
| `CACHE_DRIVER` | Driver de cache | `file` |
| `CACHE_LIFETIME` | Duración del cache (segundos) | `3600` |

## 🔧 Uso en el Código

### Función Helper
```php
// Obtener variable con valor por defecto
$dbHost = env('DB_HOST', 'localhost');

// Verificar si existe una variable
if (env('APP_DEBUG', false)) {
    echo "Modo debug activado";
}
```

### Clase EnvLoader
```php
// Cargar archivo .env personalizado
EnvLoader::load('/ruta/personalizada/.env');

// Obtener variable
$value = EnvLoader::get('MI_VARIABLE', 'valor_por_defecto');

// Establecer variable (runtime)
EnvLoader::set('NUEVA_VAR', 'valor');

// Verificar existencia
if (EnvLoader::has('MI_VARIABLE')) {
    // La variable existe
}
```

## 🛠️ Configuración por Entorno

### 🏠 Desarrollo Local
```bash
APP_ENV=development
APP_DEBUG=true
BASE_URL=http://localhost
DB_HOST=localhost
DB_USER=root
DB_PASS=
FORCE_HTTPS=false
```

### 🚀 Producción
```bash
APP_ENV=production
APP_DEBUG=false
BASE_URL=https://tu-dominio.com
DB_HOST=tu-servidor-db
DB_USER=usuario_produccion
DB_PASS=contraseña_muy_segura
FORCE_HTTPS=true
SESSION_LIFETIME=7200
MAX_LOGIN_ATTEMPTS=3
```

### 🧪 Testing
```bash
APP_ENV=testing
APP_DEBUG=true
DB_NAME=gestion_medicinas_test
CACHE_DRIVER=array
LOG_LEVEL=error
```

## 🔒 Seguridad

### ✅ Buenas Prácticas
1. **Nunca** subir el archivo `.env` al repositorio
2. Usar contraseñas fuertes en `DB_PASS`
3. Establecer `APP_DEBUG=false` en producción
4. Usar `FORCE_HTTPS=true` en producción
5. Configurar `SESSION_LIFETIME` apropiadamente

### 🚫 Archivos Ignorados
El `.gitignore` incluye:
```
.env
logs/
public/uploads/*
cache/
```

## 📦 Deployment

### 1. Servidor de Producción
```bash
# 1. Copiar archivos (excluyendo .env)
rsync -av --exclude='.env' ./src/ servidor:/var/www/html/mediplus/

# 2. Crear .env en servidor
cp .env.example .env
nano .env  # Configurar variables de producción

# 3. Configurar permisos
chmod 600 .env
chown www-data:www-data .env
```

### 2. Docker (opcional)
```dockerfile
# Dockerfile
COPY .env.example .env
RUN chmod 600 .env
```

### 3. Script de Deployment
```bash
#!/bin/bash
# Verificar que existe .env
if [ ! -f ".env" ]; then
    echo "Creando .env desde ejemplo..."
    cp .env.example .env
    echo "¡IMPORTANTE! Configurar variables en .env"
fi
```

## 🐛 Troubleshooting

### Problemas Comunes

#### 🔴 "No se puede conectar a la base de datos"
**Solución**: Verificar variables `DB_*` en `.env`
```bash
# Verificar configuración
grep "DB_" .env
```

#### 🔴 "Sesión no persiste"
**Solución**: Verificar `SESSION_*` y permisos de directorio
```bash
# Verificar permisos de sesión
ls -la /var/lib/php/sessions/
```

#### 🔴 "CSS no carga"
**Solución**: Verificar `BASE_URL` y configuración web server
```bash
# Verificar URL base
grep "BASE_URL" .env
```

#### 📄 "Archivo .env no encontrado"
El sistema automáticamente usa `.env.example` como fallback en desarrollo.

## 🔄 Migración desde Configuración Anterior

### Paso 1: Crear .env
```bash
cp .env.example .env
```

### Paso 2: Migrar valores
```bash
# Si tenías en config.php:
# define('DB_USER', 'mi_usuario');

# Ahora en .env:
# DB_USER=mi_usuario
```

### Paso 3: Verificar funcionamiento
```bash
# Probar que la aplicación carga correctamente
curl -I http://localhost/
```

---

**💡 Tip**: Usar `env('VARIABLE')` en lugar de variables globales hace el código más testeable y flexible.