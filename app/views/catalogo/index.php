<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmacia Online</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/style.css">
</head>
<body>

    <nav class="navbar">
        <div class="logo">Medi<span>Plus</span></div>
        <div class="nav-links">
            <a href="<?php echo BASE_URL; ?>/home">Inicio</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="<?php echo BASE_URL; ?>/pedido/mis_pedidos">Mis Pedidos</a>
                <a href="<?php echo BASE_URL; ?>/carrito">Carrito <span style="background:var(--primary); color:white; padding:2px 6px; border-radius:10px; font-size:0.8em;"><?php echo isset($_SESSION['carrito']) ? array_sum($_SESSION['carrito']) : 0; ?></span></a>
                <a href="<?php echo BASE_URL; ?>/auth/logout" style="color: var(--danger);">Salir</a>
            <?php else: ?>
                <a href="<?php echo BASE_URL; ?>/auth/login" class="btn-nav">Iniciar Sesión</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="container">
        <div style="text-align: center; padding: 40px 0;">
            <h1 style="color: var(--primary-dark); font-size: 2.5rem;">Catálogo de Medicamentos</h1>
            <p style="color: var(--text-light);">Calidad y confianza para el cuidado de tu salud.</p>
        </div>

        <?php foreach($data['medicamentos_agrupados'] as $categoria => $medicinas): ?>
            
            <h2 class="section-title"><?php echo $categoria; ?></h2>

            <div class="grid-medicamentos">
                <?php foreach($medicinas as $med): ?>
                    <?php if($med->estado == 1 && $med->stock > 0): ?>
                        <div class="card">
                            <div>
                                <span class="card-cat"><?php echo $categoria; ?></span>
                                <h3><?php echo $med->nombre; ?></h3>
                                <p><?php echo $med->descripcion; ?></p>
                                <small style="color: var(--text-light); display:block; margin-bottom:10px;">
                                    Presentación: <?php echo $med->presentacion; ?>
                                </small>
                            </div>
                            
                            <div class="card-footer">
                                <div class="price">$<?php echo $med->precio; ?></div>
                                
                                <form action="<?php echo BASE_URL; ?>/carrito/agregar" method="POST">
                                    <input type="hidden" name="id" value="<?php echo $med->id; ?>">
                                    <button type="submit" class="btn-add">
                                        Agregar +
                                    </button>
                                </form>
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