<?php
class AdminController extends Controller {
    private $medicamentoModelo;
    private $categoriaModelo; // 1. Nueva propiedad

    public function __construct() {
        if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] != 'admin') {
            header('location: ' . BASE_URL . '/home');
            exit();
        }

        $this->medicamentoModelo = $this->model('Medicamento');
        $this->categoriaModelo = $this->model('Categoria'); // 2. Cargar modelo
    }

    public function index() {
        $medicamentos = $this->medicamentoModelo->obtenerTodos();
        $this->view('admin/index', ['medicamentos' => $medicamentos]);
    }

    public function agregar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $datos = [
                'nombre' => trim($_POST['nombre']),
                'categoria_id' => trim($_POST['categoria_id']), // Ahora vendrá del Select
                'descripcion' => trim($_POST['descripcion']),
                'presentacion' => trim($_POST['presentacion']),
                'precio' => trim($_POST['precio']),
                'stock' => trim($_POST['stock']),
                'estado' => 1
            ];

            if ($this->medicamentoModelo->agregar($datos)) {
                header('location: ' . BASE_URL . '/admin/index');
            } else {
                die('Error al guardar');
            }
        } else {
            // 3. Obtener categorías y pasarlas a la vista
            $categorias = $this->categoriaModelo->obtenerTodas();
            $this->view('admin/agregar', ['categorias' => $categorias]);
        }
    }
}
?>