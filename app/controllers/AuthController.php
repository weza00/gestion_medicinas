<?php
class AuthController extends Controller {
    private $usuarioModelo;

    public function __construct() {
        $this->usuarioModelo = $this->model('Usuario');
    }

    public function index() {
        $this->login();
    }

    // Registro eliminado - Solo admins pueden crear cuentas
    public function registro() {
        // Redireccionar al login si alguien intenta acceder al registro
        header('location: ' . BASE_URL . '/auth/login');
        exit();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            $usuarioLogueado = $this->usuarioModelo->login($email, $password);

            if ($usuarioLogueado) {
                // Crear Sesión
                $_SESSION['user_id'] = $usuarioLogueado->id;
                $_SESSION['user_email'] = $usuarioLogueado->email;
                $_SESSION['user_nombre'] = $usuarioLogueado->nombre;
                $_SESSION['user_rol'] = $usuarioLogueado->rol;

                // --- LOGICA DE REDIRECCIÓN INTELIGENTE ---
                switch ($usuarioLogueado->rol) {
                    case 'admin':
                    case 'validador':
                    case 'farmaceutico':
                        header('location: ' . BASE_URL . '/hospital/inicio');
                        break;
                    default: // Pacientes
                        header('location: ' . BASE_URL . '/home');
                        break;
                }
                // -----------------------------------------

            } else {
                $datos = ['error' => 'Email o contraseña incorrectos'];
                $this->view('auth/login', $datos);
            }
        } else {
            $datos = ['error' => ''];
            $this->view('auth/login', $datos);
        }
    }

    public function logout() {
        session_destroy();
        header('location: ' . BASE_URL . '/home');
    }
}
?>