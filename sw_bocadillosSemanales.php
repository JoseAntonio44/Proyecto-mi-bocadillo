<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

session_start();

require 'inc/auth.inc.php';
require 'models/cocina.php';

listarBocadillosSemanales();
function listarBocadillosSemanales() {

    $cocina = new cocina();
    $resultado = $cocina->listarBocadillosSemanales();
    if ($resultado) {
        echo json_encode([
            'success' => true,
            'message' => "Bocadillos mostrados correctamente.",
            'data' => $resultado
        ]);
    }else {
        echo json_encode([
            'success' => false,
            'message' => "Error al mostrar los bocadillos."
        ]);
    }
}
?>