<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); // Iniciar sesión para CSRF

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

$config = require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validar token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Error: token CSRF inválido.");
    }
    
    // Honeypot: si se llenó, es un bot
        if (!empty($_POST['website'])) {
            die("Error: detectado como spam.");
        }


    // Limpiar inputs
    $nombre   = trim($_POST['nombre'] ?? '');
    $empresa  = trim($_POST['empresa'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $mensaje  = trim($_POST['mensaje'] ?? '');

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = $config['host'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $config['username'];
        $mail->Password   = $config['password'];
        $mail->SMTPSecure = $config['secure'];
        $mail->Port       = $config['port'];

        $mail->setFrom($config['username'], 'Sitio Web APRSOFT');
        $mail->addAddress($config['to_address'], 'Administrador APRSOFT');
        $mail->addReplyTo($email, $nombre);

        $mail->isHTML(true);
        $mail->Subject = "Nuevo mensaje de contacto";
        $mail->Body = "
            <p><strong>Nombre:</strong> {$nombre}</p>
            <p><strong>Empresa:</strong> {$empresa}</p>
            <p><strong>Teléfono:</strong> {$telefono}</p>
            <p><strong>Email:</strong> {$email}</p>
            <p><strong>Mensaje:</strong><br>{$mensaje}</p>
        ";

        $mail->send();
        echo "ok";

    } catch (Exception $e) {
        echo "error";
    }

} else {
    echo "error";
}
