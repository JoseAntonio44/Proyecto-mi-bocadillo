<?php
require_once 'inc/conexion.php';

class Pedido {
    private $db;

    public function __construct() {
        $this->db = DB::getInstance();
    }

    //metodo para registrar un pedido automáticamente con el bocadillo del día
    public function registrarPedidoDelDia($id_usuario, $tipo_bocadillo_valor, $dia) {
        //busca el bocadillo del día en la base de datos
        $stmt = $this->db->prepare("
            SELECT nombre, pvp 
            FROM bocadillo 
            WHERE dia = :dia AND frio = :frio AND f_baja IS NULL
        ");
        $stmt->bindParam(':dia', $dia);
        $stmt->bindParam(':frio', $tipo_bocadillo_valor); // Bocadillo frío (1) o caliente (0)
        $stmt->execute();

        //se almacena el bocadillo en una variable
        $bocadillo = $stmt->fetch(PDO::FETCH_ASSOC);

        //si no se encuentra un bocadillo para el día, devuelve false
        if (!$bocadillo) {
            return false;
        }

        //Guarda en una variable el nombre del alumno según el email (id_usuario)
        $stmt = $this->db->prepare("
            SELECT nombre
            FROM alumno
            WHERE id_usuario = :email
        ");
        $stmt->bindParam(':email', $id_usuario);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        //Verifica que se encontró el alumno
        if (!$usuario) {
            return false; //Si no se encuentra el alumno devuelve false
        }

        //Inserta el pedido en la base de datos
        $stmt = $this->db->prepare("
            INSERT INTO pedido (id_alumno, id_bocadillo, fecha, f_recogido, pvp, id_descuento)
            VALUES (:id_alumno, :id_bocadillo, NOW(), NULL, :pvp, NULL);
        ");
        
        
        $stmt->bindParam(':id_alumno', $usuario['nombre']);
        $stmt->bindParam(':id_bocadillo', $bocadillo['nombre']);
        $stmt->bindParam(':pvp', $bocadillo['pvp']);

        //Ejecuta la consulta de inserción
        if ($stmt->execute()) {
            return true; 
        } else {
            return false;
        }
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
