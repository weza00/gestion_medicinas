<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Módulo Farmacia</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/style.css">
    <style>
        /* Estilos específicos para la búsqueda grande */
        .search-hero { background: white; padding: 40px; border-radius: 15px; box-shadow: var(--shadow); text-align: center; max-width: 600px; margin: 0 auto; }
        .input-code { font-size: 1.5rem; letter-spacing: 2px; text-transform: uppercase; text-align: center; border: 2px solid #eee; }
        .input-code:focus { border-color: var(--primary); }
        .result-card { background: #e3f2fd; border: 1px solid #90caf9; padding: 20px; border-radius: 10px; margin-top: 30px; text-align: left; }
    </style>
</head>
<body>

    <nav class="navbar" style="background: var(--text-dark);">
        <div class="logo" style="color: white;">Farmacia<span>Despacho</span></div>
        <div class="nav-links">
            <a href="<?php echo BASE_URL; ?>/auth/logout" style="color: white;">Cerrar Turno</a>
        </div>
    </nav>

    <div class="container" style="margin-top: 50px;">
        
        <div class="search-hero">
            <h2 style="color: var(--primary-dark);">Entrega de Medicamentos</h2>
            <p style="margin-bottom: 20px;">Ingrese el código presentado por el paciente</p>
            
            <form action="<?php echo BASE_URL; ?>/farmacia/index" method="POST">
                <div class="form-group">
                    <input type="text" name="codigo" class="input-code" placeholder="MED-XXXXXX" required autocomplete="off">
                </div>
                <button type="submit" class="btn btn-primary btn-block" style="font-size: 1.2rem;">BUSCAR PEDIDO</button>
            </form>

            <?php if($data['error']): ?>
                <div class="alert alert-error mt-20">
                    ⚠ <?php echo $data['error']; ?>
                </div>
            <?php endif; ?>
        </div>

        <?php if(isset($data['resultado']) && $data['resultado']): ?>
            <div class="auth-card" style="max-width: 600px; margin: 30px auto; border-top: 5px solid var(--success);">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h2 style="color: var(--success);">Pedido Aprobado</h2>
                    <span style="font-size: 2rem;">💊</span>
                </div>
                
                <hr style="margin: 15px 0; border: 0; border-top: 1px dashed #ccc;">

                <div style="text-align: left; font-size: 1.1rem;">
                    <p><strong>Paciente:</strong> <?php echo $data['resultado']->usuario_nombre; ?></p>
                    <p><strong>Código:</strong> <span style="font-family: monospace; background: #eee; padding: 2px 5px;"><?php echo $data['resultado']->codigo_retiro; ?></span></p>
                    <p><strong>Estado:</strong> <span class="badge status-aprobado">LISTO PARA ENTREGA</span></p>
                </div>

                <div class="alert" style="background: #fff3cd; color: #856404; margin-top: 20px; font-size: 0.9rem;">
                    ⚠ Verifique la identidad del paciente antes de entregar.
                </div>

                <a href="<?php echo BASE_URL; ?>/farmacia/entregar/<?php echo $data['resultado']->id; ?>" class="btn btn-success btn-block" style="padding: 15px; font-size: 1.2rem;">
                    CONFIRMAR ENTREGA
                </a>
            </div>
        <?php endif; ?>

    </div>
</body>
</html>