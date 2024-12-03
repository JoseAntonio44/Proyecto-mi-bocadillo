<?php
require_once 'inc/conexion.php';
class Bocadillo
{
    private $db;

    public function __construct() {
        $this->db = DB::getInstance();
        $this->db->exec("SET lc_time_names = 'es_ES';");
    }
    public function traerBocadillos(){
        $stmt = $this->db->prepare("
        select *
        from bocadillo
        where dia = DAYNAME(NOW());
    ");
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function listarBocadillosSemanales() {
        $stmt = $this->db->prepare("SELECT * FROM bocadillo ORDER BY dia");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
