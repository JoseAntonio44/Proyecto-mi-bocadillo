<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

session_start();
require 'inc/conexion.php';


login();
function login() {
    $data = json_decode(file_get_contents('php://input'), true);
    $usuario = $data['Usuario'] ?? null;
    $password = $data['password'] ?? null;

    if ($usuario && $password) {
        $db = DB::getInstance();
        $stmt = $db->prepare("SELECT * FROM usuario WHERE email = :email");
        $stmt->bindParam(':email', $usuario);
        $stmt->execute();

        if ($stmt->rowCount() === 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            // Comparamos la contraseña directamente
            if ($password === $user['password']) {
                $_SESSION['user_email'] = $user['email'];
                echo json_encode([
                    'success' => true,
                    'message' => "Login exitoso. Bienvenido"
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => "Contraseña incorrecta."
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => "Usuario incorrecto."
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => "Por favor, completa todos los campos."
        ]);
    }
}
?>