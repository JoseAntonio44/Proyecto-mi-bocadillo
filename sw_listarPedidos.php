<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
session_start();
//require 'inc/auth.inc.php'; // Verifica autenticación
require 'models/cocina.php';

$data = json_decode(file_get_contents('php://input'), true);

listarPedidos();

function listarPedidos() {
    $cocina = new cocina();
    $resultado = $cocina->listarPedidos(); // Llama al metodo listarPedidos() de la clase cocina

    if ($resultado) {
        echo json_encode([
        'success' => true,
        'message' => "Pedidos mostrados correctamente.",
        'data' => $resultado
        ]);
    }
    header('Content-Type: application/json');

}


?>