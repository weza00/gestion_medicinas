<?php
class CatalogoController extends Controller {
    private $medicamentoModelo;

    public function __construct() {
        $this->medicamentoModelo = $this->model('Medicamento');
    }

    public function index() {
        // 1. Obtener todas las medicinas
        $todosMedicamentos = $this->medicamentoModelo->obtenerTodos();
        
        // 2. Agruparlas por Categoría
        $medicamentosPorCategoria = [];

        foreach ($todosMedicamentos as $med) {
            // Si la categoría no existe en el array, la creamos
            if (!isset($medicamentosPorCategoria[$med->categoria_nombre])) {
                $medicamentosPorCategoria[$med->categoria_nombre] = [];
            }
            // Agregamos el medicamento a su categoría correspondiente
            $medicamentosPorCategoria[$med->categoria_nombre][] = $med;
        }

        $datos = [
            'medicamentos_agrupados' => $medicamentosPorCategoria
        ];

        $this->view('catalogo/index', $datos);
    }

}
?>