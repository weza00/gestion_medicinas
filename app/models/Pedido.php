<?php
class Pedido {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function crear($usuario_id, $nombre_archivo) {
        // 1. Insertar el pedido principal
        $this->db->query('INSERT INTO pedidos (usuario_id, receta_archivo, estado, creado_en) VALUES (:usuario_id, :archivo, :estado, NOW())');
        
        $this->db->bind(':usuario_id', $usuario_id);
        $this->db->bind(':archivo', $nombre_archivo);
        $this->db->bind(':estado', 'pendiente');

        if ($this->db->execute()) {
            // Necesitamos el ID del pedido que acabamos de crear para insertar los detalles
            // PDO no tiene lastInsertId() en mi clase wrapper simple, así que lo buscamos.
            // (En un entorno prod, agregaría lastInsertId a la clase Database, pero usaremos este truco rápido):
            $this->db->query('SELECT MAX(id) as id FROM pedidos WHERE usuario_id = :uid');
            $this->db->bind(':uid', $usuario_id);
            $fila = $this->db->register();
            return $fila->id;
        } else {
            return false;
        }
    }

    public function agregarDetalle($pedido_id, $medicamento_id, $cantidad) {
        $this->db->query('INSERT INTO pedido_detalle (pedido_id, medicamento_id, cantidad) VALUES (:pid, :mid, :cant)');
        $this->db->bind(':pid', $pedido_id);
        $this->db->bind(':mid', $medicamento_id);
        $this->db->bind(':cant', $cantidad);
        $this->db->execute();
        
        // Opcional: Descontar stock (Lo haremos simple por ahora)
        $this->db->query('UPDATE medicamentos SET stock = stock - :cant WHERE id = :mid');
        $this->db->bind(':cant', $cantidad);
        $this->db->bind(':mid', $medicamento_id);
        $this->db->execute();
    }

    public function obtenerPendientes() {
        $this->db->query("SELECT p.*, u.nombre as usuario_nombre 
                          FROM pedidos p 
                          INNER JOIN usuarios u ON p.usuario_id = u.id 
                          WHERE p.estado = 'pendiente' 
                          ORDER BY p.creado_en ASC");
        return $this->db->registers();
    }

    // Aprobar o Rechazar pedido
    public function actualizarEstado($id, $estado, $codigo = null) {
        $sql = "UPDATE pedidos SET estado = :estado, codigo_retiro = :codigo WHERE id = :id";
        
        $this->db->query($sql);
        $this->db->bind(':estado', $estado);
        $this->db->bind(':codigo', $codigo); // Será null si se rechaza
        $this->db->bind(':id', $id);
        
        return $this->db->execute();
    }

    public function obtenerPorUsuario($usuario_id) {
        $this->db->query("SELECT * FROM pedidos WHERE usuario_id = :uid ORDER BY id DESC");
        $this->db->bind(':uid', $usuario_id);
        return $this->db->registers();
    }
    
    // Buscar pedido por código (Para el Farmacéutico más adelante)
    public function obtenerPorCodigo($codigo) {
        $this->db->query("SELECT p.*, u.nombre as usuario_nombre 
                          FROM pedidos p 
                          INNER JOIN usuarios u ON p.usuario_id = u.id 
                          WHERE p.codigo_retiro = :codigo");
        $this->db->bind(':codigo', $codigo);
        return $this->db->register();
    }
}
?>