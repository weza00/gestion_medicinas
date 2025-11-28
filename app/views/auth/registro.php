<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Paciente - MediPlus</title>
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
            <h2 style="color: var(--primary-dark); margin-bottom: 10px;">Crear Cuenta</h2>
            <p style="color: var(--text-light); margin-bottom: 30px;">Regístrate para solicitar tus medicamentos en línea.</p>

            <form action="<?php echo BASE_URL; ?>/auth/registro" method="POST">
                
                <div class="form-group">
                    <label>Nombre Completo</label>
                    <input type="text" name="nombre" placeholder="Ej: Juan Pérez" required autocomplete="name">
                </div>

                <div class="form-group">
                    <label>Correo Electrónico</label>
                    <input type="email" name="email" placeholder="ejemplo@correo.com" required autocomplete="email">
                </div>

                <div class="form-group">
                    <label>Contraseña</label>
                    <input type="password" name="password" placeholder="Mínimo 6 caracteres" required autocomplete="new-password">
                </div>

                <button type="submit" class="btn btn-success btn-block" style="margin-top: 10px;">
                    Registrarse
                </button>
            </form>

            <div class="mt-20">
                <p style="font-size: 0.9rem;">¿Ya tienes una cuenta? <a href="<?php echo BASE_URL; ?>/auth/login" style="color: var(--primary); font-weight: bold;">Inicia Sesión aquí</a></p>
            </div>
        </div>
    </div>

</body>
</html>