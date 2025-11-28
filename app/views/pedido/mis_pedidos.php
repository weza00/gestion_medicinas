<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Pedidos - MediPlus</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/style.css">
</head>
<body>

    <nav class="navbar">
        <div class="logo">Medi<span>Plus</span></div>
        <div class="nav-links">
            <a href="<?php echo BASE_URL; ?>/home">Inicio</a>
            <a href="<?php echo BASE_URL; ?>/catalogo">Catálogo</a>
            <a href="<?php echo BASE_URL; ?>/auth/logout" style="color: var(--danger);">Salir</a>
        </div>
    </nav>

    <div class="container">
        <h1 class="section-title">Historial de Pedidos</h1>

        <?php if(empty($data['pedidos'])): ?>
            <div class="alert alert-error" style="text-align: center; background: white; border-color: #eee;">
                No has realizado ningún pedido aún. <br><br>
                <a href="<?php echo BASE_URL; ?>/catalogo" class="btn btn-primary">Ir al Catálogo</a>
            </div>
        <?php else: ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Código de Retiro</th>
                            <th>Receta</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['pedidos'] as $p): ?>
                            <tr>
                                <td><?php echo date('d/m/Y H:i', strtotime($p->creado_en)); ?></td>
                                
                                <td>
                                    <span class="badge-status status-<?php echo $p->estado; ?>">
                                        <?php echo ucfirst($p->estado); ?>
                                    </span>
                                </td>
                                
                                <td>
                                    <?php if($p->estado == 'aprobado'): ?>
                                        <div class="code-box"><?php echo $p->codigo_retiro; ?></div>
                                        <div style="font-size: 0.8rem; color: var(--text-light);">Presentar en farmacia</div>
                                    <?php elseif($p->estado == 'entregado'): ?>
                                        <span style="text-decoration: line-through; color: #aaa;"><?php echo $p->codigo_retiro; ?></span>
                                        <br><small>Entregado</small>
                                    <?php else: ?>
                                        <span style="color: #aaa;">---</span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <a href="<?php echo BASE_URL; ?>/uploads/<?php echo $p->receta_archivo; ?>" target="_blank" class="btn btn-outline" style="padding: 5px 10px; font-size: 0.8rem;">
                                        📄 Ver Archivo
                                    </a>
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