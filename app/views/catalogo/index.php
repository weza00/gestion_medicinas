<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Medicamentos - MediPlus</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/style.css">
    <style>
        .btn-disabled {
            background: #ccc !important;
            color: #666 !important;
            cursor: not-allowed !important;
            pointer-events: none;
        }
        .login-notice {
            background: #fff3cd;
            color: #856404;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            margin: 20px 0;
            border: 1px solid #ffeeba;
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="logo">Medi<span>Plus</span></div>
        <div class="nav-links">
            <a href="<?php echo BASE_URL; ?>/home">Inicio</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <?php if($_SESSION['user_rol'] == 'paciente'): ?>
                    <a href="<?php echo BASE_URL; ?>/pedido/mis_pedidos">Mis Pedidos</a>
                    <a href="<?php echo BASE_URL; ?>/carrito">Carrito <span style="background:var(--primary); color:white; padding:2px 6px; border-radius:10px; font-size:0.8em;"><?php echo isset($_SESSION['carrito']) ? array_sum($_SESSION['carrito']) : 0; ?></span></a>
                <?php endif; ?>
                <a href="<?php echo BASE_URL; ?>/auth/logout" style="color: var(--danger);">Salir</a>
            <?php else: ?>
                <a href="<?php echo BASE_URL; ?>/auth/login" class="btn-nav">Iniciar Sesión</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="container">
        <div style="text-align: center; padding: 40px 0;">
            <h1 style="color: var(--primary-dark); font-size: 2.5rem;">Catálogo de Medicamentos</h1>
            <p style="color: var(--text-light);">Consulta nuestro inventario disponible en el hospital.</p>
        </div>

        <?php if(!isset($_SESSION['user_id'])): ?>
            <div class="login-notice">
                ⚠️ <strong>Para realizar pedidos:</strong> Debe iniciar sesión con una cuenta autorizada por el hospital.
                <a href="<?php echo BASE_URL; ?>/auth/login" style="color: var(--primary-dark); font-weight: bold; text-decoration: underline;">Iniciar Sesión</a>
            </div>
        <?php endif; ?>

        <?php foreach($data['medicamentos_agrupados'] as $categoria => $medicinas): ?>
            
            <h2 class="section-title"><?php echo $categoria; ?></h2>

            <div class="grid-medicamentos">
                <?php foreach($medicinas as $med): ?>
                    <?php if($med->estado == 1): ?>
                        <div class="card">
                            <div>
                                <span class="card-cat"><?php echo $categoria; ?></span>
                                <h3><?php echo $med->nombre; ?></h3>
                                <p><?php echo $med->descripcion; ?></p>
                                <small style="color: var(--text-light); display:block; margin-bottom:10px;">
                                    Presentación: <?php echo $med->presentacion; ?>
                                </small>
                                <small style="color: var(--text-light); display:block; margin-bottom:10px;">
                                    <?php if($med->stock > 0): ?>
                                        <span style="color: var(--success); font-weight: bold;">✓ Disponible (<?php echo $med->stock; ?> unidades)</span>
                                    <?php else: ?>
                                        <span style="color: var(--danger); font-weight: bold;">✗ Sin stock</span>
                                    <?php endif; ?>
                                </small>
                            </div>
                            
                            <div class="card-footer">
                                <div class="price">$<?php echo $med->precio; ?></div>
                                
                                <?php if(isset($_SESSION['user_id']) && $_SESSION['user_rol'] == 'paciente' && $med->stock > 0): ?>
                                    <form action="<?php echo BASE_URL; ?>/carrito/agregar" method="POST">
                                        <input type="hidden" name="id" value="<?php echo $med->id; ?>">
                                        <button type="submit" class="btn-add">
                                            Agregar +
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <button class="btn-add btn-disabled" title="<?php echo !isset($_SESSION['user_id']) ? 'Inicie sesión para realizar pedidos' : ($med->stock == 0 ? 'Sin stock disponible' : 'No autorizado para pedidos'); ?>">
                                        <?php echo $med->stock > 0 ? 'Inicie Sesión' : 'Sin Stock'; ?>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

        <?php endforeach; ?>
        
        <?php if(empty($data['medicamentos_agrupados'])): ?>
            <p style="text-align: center; margin-top: 50px;">No hay medicamentos disponibles en este momento.</p>
        <?php endif; ?>

        <br><br><br>
    </div>

</body>
</html>