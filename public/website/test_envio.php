<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

$config = require 'config.php';

$mail = new PHPMailer(true);

try {
    // Activar depuración detallada
    $mail->SMTPDebug = 2; // Mostrar salida detallada
    $mail->Debugoutput = 'html';

    // Configuración del servidor SMTP
    $mail->isSMTP();
    $mail->Host       = $config['host'];
    $mail->SMTPAuth   = true;
    $mail->Username   = $config['username'];
    $mail->Password   = $config['password'];
    $mail->SMTPSecure = $config['secure'];
    $mail->Port       = $config['port'];

    // Remitente y destinatario
    $mail->setFrom($config['username'], 'Prueba PHPMailer');
    $mail->addAddress($config['to_address'], 'Administrador APRSOFT');

    // Contenido del correo
    $mail->isHTML(true);
    $mail->Subject = '📧 Prueba de envío desde PHPMailer';
    $mail->Body    = '<p>Este es un correo de prueba enviado desde el archivo <b>test_envio.php</b> en tu hosting.</p>';
    $mail->AltBody = 'Este es un correo de prueba enviado desde test_envio.php';

    // Enviar
    $mail->send();
    echo "<h3 style='color:green'>✅ Correo de prueba enviado correctamente.</h3>";

} catch (Exception $e) {
    echo "<h3 style='color:red'>❌ Error al enviar el correo:</h3>";
    echo "<pre>" . htmlspecialchars($mail->ErrorInfo) . "</pre>";
}
?>
