<?php
require_once 'inc/conexion.php'; // Asegúrate de que la ruta sea correcta

class Pedido {
    private $db;

    public function __construct() {
        $this->db = DB::getInstance(); // Conexión a la base de datos
    }

    // Método para registrar un pedido automáticamente con el bocadillo del día
    public function registrarPedidoDelDia($id_usuario, $tipo_bocadillo_valor, $dia) {
        // Buscar el bocadillo del día en la base de datos
        $stmt = $this->db->prepare("
            SELECT nombre, pvp 
            FROM bocadillo 
            WHERE dia = :dia AND frio = :frio AND f_baja IS NULL
        ");
        $stmt->bindParam(':dia', $dia);
        $stmt->bindParam(':frio', $tipo_bocadillo_valor); // Bocadillo frío (1) o caliente (0)
        $stmt->execute();

        // Obtener el bocadillo
        $bocadillo = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si no se encontró un bocadillo para el día, retornar false
        if (!$bocadillo) {
            return false;
        }

        // Guardar en una variable el nombre del alumno según el email (id_usuario)
        $stmt = $this->db->prepare("
            SELECT nombre
            FROM alumno
            WHERE id_usuario = :email
        ");
        $stmt->bindParam(':email', $id_usuario);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar que se encontró el alumno
        if (!$usuario) {
            return false; // Si no se encuentra el alumno, retornar false
        }

        // Insertar el pedido en la base de datos
        $stmt = $this->db->prepare("
            INSERT INTO pedido (id_alumno, id_bocadillo, fecha, f_recogido, pvp, id_descuento)
            VALUES (:id_alumno, :id_bocadillo, NOW(), NULL, :pvp, NULL);
        ");
        
        // Aquí debes usar el ID del alumno o el nombre, dependiendo de la estructura de tu base de datos
        // Si estás usando el nombre como clave, usa $usuario['nombre'], de lo contrario, usa el ID del alumno.
        $stmt->bindParam(':id_alumno', $usuario['nombre']); // O usa el ID si es necesario
        $stmt->bindParam(':id_bocadillo', $bocadillo['nombre']);
        $stmt->bindParam(':pvp', $bocadillo['pvp']);

        // Ejecutar la consulta de inserción
        if ($stmt->execute()) {
            return true; // El pedido se registró correctamente
        } else {
            return false; // Hubo un error al registrar el pedido
        }
    }
}
?>
