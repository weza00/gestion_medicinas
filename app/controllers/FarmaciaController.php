<?php
class FarmaciaController extends Controller {
    private $pedidoModelo;

    public function __construct() {
        // Solo farmacéuticos
        if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] != 'farmaceutico') {
            die('Acceso denegado. Área de Farmacia.');
        }
        $this->pedidoModelo = $this->model('Pedido');
    }

    public function index() {
        $resultado = null;
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $codigo = trim($_POST['codigo']);
            $pedido = $this->pedidoModelo->obtenerPorCodigo($codigo);

            if ($pedido) {
                if ($pedido->estado == 'aprobado') {
                    $resultado = $pedido;
                } elseif ($pedido->estado == 'entregado') {
                    $error = "Este pedido YA FUE ENTREGADO anteriormente.";
                } else {
                    $error = "El pedido no está aprobado (Estado: " . $pedido->estado . ")";
                }
            } else {
                $error = "Código no encontrado.";
            }
        }

        $this->view('farmacia/index', ['resultado' => $resultado, 'error' => $error]);
    }

    public function entregar($id) {
        if ($this->pedidoModelo->actualizarEstado($id, 'entregado', null)) {
            // Nota: Al entregar no borramos el código, pero en actualizarEstado 
            // teníamos lógica para poner NULL. Para farmacia deberíamos mantener el código
            // o ajustar el modelo. Por simplicidad, asumimos que se actualiza el estado.
            
            // Corrección rápida: Usamos query directa para no borrar el código
            $db = new Database();
            $db->query("UPDATE pedidos SET estado = 'entregado' WHERE id = :id");
            $db->bind(':id', $id);
            $db->execute();

            echo "<script>alert('¡Medicamentos entregados correctamente!'); window.location.href='" . BASE_URL . "/farmacia/index';</script>";
        }
    }
}
?>