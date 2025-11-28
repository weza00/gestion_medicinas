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
}
?>