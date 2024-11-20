<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

session_start();

//require 'inc/auth.inc.php';
require 'models/cocina.php';

eliminarPedido();

function eliminarPedido() {
    $data = json_decode(file_get_contents('php://input'), true);

    $cocina = new Cocina();
    
    $resultado = $cocina->eliminarPedido($data['fecha']);

    if ($resultado) {
        echo json_encode([
        'success' => true,
        'message' => "Pedido eliminado correctamente."
        ]);
    }else {
        echo json_encode([
        'success' => false,
        'message' => "Error al eliminar el pedido."
        ]);
    }

    

}
?>