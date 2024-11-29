<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
session_start();

require_once 'inc/auth.inc.php';
require 'models/pedido.php';

$data = json_decode(file_get_contents('php://input'), true);
$action = isset($data['action']) ? $data['action'] : 'hacerPedido';


if (!isset($_SESSION['id_usuario'])) {
    echo json_encode([
        "success" => false,
        "message" => "No tienes permiso para acceder a esta sección."
    ]);
}


try {
    switch ($action) {
        case "hacerPedido":
            $data = json_decode(file_get_contents('php://input'), true);
            $tipo_bocadillo = $data['tipo_bocadillo'] ?? null;
            $hora = date('H:i');

            if ($hora >= '09:00' && $hora <= '22:00') {
                if ($tipo_bocadillo) {
                    
                    $pedido = new Pedido();
                    $resultado = $pedido->registrarPedidoDelDia($_SESSION['id_usuario'], $tipo_bocadillo);

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
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => "A esta hora no puedes pedir un bocadillo."
                ]);
            }
            break;

        case "mostrarBocadillos":
            $mostrarBocadillos = new Pedido();
            $resultado = $mostrarBocadillos->traerBocadillos();

            if ($resultado) {
                echo json_encode([
                    'success' => true,
                    'message' => "Bocadillos del día mostrados correctamente.",
                    'data' => $resultado
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => "No hay bocadillos el dia de hoy."
                ]);
            }
            break;

        case "mostrarNombre":
            $traerNombre = new Pedido();
            $resultado = $traerNombre->traerNombre($_SESSION['id_usuario']);

            if ($resultado) {
                echo json_encode([
                    "success" => true,
                    "msg" => "Nombre encontrado",
                    "data" => [$resultado]
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "msg" => "Nombre no encontrado"
                ]);
            }
            break;
        case "verificarAutenticacion":
            echo json_encode([
                "success" => true,
                "message" => "Usuario autenticado."
            ]);
            break;

        default:
            echo json_encode([
                "success" => false,
                "msg" => "Acción no reconocida."
            ]);
            break;
    }
} catch (Exception $e) {
    echo json_encode(array(
        "success" => false,
        "msg" => $e->getMessage()
    ));
}
exit();
