<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

session_start();
require 'inc/conexion.php';

cerrarSesion();
function cerrarSesion(){
    session_unset();
    session_destroy();

    echo json_encode([
        'success' => true,
        'message' => "Sesion cerrada correctamente"
    ]);
}

?>