<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>MediPlus - Farmacia Hospitalaria</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/style.css">
    <style>
        /* Estilos específicos del Home */
        .hero {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 80px 20px;
            text-align: center;
            border-radius: 0 0 50% 50% / 20px; /* Curva sutil abajo */
        }
        .hero h1 { color: white; font-size: 3rem; margin-bottom: 20px; }
        .hero p { font-size: 1.2rem; opacity: 0.9; margin-bottom: 30px; }
        .features { display: flex; justify-content: center; gap: 30px; margin-top: -50px; padding: 0 20px; flex-wrap: wrap; }
        .feature-card { background: white; padding: 30px; border-radius: 15px; box-shadow: var(--shadow); width: 300px; text-align: center; }
        .icon { font-size: 2.5rem; margin-bottom: 15px; display: block; }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="logo">Medi<span>Plus</span></div>
        <div class="nav-links">
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="<?php echo BASE_URL; ?>/catalogo">Catálogo</a>
                <a href="<?php echo BASE_URL; ?>/pedido/mis_pedidos">Mis Pedidos</a>
                <a href="<?php echo BASE_URL; ?>/auth/logout" style="color: var(--danger);">Salir</a>
            <?php else: ?>
                <a href="<?php echo BASE_URL; ?>/auth/login" class="btn-nav">Ingresar</a>
            <?php endif; ?>
        </div>
    </nav>

    <header class="hero">
        <h1>Tu salud es nuestra prioridad</h1>
        <p>Gestiona tus medicamentos, recetas y retiros de forma segura y rápida.</p>
        
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="<?php echo BASE_URL; ?>/catalogo" class="btn" style="background: white; color: var(--primary); font-weight: bold;">
                Ver Catálogo de Medicamentos
            </a>
        <?php else: ?>
            <a href="<?php echo BASE_URL; ?>/auth/registro" class="btn" style="background: white; color: var(--primary); font-weight: bold;">
                Crear Cuenta Paciente
            </a>
        <?php endif; ?>
    </header>

    <section class="features">
        <div class="feature-card">
            <span class="icon">💊</span>
            <h3>Catálogo Completo</h3>
            <p>Accede a todos los medicamentos disponibles en stock en tiempo real.</p>
        </div>
        <div class="feature-card">
            <span class="icon">📄</span>
            <h3>Recetas Digitales</h3>
            <p>Sube tu receta médica en PDF o imagen para validación inmediata.</p>
        </div>
        <div class="feature-card">
            <span class="icon">🏥</span>
            <h3>Retiro Seguro</h3>
            <p>Obtén tu código único y retira tus medicinas sin filas innecesarias.</p>
        </div>
    </section>

</body>
</html>