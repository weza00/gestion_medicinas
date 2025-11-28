<?php
class HomeController extends Controller {
    private $medicamentoModelo;

    public function __construct() {
        $this->medicamentoModelo = $this->model('Medicamento');
    }

    public function index() {
        // Obtener medicamentos para mostrar en la página principal
        $todosMedicamentos = $this->medicamentoModelo->obtenerTodos();
        
        // Agrupar por categoría
        $medicamentosPorCategoria = [];
        foreach ($todosMedicamentos as $med) {
            if (!isset($medicamentosPorCategoria[$med->categoria_nombre])) {
                $medicamentosPorCategoria[$med->categoria_nombre] = [];
            }
            $medicamentosPorCategoria[$med->categoria_nombre][] = $med;
        }

        $datos = [
            'titulo' => 'Bienvenido al Sistema de Gestión de Medicamentos',
            'medicamentos_agrupados' => $medicamentosPorCategoria
        ];
        
        $this->view('home/index', $datos);
    }
}
?>