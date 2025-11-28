<?php
class Log {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Registrar una acción en el sistema
    public function registrar($usuario_id, $accion, $referencia_id = null) {
        $this->db->query('INSERT INTO logs (usuario_id, accion, fecha, referencia_id) VALUES (:usuario_id, :accion, NOW(), :referencia_id)');
        $this->db->bind(':usuario_id', $usuario_id);
        $this->db->bind(':accion', $accion);
        $this->db->bind(':referencia_id', $referencia_id);
        
        return $this->db->execute();
    }

    // Obtener todos los logs con información de usuarios
    public function obtenerTodos($limite = 100) {
        $this->db->query("SELECT l.*, u.nombre as usuario_nombre, u.email as usuario_email, u.rol as usuario_rol 
                          FROM logs l 
                          INNER JOIN usuarios u ON l.usuario_id = u.id 
                          ORDER BY l.fecha DESC 
                          LIMIT :limite");
        $this->db->bind(':limite', $limite);
        return $this->db->registers();
    }

    // Obtener logs por usuario
    public function obtenerPorUsuario($usuario_id) {
        $this->db->query("SELECT l.*, u.nombre as usuario_nombre 
                          FROM logs l 
                          INNER JOIN usuarios u ON l.usuario_id = u.id 
                          WHERE l.usuario_id = :usuario_id 
                          ORDER BY l.fecha DESC");
        $this->db->bind(':usuario_id', $usuario_id);
        return $this->db->registers();
    }

    // Obtener logs por fecha
    public function obtenerPorFecha($fecha_inicio, $fecha_fin) {
        $this->db->query("SELECT l.*, u.nombre as usuario_nombre, u.rol as usuario_rol 
                          FROM logs l 
                          INNER JOIN usuarios u ON l.usuario_id = u.id 
                          WHERE l.fecha BETWEEN :fecha_inicio AND :fecha_fin 
                          ORDER BY l.fecha DESC");
        $this->db->bind(':fecha_inicio', $fecha_inicio);
        $this->db->bind(':fecha_fin', $fecha_fin);
        return $this->db->registers();
    }
}
?>