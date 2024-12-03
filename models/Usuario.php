<?php
require_once 'inc/conexion.php';
class Usuario {
    private $db;

    public function __construct() {
        $this->db = DB::getInstance();
        $this->db->exec("SET lc_time_names = 'es_ES';");
    }
    public function traerNombre($id_alumno){
        $stmt = $this->db->prepare("
            select nombre
            from alumno
            where id_usuario = :id_usuario
        ");
        $stmt->bindParam(':id_usuario', $id_alumno);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>