<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/style.css">
</head>
<body>

    <nav class="navbar">
        <div class="logo">Medi<span>Plus</span></div>
        <div class="nav-links">
            <a href="<?php echo BASE_URL; ?>/catalogo">Seguir Comprando</a>
            <a href="<?php echo BASE_URL; ?>/auth/logout">Salir</a>
        </div>
    </nav>

    <div class="container">
        <h1 class="text-center" style="margin: 40px 0; color: var(--primary-dark);">Tu Carrito de Compras</h1>

        <?php if (empty($data['productos'])): ?>
            <div class="text-center" style="padding: 50px;">
                <p style="font-size: 1.2rem; color: var(--text-light);">Tu carrito está vacío 😔</p>
                <br>
                <a href="<?php echo BASE_URL; ?>/catalogo" class="btn btn-primary">Ir al Catálogo</a>
            </div>
        <?php else: ?>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['productos'] as $prod): ?>
                            <tr>
                                <td>
                                    <strong><?php echo $prod->nombre; ?></strong><br>
                                    <small><?php echo $prod->presentacion; ?></small>
                                </td>
                                <td>$<?php echo $prod->precio; ?></td>
                                <td><?php echo $prod->cantidad_carrito; ?></td>
                                <td style="font-weight: bold; color: var(--primary-dark);">$<?php echo number_format($prod->subtotal, 2); ?></td>
                                <td>
                                    <a href="<?php echo BASE_URL; ?>/carrito/eliminar/<?php echo $prod->id; ?>" style="color: var(--danger); font-weight: bold;">Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 30px; background: white; padding: 20px; border-radius: 12px; box-shadow: var(--shadow);">
                <a href="<?php echo BASE_URL; ?>/carrito/vaciar" class="btn btn-outline" style="color: var(--danger); border-color: var(--danger);">Vaciar Carrito</a>
                
                <div style="text-align: right;">
                    <div style="font-size: 1.5rem; font-weight: bold; margin-bottom: 10px;">
                        Total: $<?php echo number_format($data['total'], 2); ?>
                    </div>
                    <a href="<?php echo BASE_URL; ?>/pedido/checkout" class="btn btn-success">
                        Proceder al Pago y Receta ➔
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>