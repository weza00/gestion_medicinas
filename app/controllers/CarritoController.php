<?php
class CarritoController extends Controller {
    private $medicamentoModelo;

    public function __construct() {
        $this->medicamentoModelo = $this->model('Medicamento');
        
        // Inicializar carrito si no existe
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }
    }

    // Muestra lo que hay en el carrito
    public function index() {
        $productos = [];
        $total = 0;

        // Recorrer la sesión para buscar los detalles en la BD
        foreach ($_SESSION['carrito'] as $id => $cantidad) {
            $med = $this->medicamentoModelo->obtenerPorId($id);
            if ($med) {
                $med->cantidad_carrito = $cantidad;
                $med->subtotal = $med->precio * $cantidad;
                $total += $med->subtotal;
                $productos[] = $med;
            }
        }

        $datos = [
            'productos' => $productos,
            'total' => $total
        ];

        $this->view('carrito/index', $datos);
    }

    // Agregar item al carrito
    public function agregar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            
            // Si ya existe, sumamos 1, si no, lo creamos
            if (isset($_SESSION['carrito'][$id])) {
                $_SESSION['carrito'][$id]++;
            } else {
                $_SESSION['carrito'][$id] = 1;
            }

            header('location: ' . BASE_URL . '/catalogo');
        }
    }

    // Vaciar carrito
    public function vaciar() {
        unset($_SESSION['carrito']);
        header('location: ' . BASE_URL . '/catalogo');
    }

    // Eliminar un item específico
    public function eliminar($id) {
        if (isset($_SESSION['carrito'][$id])) {
            unset($_SESSION['carrito'][$id]);
        }
        header('location: ' . BASE_URL . '/carrito');
    }
}
?>