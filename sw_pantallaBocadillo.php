<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
session_start();

require 'inc/auth.inc.php';
require 'models/pedido.php';

$data = json_decode(file_get_contents('php://input'), true);
$action = isset($data['action']) ? $data['action'] : 'get';
try {
    switch ($action) {
        case "hacerPedido":
            $data = json_decode(file_get_contents('php://input'), true);
            $tipo_bocadillo = $data['tipo_bocadillo'] ?? null;
            $hora = date('H:i');

            if ($hora >= '09:00' && $hora <= '22:00') {
                if ($tipo_bocadillo) {

                    $dia = date('l'); // Obtiene el día actual

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
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => "A esta hora no puedes pedir un bocadillo."
                ]);
            }
            break;

        case "mostrarBocadillos":
            $dia = date('l'); // Obtiene el día actual
            $mostrarBocadillos = new Pedido();
            $resultado = $mostrarBocadillos->traerBocadillos($dia);

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
