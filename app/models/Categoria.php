<?php
class Categoria {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function obtenerTodas() {
        $this->db->query("SELECT * FROM categorias ORDER BY nombre ASC");
        return $this->db->registers();
    }

    public function crear($nombre, $descripcion = '') {
        try {
            $this->db->query("INSERT INTO categorias (nombre, descripcion) VALUES (:nombre, :descripcion)");
            $this->db->bind(':nombre', $nombre);
            $this->db->bind(':descripcion', $descripcion);
            return $this->db->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    public function obtenerPorId($id) {
        $this->db->query("SELECT * FROM categorias WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->register();
    }

    public function actualizar($id, $nombre, $descripcion = '') {
        try {
            $this->db->query("UPDATE categorias SET nombre = :nombre, descripcion = :descripcion WHERE id = :id");
            $this->db->bind(':id', $id);
            $this->db->bind(':nombre', $nombre);
            $this->db->bind(':descripcion', $descripcion);
            return $this->db->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    public function eliminar($id) {
        try {
            // Verificar si hay medicamentos usando esta categoría
            $this->db->query("SELECT COUNT(*) as total FROM medicamentos WHERE categoria_id = :id");
            $this->db->bind(':id', $id);
            $result = $this->db->register();
            
            if ($result->total > 0) {
                return ['error' => 'No se puede eliminar la categoría porque tiene medicamentos asociados.'];
            }

            $this->db->query("DELETE FROM categorias WHERE id = :id");
            $this->db->bind(':id', $id);
            return $this->db->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    public function existe($nombre, $excluyendo_id = null) {
        if ($excluyendo_id) {
            $this->db->query("SELECT COUNT(*) as total FROM categorias WHERE nombre = :nombre AND id != :id");
            $this->db->bind(':id', $excluyendo_id);
        } else {
            $this->db->query("SELECT COUNT(*) as total FROM categorias WHERE nombre = :nombre");
        }
        $this->db->bind(':nombre', $nombre);
        $result = $this->db->register();
        return $result->total > 0;
    }
}
?>