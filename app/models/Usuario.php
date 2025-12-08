<?php
class Usuario {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Registrar un nuevo usuario
    public function registrar($datos) {
        // CORRECCIÓN: Cambiado 'users' por 'usuarios'
        $this->db->query('INSERT INTO usuarios (nombre, dni, email, password, rol) VALUES (:nombre, :dni, :email, :password, :rol)');
        
        $password_hash = password_hash($datos['password'], PASSWORD_DEFAULT);

        $this->db->bind(':nombre', $datos['nombre']);
        $this->db->bind(':dni', $datos['dni']);
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
    public function login($dni, $password) {
        // CORRECCIÓN: Cambiado 'users' por 'usuarios' y email por DNI. Verificar que el usuario esté activo
        $this->db->query('SELECT * FROM usuarios WHERE dni = :dni AND estado = 1');
        $this->db->bind(':dni', $dni);
        
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

    // Verificar DNI
    public function obtenerUsuarioPorDNI($dni) {
        $this->db->query('SELECT * FROM usuarios WHERE dni = :dni');
        $this->db->bind(':dni', $dni);
        $this->db->execute();
        if ($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Validar cédula ecuatoriana
    public static function validarCedulaEcuatoriana($cedula) {
        // Limpiar la cédula de espacios y caracteres no numéricos
        $cedula = preg_replace('/[^0-9]/', '', $cedula);
        
        // Verificar que tenga exactamente 10 dígitos
        if (strlen($cedula) != 10) {
            return false;
        }
        
        // Verificar que los dos primeros dígitos correspondan a una provincia válida (01-24)
        $provincia = intval(substr($cedula, 0, 2));
        if ($provincia < 1 || $provincia > 24) {
            return false;
        }
        
        // Algoritmo de validación del dígito verificador
        $digitos = str_split($cedula);
        $digitoVerificador = intval($digitos[9]);
        
        $suma = 0;
        for ($i = 0; $i < 9; $i++) {
            $digito = intval($digitos[$i]);
            if ($i % 2 == 0) { // Posiciones impares (0, 2, 4, 6, 8)
                $resultado = $digito * 2;
                if ($resultado > 9) {
                    $resultado -= 9;
                }
                $suma += $resultado;
            } else { // Posiciones pares (1, 3, 5, 7)
                $suma += $digito;
            }
        }
        
        $modulo = $suma % 10;
        $digitoEsperado = ($modulo == 0) ? 0 : (10 - $modulo);
        
        return $digitoEsperado == $digitoVerificador;
    }
}
?>