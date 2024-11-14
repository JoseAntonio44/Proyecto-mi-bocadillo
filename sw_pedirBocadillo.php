<?php
header('Content-Type: application/json');
session_start();
require 'inc/auth.inc.php'; 
require 'models/Pedido.php';

$data = json_decode(file_get_contents('php://input'), true);
$tipo_bocadillo = $data['tipo_bocadillo'] ?? null;

if ($tipo_bocadillo) {
    //Convertir el tipo de bocadillo a 1 (frío) o 0 (caliente) 
    //ya que en la BD en la columna "frio" se almacena 1 para bocadillo frío y 0 para bocadillo caliente
    
    $dia = date('l'); //Obtiene el día actual (En inglés)

    $pedido = new Pedido();
    $resultado = $pedido->registrarPedidoDelDia($_SESSION['id_usuario'], $tipo_bocadillo, $dia);

    if ($resultado) {
        echo json_encode([
            'success' => true,
            'message' => "Pedido de bocadillo del día realizado correctamente."
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => "Error al realizar el pedido del bocadillo del dia."
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => "Tipo de bocadillo no especificado."
    ]);
}
?>