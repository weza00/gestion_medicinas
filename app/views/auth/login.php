<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - MediPlus</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/style.css">
</head>
<body>
    
    <nav class="navbar">
        <div class="logo">Medi<span>Plus</span></div>
        <div class="nav-links">
            <a href="<?php echo BASE_URL; ?>/home">Volver al Inicio</a>
        </div>
    </nav>

    <div class="auth-wrapper">
        <div class="auth-card">
            <h2 style="color: var(--primary-dark); margin-bottom: 10px;">Bienvenido</h2>
            <p style="color: var(--text-light); margin-bottom: 30px;">Ingresa a tu cuenta para gestionar tus pedidos.</p>

            <?php if(isset($data['error']) && !empty($data['error'])): ?>
                <div class="alert alert-error"><?php echo $data['error']; ?></div>
            <?php endif; ?>

            <form action="<?php echo BASE_URL; ?>/auth/login" method="POST">
                <div class="form-group">
                    <label>Correo Electrónico</label>
                    <input type="email" name="email" placeholder="ejemplo@correo.com" required>
                </div>

                <div class="form-group">
                    <label>Contraseña</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
            </form>

            <div class="mt-20">
                <p style="font-size: 0.9rem;">¿No tienes cuenta? <a href="<?php echo BASE_URL; ?>/auth/registro" style="color: var(--primary); font-weight: bold;">Regístrate aquí</a></p>
            </div>
        </div>
    </div>
</body>
</html>