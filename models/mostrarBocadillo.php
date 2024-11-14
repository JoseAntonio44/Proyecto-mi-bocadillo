<?php
require_once 'inc/conexion.php'; // Asegúrate de que la ruta sea correcta

class mostrarBocadillos{
    private $db;

    public function __construct() {
        $this->db = DB::getInstance(); // Conexión a la base de datos
    }

    public function traerBocadillos($dia){
        $stmt = $this->db->prepare("
        select nombre, frio 
        from bocadillo
        where dia = :dia
    ");
    $stmt->bindParam(':dia', $dia);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retorna un array asociativo con los resultados



    }
}
?>