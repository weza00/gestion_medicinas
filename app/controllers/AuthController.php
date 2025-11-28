<?php
class AuthController extends Controller {
    // 1. AQUI ESTABA EL ERROR: Declaramos la propiedad primero
    private $usuarioModelo;

    public function __construct() {
        // Ahora sí podemos usarla
        $this->usuarioModelo = $this->model('Usuario');
    }

    public function index() {
        $this->login();
    }

    public function registro() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $datos = [
                'nombre' => trim($_POST['nombre']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'rol' => 'paciente'
            ];

            if ($this->usuarioModelo->registrar($datos)) {
                header('location: ' . BASE_URL . '/auth/login');
            } else {
                die('Algo salió mal al registrar.');
            }
        } else {
            $this->view('auth/registro');
        }
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            $usuarioLogueado = $this->usuarioModelo->login($email, $password);

            if ($usuarioLogueado) {
                $_SESSION['user_id'] = $usuarioLogueado->id;
                $_SESSION['user_email'] = $usuarioLogueado->email;
                $_SESSION['user_nombre'] = $usuarioLogueado->nombre;
                $_SESSION['user_rol'] = $usuarioLogueado->rol;

                header('location: ' . BASE_URL . '/home');
            } else {
                $datos = ['error' => 'Password o email incorrectos'];
                $this->view('auth/login', $datos);
            }
        } else {
            $datos = ['error' => ''];
            $this->view('auth/login', $datos);
        }
    }

    public function logout() {
        session_destroy();
        header('location: ' . BASE_URL . '/auth/login');
    }
}
?>