<?php
// Cargar el manejador de variables de entorno
require_once __DIR__ . '/../app/core/EnvLoader.php';

// Cargar las variables del archivo .env
EnvLoader::load();

// Configuración de URL base
// Si no hay BASE_URL en el .env, detectar automáticamente
if (EnvLoader::has('BASE_URL')) {
    define('BASE_URL', EnvLoader::get('BASE_URL'));
} else {
    // Detección automática del protocolo y host
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
    
    // Si está forzado HTTPS en .env
    if (EnvLoader::get('FORCE_HTTPS', false)) {
        $protocol = 'https://';
    }
    
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    define('BASE_URL', $protocol . $host);
}

// Configuración de Base de Datos
define('DB_HOST', EnvLoader::get('DB_HOST', 'localhost'));
define('DB_USER', EnvLoader::get('DB_USER', 'root'));
define('DB_PASS', EnvLoader::get('DB_PASS', ''));
define('DB_NAME', EnvLoader::get('DB_NAME', 'gestion_medicinas'));

// Configuración de la Aplicación
define('APP_NAME', EnvLoader::get('APP_NAME', 'MediPlus - Farmacia Hospitalaria'));
define('APP_ENV', EnvLoader::get('APP_ENV', 'development'));
define('APP_DEBUG', EnvLoader::get('APP_DEBUG', true));

// Configuración adicional para nginx
define('REWRITE_ENABLED', true); // Para usar URLs amigables
define('ENVIRONMENT', EnvLoader::get('APP_ENV', 'development')); // Alias para compatibilidad

// Timezone
date_default_timezone_set(EnvLoader::get('APP_TIMEZONE', 'America/Bogota'));

// Configuración de Archivos
define('MAX_UPLOAD_SIZE', EnvLoader::get('MAX_UPLOAD_SIZE', 10485760)); // 10MB por defecto
define('ALLOWED_UPLOAD_TYPES', EnvLoader::get('ALLOWED_UPLOAD_TYPES', 'pdf,jpg,jpeg,png'));
define('UPLOADS_PATH', EnvLoader::get('UPLOADS_PATH', 'public/uploads'));

// Configuración de Sesión
define('SESSION_LIFETIME', EnvLoader::get('SESSION_LIFETIME', 3600)); // 1 hora
define('SESSION_NAME', EnvLoader::get('SESSION_NAME', 'mediplus_session'));

// Configuración de Seguridad
define('ENABLE_RATE_LIMITING', EnvLoader::get('ENABLE_RATE_LIMITING', true));
define('MAX_LOGIN_ATTEMPTS', EnvLoader::get('MAX_LOGIN_ATTEMPTS', 5));
define('LOGIN_LOCKOUT_TIME', EnvLoader::get('LOGIN_LOCKOUT_TIME', 300)); // 5 minutos

// Configuración de Logs
define('LOG_LEVEL', EnvLoader::get('LOG_LEVEL', 'debug'));
define('LOG_PATH', EnvLoader::get('LOG_PATH', 'logs/'));
define('ENABLE_DB_LOGGING', EnvLoader::get('ENABLE_DB_LOGGING', true));

// Configuración de Cache
define('CACHE_DRIVER', EnvLoader::get('CACHE_DRIVER', 'file'));
define('CACHE_LIFETIME', EnvLoader::get('CACHE_LIFETIME', 3600));

// Helper function para obtener variables de entorno fácilmente
function env($key, $default = null) {
    return EnvLoader::get($key, $default);
}

// Configurar sesión con nombre personalizado
if (session_status() === PHP_SESSION_NONE) {
    session_name(SESSION_NAME);
    
    // Configuración de seguridad de sesión
    ini_set('session.cookie_httponly', '1');
    ini_set('session.use_only_cookies', '1');
    ini_set('session.cookie_secure', EnvLoader::get('FORCE_HTTPS', false) ? '1' : '0');
    ini_set('session.gc_maxlifetime', SESSION_LIFETIME);
}

// Configurar límites de PHP basados en .env
if (EnvLoader::has('MAX_UPLOAD_SIZE')) {
    ini_set('upload_max_filesize', MAX_UPLOAD_SIZE);
    ini_set('post_max_size', MAX_UPLOAD_SIZE * 1.2); // 20% más para overhead
}
?>