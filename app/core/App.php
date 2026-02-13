<?php
class App {
    protected $controller = 'HomeController'; // Controlador por defecto
    protected $method = 'index';              // Método por defecto
    protected $params = [];

    public function __construct() {
        $url = $this->getUrl();

        // 1. Buscar Controlador
        if (isset($url[0])) {
            if (file_exists('../app/controllers/' . ucwords($url[0]) . 'Controller.php')) {
                $this->controller = ucwords($url[0]) . 'Controller';
                unset($url[0]);
            }
        }

        require_once '../app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // 2. Buscar Método
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // 3. Obtener Parámetros
        $this->params = $url ? array_values($url) : [];

        // 4. Llamar al método con parámetros
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function getUrl() {
        $url = '';
        
        // Manejar diferentes formas de obtener la URL según el servidor web
        if (isset($_GET['url'])) {
            // Método tradicional con parámetro GET (Apache y nginx con query string)
            $url = $_GET['url'];
        } elseif (isset($_SERVER['PATH_INFO'])) {
            // PATH_INFO disponible
            $url = $_SERVER['PATH_INFO'];
        } elseif (isset($_SERVER['REQUEST_URI'])) {
            // Extraer de REQUEST_URI (más robusto para nginx)
            $requestUri = $_SERVER['REQUEST_URI'];
            
            // Remover query string si existe
            $queryPos = strpos($requestUri, '?');
            if ($queryPos !== false) {
                $requestUri = substr($requestUri, 0, $queryPos);
            }
            
            // Remover el directorio base si la aplicación está en un subdirectorio
            $scriptName = dirname($_SERVER['SCRIPT_NAME']);
            if ($scriptName !== '/' && strpos($requestUri, $scriptName) === 0) {
                $requestUri = substr($requestUri, strlen($scriptName));
            }
            
            $url = ltrim($requestUri, '/');
        }
        
        if (!empty($url)) {
            $url = rtrim($url, '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
        
        return [];
    }
}
?>