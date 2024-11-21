<?php
header('Content-Type: application/json');
session_start();
require 'inc/auth.inc.php';
require 'models/Pedido.php';

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
