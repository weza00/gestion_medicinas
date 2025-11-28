<?php
class Medicamento {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function obtenerTodos() {
        // Hacemos un JOIN para traer también el nombre de la categoría
        $this->db->query("SELECT m.*, c.nombre as categoria_nombre 
                          FROM medicamentos m 
                          INNER JOIN categorias c ON m.categoria_id = c.id
                          ORDER BY m.id DESC");
        return $this->db->registers();
    }

    public function agregar($datos) {
        $this->db->query('INSERT INTO medicamentos (categoria_id, nombre, descripcion, presentacion, precio, stock, estado) 
                          VALUES (:categoria_id, :nombre, :descripcion, :presentacion, :precio, :stock, :estado)');

        $this->db->bind(':categoria_id', $datos['categoria_id']);
        $this->db->bind(':nombre', $datos['nombre']);
        $this->db->bind(':descripcion', $datos['descripcion']);
        $this->db->bind(':presentacion', $datos['presentacion']);
        $this->db->bind(':precio', $datos['precio']);
        $this->db->bind(':stock', $datos['stock']);
        $this->db->bind(':estado', 1); // 1 = Activo

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function obtenerPorId($id) {
        $this->db->query('SELECT * FROM medicamentos WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->register();
    }
}
?>