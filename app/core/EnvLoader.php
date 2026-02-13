<?php
/**
 * Clase simple para manejar variables de entorno desde archivo .env
 * Compatible con sintaxis básica de archivos .env
 */
class EnvLoader {
    private static $variables = [];
    private static $loaded = false;
    
    /**
     * Carga las variables del archivo .env
     * @param string $path Ruta del archivo .env
     * @return bool True si se cargó correctamente
     */
    public static function load($path = null) {
        if (self::$loaded) {
            return true; // Ya cargado
        }
        
        if (!$path) {
            $path = dirname(__DIR__) . '/.env';
        }
        
        if (!file_exists($path)) {
            // Si no existe .env, intentar con .env.example como fallback en desarrollo
            $examplePath = dirname(__DIR__) . '/.env.example';
            if (file_exists($examplePath)) {
                $path = $examplePath;
            } else {
                return false; // No hay archivo de configuración
            }
        }
        
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($lines as $line) {
            // Ignorar comentarios
            if (strpos($line, '#') === 0) {
                continue;
            }
            
            // Separar clave y valor
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                
                // Limpiar espacios
                $key = trim($key);
                $value = trim($value);
                
                // Remover comillas si existen
                $value = self::removeQuotes($value);
                
                // Convertir valores especiales
                $value = self::convertValue($value);
                
                // Almacenar la variable
                self::$variables[$key] = $value;
                
                // También establecer en $_ENV si no existe
                if (!isset($_ENV[$key])) {
                    $_ENV[$key] = $value;
                    putenv("$key=$value");
                }
            }
        }
        
        self::$loaded = true;
        return true;
    }
    
    /**
     * Obtiene el valor de una variable de entorno
     * @param string $key Nombre de la variable
     * @param mixed $default Valor por defecto si no existe
     * @return mixed
     */
    public static function get($key, $default = null) {
        // Primero intentar desde variables del sistema
        if (isset($_ENV[$key])) {
            return self::convertValue($_ENV[$key]);
        }
        
        // Luego desde las variables cargadas
        if (isset(self::$variables[$key])) {
            return self::$variables[$key];
        }
        
        // Finalmente, usar getenv como último recurso
        $value = getenv($key);
        if ($value !== false) {
            return self::convertValue($value);
        }
        
        return $default;
    }
    
    /**
     * Establece una variable de entorno
     * @param string $key
     * @param mixed $value
     */
    public static function set($key, $value) {
        self::$variables[$key] = $value;
        $_ENV[$key] = $value;
        putenv("$key=$value");
    }
    
    /**
     * Verifica si existe una variable de entorno
     * @param string $key
     * @return bool
     */
    public static function has($key) {
        return isset($_ENV[$key]) || isset(self::$variables[$key]) || getenv($key) !== false;
    }
    
    /**
     * Obtiene todas las variables cargadas
     * @return array
     */
    public static function all() {
        return array_merge(self::$variables, $_ENV);
    }
    
    /**
     * Remueve comillas de un valor
     * @param string $value
     * @return string
     */
    private static function removeQuotes($value) {
        if (!$value) {
            return $value;
        }
        
        // Remover comillas dobles
        if (substr($value, 0, 1) === '"' && substr($value, -1) === '"') {
            return substr($value, 1, -1);
        }
        
        // Remover comillas simples
        if (substr($value, 0, 1) === "'" && substr($value, -1) === "'") {
            return substr($value, 1, -1);
        }
        
        return $value;
    }
    
    /**
     * Convierte valores especiales (true, false, null, números)
     * @param string $value
     * @return mixed
     */
    private static function convertValue($value) {
        if (!is_string($value)) {
            return $value;
        }
        
        $lower = strtolower($value);
        
        switch ($lower) {
            case 'true':
            case 'yes':
            case '1':
                return true;
            case 'false':
            case 'no':
            case '0':
                return false;
            case 'null':
            case 'nil':
                return null;
            case '':
                return '';
        }
        
        // Convertir números
        if (is_numeric($value)) {
            return strpos($value, '.') !== false ? (float) $value : (int) $value;
        }
        
        return $value;
    }
    
    /**
     * Crea un archivo .env desde .env.example si no existe
     * @param bool $overwrite Si sobrescribir el archivo existente
     * @return bool
     */
    public static function createFromExample($overwrite = false) {
        $envPath = dirname(__DIR__) . '/.env';
        $examplePath = dirname(__DIR__) . '/.env.example';
        
        if (!$overwrite && file_exists($envPath)) {
            return true; // Ya existe
        }
        
        if (!file_exists($examplePath)) {
            return false; // No hay ejemplo
        }
        
        return copy($examplePath, $envPath);
    }
}
?>