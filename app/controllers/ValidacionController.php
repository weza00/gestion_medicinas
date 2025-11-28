<?php
class ValidacionController extends Controller {
    private $pedidoModelo;

    public function __construct() {
        // SEGURIDAD: Solo entra el Validador
        if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] != 'validador') {
            die('Acceso denegado. Solo personal autorizado.');
        }
        $this->pedidoModelo = $this->model('Pedido');
    }

    public function index() {
        $pendientes = $this->pedidoModelo->obtenerPendientes();
        $this->view('validacion/index', ['pendientes' => $pendientes]);
    }

    public function aprobar($id) {
        // 1. Generar Código Único (Ej: MED-A1B2C3)
        $codigo = 'MED-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 6));

        // 2. Actualizar BD
        if ($this->pedidoModelo->actualizarEstado($id, 'aprobado', $codigo)) {
            header('location: ' . BASE_URL . '/validacion/index');
        } else {
            die('Error al aprobar');
        }
    }

    public function rechazar($id) {
        if ($this->pedidoModelo->actualizarEstado($id, 'rechazado', null)) {
            header('location: ' . BASE_URL . '/validacion/index');
        } else {
            die('Error al rechazar');
        }
    }
}
?>