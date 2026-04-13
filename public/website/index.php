<?php
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>APRSOFT - Sistema de Gestión de Agua Potable</title>
<link rel="icon" href="/website/imagenes/gota-de-agua.png" type="image/png">
<link rel="stylesheet" href="/website/css/main.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<header>
    <div class="header-content">
        <img src="/website/imagenes/gota-de-agua.png" alt="Logo Agua Potable">
        <h1>APRSOFT</h1>
    </div>
    <p>Sistema de gestión de agua potable</p>
</header>

<section class="content">
    <div class="intro-text">
        <p>
            Nuestro sistema de gestión de agua potable permite una administración ágil, transparente y moderna.  
            Gracias a su diseño <em>responsive</em>, puedes acceder desde cualquier dispositivo y mantener el control de tu red de distribución en tiempo real.
        </p>
        <br><br>
        <div class="intro-left">
            <img src="/website/imagenes/sistema2.png" alt="Sistema de Agua Potable">
        </div>
    </div>

    <div class="contact" id="contacto">
        <h2>Contacto</h2>
        <form id="contactForm" action="/website/enviar_formulario.php" method="post">
            
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" maxlength="30">

            <label for="empresa">Empresa</label>
            <input type="text" id="empresa" name="empresa" maxlength="30">

            <label for="email">Email</label>
            <input type="text" id="email" name="email" maxlength="30">
            
            <label for="telefono">Teléfono</label>
            <input type="text" id="telefono" name="telefono" maxlength="8">

            <label for="mensaje">Mensaje</label>
            <textarea id="mensaje" name="mensaje" rows="4" maxlength="100"></textarea>
            <div class="char-counter" id="mensajeContador">100 caracteres restantes</div>

            <button type="submit">Enviar</button>
            <div id="statusMessage" class="status-message"></div>
        </form>
    </div>
</section>

<footer>
    <div>&copy; <span id="year"></span> <img src="/website/imagenes/gota-de-agua.png" alt="Sistema de Agua Potable" class="logo" > APRSOFT - Sistema de Gestión de Agua Potable.</div>
    <div>
        <a href="https://wa.me/56950564726" target="_blank" class="whatsapp-link">
           <i class="fab fa-whatsapp"></i> Contáctanos
        </a>
    </div>
</footer>


<script src="/website/js/main.js"></script>


</body>
</html>
