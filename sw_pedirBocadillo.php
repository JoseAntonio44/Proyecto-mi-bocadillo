<?php
header('Content-Type: application/json');
session_start();
require 'inc/auth.inc.php'; // Verifica autenticación
require 'models/Pedido.php'; // Incluye el modelo de Pedido

$data = json_decode(file_get_contents('php://input'), true);
$tipo_bocadillo = $data['tipo_bocadillo'] ?? null;

if ($tipo_bocadillo) {
    // Convertir el tipo de bocadillo a 1 (frío) o 0 (caliente)
    $tipo_bocadillo_valor = ($tipo_bocadillo === "frio") ? 1 : 0;
    $dia = date('l'); // Obtiene el día actual

    $pedido = new Pedido();
    $resultado = $pedido->registrarPedidoDelDia($_SESSION['id_usuario'], $tipo_bocadillo_valor, $dia);

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
?>