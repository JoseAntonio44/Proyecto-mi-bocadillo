<?php
require_once 'inc/conexion.php';

class Pedido
{
    private $db;

    public function __construct()
    {
        $this->db = DB::getInstance();
        $this->db->exec("SET lc_time_names = 'es_ES';");
    }

    //metodo para registrar un pedido automáticamente con el bocadillo del día
    public function registrarPedidoDelDia($id_usuario, $tipo_bocadillo_valor)
    {
        //busca el bocadillo del día en la base de datos
        $stmt = $this->db->prepare("
            SELECT nombre, pvp 
            FROM bocadillo 
            WHERE dia = DAYNAME(NOW()) AND frio = :frio AND f_baja IS NULL
        ");
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
    
    public function insertarFechaPedido($fecha) {
        $stmt = $this->db->prepare("UPDATE pedido SET f_recogido = NOW() WHERE fecha = :fecha");
        $stmt->bindParam(':fecha', $fecha);


        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    public function listarPedidos() {
        $stmt = $this->db->prepare("SELECT * FROM pedido WHERE DATE(fecha) = CURDATE() and f_recogido is null ORDER BY fecha DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function comprobarPedidosUsuario($id_usuario)
    {
        //Guarda en una variable el nombre del alumno según el email (id_usuario)
        $stmt = $this->db->prepare("
            SELECT nombre
            FROM alumno
            WHERE id_usuario = :email
        ");
        $stmt->bindParam(':email', $id_usuario);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario) {
            return false; //Si no se encuentra el alumno devuelve false
        }

        $stmt = $this->db->prepare("SELECT COUNT(*) FROM pedido WHERE id_alumno = :id_alumno and DATE(fecha) = CURDATE()");
        $stmt->bindParam(':id_alumno', $usuario['nombre']);
        $stmt->execute();
        
        $resultado = $stmt->fetchColumn();
        if ($resultado > 0) {
            return true; // Si se encuentra un pedido hoy, devuelve true
        } else {
            return false; // Si no se encuentra un pedido hoy, devuelve false
        }
    }
}
