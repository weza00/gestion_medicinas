<?php
class PedidoController extends Controller {
    private $pedidoModelo;

    public function __construct() {
        // Verificar si está logueado
        if (!isset($_SESSION['user_id'])) {
            header('location: ' . BASE_URL . '/auth/login');
            exit();
        }
        $this->pedidoModelo = $this->model('Pedido');
    }

    // Muestra el formulario de subida de receta
    public function checkout() {
        if (empty($_SESSION['carrito'])) {
            header('location: ' . BASE_URL . '/catalogo');
        }
        $this->view('pedido/checkout');
    }

    // Procesa el formulario
    public function procesar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // 1. Manejo del Archivo (Receta)
            if (isset($_FILES['receta']) && $_FILES['receta']['error'] == 0) {
                
                $archivo = $_FILES['receta'];
                $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
                // Generar nombre único para no sobrescribir archivos (ej: receta_15_20231124.pdf)
                $nombre_guardado = 'receta_' . $_SESSION['user_id'] . '_' . time() . '.' . $extension;
                $ruta_destino = '../public/uploads/' . $nombre_guardado;

                // Mover archivo a la carpeta uploads
                if (move_uploaded_file($archivo['tmp_name'], $ruta_destino)) {
                    
                    // 2. Guardar Pedido en BD
                    $pedido_id = $this->pedidoModelo->crear($_SESSION['user_id'], $nombre_guardado);

                    if ($pedido_id) {
                        // 3. Guardar Detalles (Productos)
                        foreach ($_SESSION['carrito'] as $prod_id => $cantidad) {
                            $this->pedidoModelo->agregarDetalle($pedido_id, $prod_id, $cantidad);
                        }

                        // 4. Vaciar Carrito y Redirigir
                        unset($_SESSION['carrito']);
                        header('location: ' . BASE_URL . '/pedido/exito');

                    } else {
                        die("Error al guardar en base de datos");
                    }

                } else {
                    die("Error al subir el archivo. Verifique permisos de la carpeta uploads.");
                }

            } else {
                die("Debes subir una receta médica obligatoriamente.");
            }
        }
    }

    public function exito() {
        $this->view('pedido/exito');
    }

    public function mis_pedidos() {
        $pedidos = $this->pedidoModelo->obtenerPorUsuario($_SESSION['user_id']);
        $this->view('pedido/mis_pedidos', ['pedidos' => $pedidos]);
    }
}
?>