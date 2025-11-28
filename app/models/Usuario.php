<?php
class Usuario {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Registrar un nuevo usuario
    public function registrar($datos) {
        // CORRECCIÓN: Cambiado 'users' por 'usuarios'
        $this->db->query('INSERT INTO usuarios (nombre, email, password, rol) VALUES (:nombre, :email, :password, :rol)');
        
        $password_hash = password_hash($datos['password'], PASSWORD_DEFAULT);

        $this->db->bind(':nombre', $datos['nombre']);
        $this->db->bind(':email', $datos['email']);
        $this->db->bind(':password', $password_hash);
        // Tu SQL tiene un valor default 'paciente', pero lo enviamos explícito para seguridad
        $this->db->bind(':rol', 'paciente'); 

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Login
    public function login($email, $password) {
        // CORRECCIÓN: Cambiado 'users' por 'usuarios'
        $this->db->query('SELECT * FROM usuarios WHERE email = :email');
        $this->db->bind(':email', $email);
        
        $fila = $this->db->register();

        if ($fila) {
            if (password_verify($password, $fila->password)) {
                return $fila;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    // Verificar email
    public function obtenerUsuarioPorEmail($email) {
        // CORRECCIÓN: Cambiado 'users' por 'usuarios'
        $this->db->query('SELECT * FROM usuarios WHERE email = :email');
        $this->db->bind(':email', $email);
        $this->db->execute();
        if ($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
}
?>