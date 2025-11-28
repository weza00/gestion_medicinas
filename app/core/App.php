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
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
    }
}
?>