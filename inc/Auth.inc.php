<?php

/**
 * Archivo que verifica que el usuario está identificado
 */


if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Solo inicia la sesión si no está activa
}

$json_error = json_encode(array(
    "success" => false,
    "msg" => "No tienes permisos para acceder a esta sección."
));

if (!isset($_SESSION["id_usuario"])) {
    session_destroy();
    echo $json_error;
    exit();
}
