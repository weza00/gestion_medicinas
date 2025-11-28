<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/style.css">
</head>
<body>

    <nav class="navbar" style="background: var(--text-dark);">
        <div class="logo" style="color: white;">Admin<span>Panel</span></div>
        <div class="nav-links">
            <a href="<?php echo BASE_URL; ?>/home" style="color: #ddd;">Ver Sitio Público</a>
            <a href="<?php echo BASE_URL; ?>/auth/logout" style="color: var(--primary);">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 40px; margin-bottom: 20px;">
            <h1 class="section-title" style="margin: 0; border: none;">Inventario de Medicamentos</h1>
            <a href="<?php echo BASE_URL; ?>/admin/agregar" class="btn btn-success">
                + Nuevo Medicamento
            </a>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Presentación</th>
                        <th>Stock</th>
                        <th>Precio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['medicamentos'] as $med): ?>
                        <tr>
                            <td>#<?php echo $med->id; ?></td>
                            <td>
                                <strong><?php echo $med->nombre; ?></strong>
                            </td>
                            <td>
                                <span class="card-cat" style="font-size: 0.7rem; margin: 0;"><?php echo $med->categoria_nombre; ?></span>
                            </td>
                            <td><?php echo $med->presentacion; ?></td>
                            
                            <td>
                                <?php if($med->stock < 10): ?>
                                    <span style="color: var(--danger); font-weight: bold;"><?php echo $med->stock; ?> (Bajo)</span>
                                <?php else: ?>
                                    <span style="color: var(--success); font-weight: bold;"><?php echo $med->stock; ?></span>
                                <?php endif; ?>
                            </td>
                            
                            <td>$<?php echo $med->precio; ?></td>
                            <td>
                                <button class="btn btn-outline" style="font-size: 0.8rem; padding: 5px;">Editar</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>