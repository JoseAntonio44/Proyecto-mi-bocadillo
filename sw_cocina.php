<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
session_start();

require 'inc/auth.inc.php';
require 'models/cocina.php';

$data = json_decode(file_get_contents('php://input'), true);
$action = isset($data['action']) ? $data['action'] : 'listarPedidos';


try {
    switch ($action) {
        case 'listarPedidos':
            $cocina = new cocina();
            $resultado = $cocina->listarPedidos(); // Llama al metodo listarPedidos() de la clase cocina

            if ($resultado) {
                echo json_encode([
                    'success' => true,
                    'message' => "Pedidos mostrados correctamente.",
                    'data' => $resultado
                ]);
            }
            break;
        case 'eliminarPedido':
            $cocina = new Cocina();

            $resultado = $cocina->eliminarPedido($data['fecha']);

            if ($resultado) {
                echo json_encode([
                    'success' => true,
                    'message' => "Pedido eliminado correctamente."
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => "Error al eliminar el pedido."
                ]);
            }
            break;
        case 'listarBocadillosSemanales':
            $cocina = new cocina();
            $resultado = $cocina->listarBocadillosSemanales();
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
    }
} catch (Exception $e) {
    echo json_encode(array(
        "success" => false,
        "msg" => $e->getMessage()
    ));
}
exit();
