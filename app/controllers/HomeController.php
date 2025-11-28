<?php
class HomeController extends Controller {
    public function __construct() {
        // Podríamos cargar modelos aquí si los necesitáramos
    }

    public function index() {
        $datos = [
            'titulo' => 'Bienvenido al Sistema de Gestión de Medicamentos'
        ];
        
        $this->view('home/index', $datos);
    }
}
?>