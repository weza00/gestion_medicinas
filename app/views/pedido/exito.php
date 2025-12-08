<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pedido Enviado</title>
    <style>
        body { font-family: sans-serif; text-align: center; padding-top: 50px; }
        .icon { font-size: 50px; color: green; }
    </style>
</head>
<body>
    <i class="material-icons" style="font-size: 4rem; color: var(--success); margin-bottom: 15px;">check_circle</i>
    <h1>¡Pedido Enviado con Éxito!</h1>
    <p>Hemos recibido su solicitud y su receta médica.</p>
    <p>Un <strong>Validador</strong> revisará su receta en breve.</p>
    <p>Si todo está correcto, se generará su <strong>Código de Retiro</strong>.</p>
    
    <br><br>
    <a href="<?php echo BASE_URL; ?>/home">Volver al Inicio</a>
</body>
</html>