<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
session_start();

require 'models/pedido.php';

$data = json_decode(file_get_contents('php://input'), true);

traerNombre();
function traerNombre(){
    $traerNombre = new Pedido();
    $resultado = $traerNombre->traerNombre($_SESSION['id_usuario']);

    if ($resultado){
        echo json_encode([
            'success' => true,
            'message' => 'Nombre encontrado',
            'data' => [$resultado]
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Nombre no encontrado'
        ]);
    }
}
?>