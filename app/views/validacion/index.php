<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Validación</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/style.css">
</head>
<body>

    <nav class="navbar">
        <div class="logo">Panel<span>Validador</span></div>
        <div class="nav-links">
            <span style="margin-right: 20px; font-weight: bold; color: var(--primary);">Hola, Doc.</span>
            <a href="<?php echo BASE_URL; ?>/auth/logout">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="container">
        <h1 class="section-title">Recetas Pendientes de Revisión</h1>

        <?php if(empty($data['pendientes'])): ?>
            <div style="text-align: center; padding: 50px; background: white; border-radius: 12px; box-shadow: var(--shadow);">
                <div style="font-size: 3rem;">✅</div>
                <h3>Todo al día</h3>
                <p>No hay recetas pendientes de validación en este momento.</p>
            </div>
        <?php else: ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Paciente</th>
                            <th>Fecha Solicitud</th>
                            <th>Receta Médica</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['pendientes'] as $p): ?>
                            <tr>
                                <td>#<?php echo $p->id; ?></td>
                                <td style="font-weight: bold;"><?php echo $p->usuario_nombre; ?></td>
                                <td><?php echo date('d/m/Y', strtotime($p->creado_en)); ?></td>
                                <td>
                                    <a href="<?php echo BASE_URL; ?>/uploads/<?php echo $p->receta_archivo; ?>" target="_blank" class="btn btn-outline" style="border-color: var(--primary); color: var(--primary);">
                                        👁 Abrir Receta
                                    </a>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 10px;">
                                        <a href="<?php echo BASE_URL; ?>/validacion/aprobar/<?php echo $p->id; ?>" class="btn btn-success" onclick="return confirm('¿Aprobar y generar código?');">
                                            ✔ Aprobar
                                        </a>
                                        <a href="<?php echo BASE_URL; ?>/validacion/rechazar/<?php echo $p->id; ?>" class="btn btn-danger" onclick="return confirm('¿Rechazar solicitud?');">
                                            ✖ Rechazar
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>