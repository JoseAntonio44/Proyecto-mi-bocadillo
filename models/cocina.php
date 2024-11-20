<?php

require 'inc/conexion.php';

class Cocina {
    private $db;

    public function __construct() {
        $this->db = DB::getInstance();
    }

    public function listarPedidos() {
        $stmt = $this->db->prepare("SELECT * FROM pedido ORDER BY fecha");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function eliminarPedido($fecha) {
        $stmt = $this->db->prepare("DELETE FROM pedido WHERE fecha = :fecha");
        $stmt->bindParam(':fecha', $fecha);

        
        if ($stmt->execute()) {
            return true; 
        } else {
            return false;
        }
        
        
    }
    public function listarBocadillosSemanales() {
        $stmt = $this->db->prepare("SELECT * FROM bocadillo ORDER BY dia");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>