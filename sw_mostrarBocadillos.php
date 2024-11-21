<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
session_start();
require 'inc/auth.inc.php'; // Verifica autenticación
require 'models/pedido.php'; // Incluye el modelo de Pedido

$data = json_decode(file_get_contents('php://input'), true);

mostrarBocadillos();

function mostrarBocadillos(){

    $dia = date('l'); // Obtiene el día actual
    $mostrarBocadillos = new Pedido(); 
    $resultado = $mostrarBocadillos->traerBocadillos($dia);

    if ($resultado) {
        echo json_encode([
        'success' => true,
        'message' => "Bocadillos del día mostrados correctamente.",
        'data' => $resultado
        ]);
    }
    header('Content-Type: application/json');
}

?>