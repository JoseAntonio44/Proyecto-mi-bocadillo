<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
session_start();

require 'inc/auth.inc.php';
require 'models/Pedido.php';
require 'models/Bocadillo.php';
require 'models/Usuario.php';


$data = json_decode(file_get_contents('php://input'), true);
$action = isset($data['action']) ? $data['action'] : 'listarPedidos';

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode([
        "success" => false,
        "message" => "No tienes permiso para acceder a esta sección."
    ]);
}

try {
    switch ($action) {
        case 'listarPedidos':
            $listarPedidos = new Pedido();
            $resultado = $listarPedidos->listarPedidos(); // Llama al metodo listarPedidos() de la clase cocina

            if ($resultado) {
                echo json_encode([
                    'success' => true,
                    'message' => "Pedidos mostrados correctamente.",
                    'data' => $resultado
                ]);
            }
            break;
        case 'eliminarPedido':
            $eliminarPedido = new Pedido();

            $resultado = $eliminarPedido->insertarFechaPedido($data['fecha']);

            if ($resultado) {
                echo json_encode([
                    'success' => true,
                    'message' => "Pedido insertado correctamente."
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => "Error al eliminar el pedido."
                ]);
            }
            break;
        case 'listarBocadillosSemanales':
            $listarBocadillosSemanales = new Bocadillo();
            $resultado = $listarBocadillosSemanales->listarBocadillosSemanales();
            if ($resultado) {
                echo json_encode([
                    'success' => true,
                    'message' => "Bocadillos mostrados correctamente.",
                    'data' => $resultado
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => "Error al mostrar los bocadillos."
                ]);
            }
            break;
        case "verificarAutenticacion":
            echo json_encode([
                "success" => true,
                "message" => "Usuario autenticado."
            ]);
            break;
        case "bocadillosTotal":

            $numeroBocadillos = new Bocadillo();

            $resultado = $numeroBocadillos->contarBocadillos();

                if($resultado){
                    echo json_encode([
                        'success' => true,
                        'message' => "Bocadillos contados correctamente.",
                        'data' => $resultado
                    ]);
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => "Error al contar los bocadillos."
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
