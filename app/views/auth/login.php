<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - MediPlus</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/style.css">
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
    
    <nav class="navbar">
        <div class="logo">Medi<span>Plus</span></div>
        <div class="nav-links">
            <a href="<?php echo BASE_URL; ?>/home">
                <i class="material-icons" style="vertical-align: middle; margin-right: 5px;">home</i>Volver al Inicio
            </a>
        </div>
    </nav>

    <div class="auth-wrapper">
        <div class="auth-card">
            <h2 style="color: var(--primary-dark); margin-bottom: 10px;">
                <i class="material-icons" style="vertical-align: middle; margin-right: 10px;">login</i>Bienvenido
            </h2>
            <p style="color: var(--text-light); margin-bottom: 30px;">Ingresa a tu cuenta autorizada del hospital.</p>

            <?php if(isset($data['error']) && !empty($data['error'])): ?>
                <div class="alert alert-error">
                    <i class="material-icons" style="vertical-align: middle; margin-right: 5px;">error</i>
                    <?php echo $data['error']; ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo BASE_URL; ?>/auth/login" method="POST">
                <div class="form-group">
                    <label><i class="material-icons" style="vertical-align: middle; margin-right: 5px;">badge</i>Cédula de Identidad</label>
                    <input type="text" name="dni" placeholder="1234567890" maxlength="10" pattern="[0-9]{10}" required>
                    <small style="color: #666; font-size: 0.85rem;">Ingrese su cédula de identidad (10 dígitos)</small>
                </div>

                <div class="form-group">
                    <label><i class="material-icons" style="vertical-align: middle; margin-right: 5px;">lock</i>Contraseña</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    <i class="material-icons" style="vertical-align: middle; margin-right: 5px;">login</i>Ingresar
                </button>
            </form>

            <div class="mt-20" style="text-align: center; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                <p style="font-size: 0.9rem; margin: 0; color: var(--text-light);">
                    <i class="material-icons" style="vertical-align: middle; margin-right: 5px;">info</i>
                    <strong>¿No tienes cuenta?</strong><br>
                    Solo el personal del hospital puede crear cuentas de pacientes.<br>
                    Contacta al administrador del sistema.
                </p>
            </div>
        </div>
    </div>
</body>
</html>