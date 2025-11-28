<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema Hospital</title>
</head>
<body>
    <h1>Bienvenido al Sistema de Medicamentos</h1>
    
    <?php if(isset($_SESSION['user_id'])): ?>
        <div style="background: #e2e6ea; padding: 20px;">
            <h3>Hola, <?php echo $_SESSION['user_nombre']; ?> (<?php echo $_SESSION['user_rol']; ?>)</h3>
            <a href="<?php echo BASE_URL; ?>/auth/logout">Cerrar Sesión</a>
        </div>
    <?php else: ?>
        <p>Por favor inicia sesión para continuar.</p>
        <a href="<?php echo BASE_URL; ?>/auth/login">Iniciar Sesión</a> | 
        <a href="<?php echo BASE_URL; ?>/auth/registro">Registrarse</a>
    <?php endif; ?>

</body>
</html>