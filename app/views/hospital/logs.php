<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2 style="margin: 0;">Registro de Actividades del Sistema</h2>
    <div>
        <span style="font-size: 0.9rem; color: var(--text-light);">Mostrando los últimos 200 registros</span>
    </div>
</div>

<div class="table-container">
    <table style="font-size: 0.9rem;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Acción</th>
                <th>Fecha y Hora</th>
                <th>Ref. ID</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($datos['logs'] as $log): ?>
                <tr>
                    <td>#<?php echo $log->id; ?></td>
                    <td>
                        <strong><?php echo $log->usuario_nombre; ?></strong>
                        <br><small style="color: var(--text-light);"><?php echo $log->usuario_email; ?></small>
                    </td>
                    <td>
                        <span class="role-badge role-<?php echo $log->usuario_rol; ?>" style="font-size: 0.65rem;">
                            <?php echo ucfirst($log->usuario_rol); ?>
                        </span>
                    </td>
                    <td>
                        <span style="font-family: monospace; background: #f8f9fa; padding: 2px 5px; border-radius: 3px;">
                            <?php echo $log->accion; ?>
                        </span>
                    </td>
                    <td><?php echo date('d/m/Y H:i:s', strtotime($log->fecha)); ?></td>
                    <td>
                        <?php if($log->referencia_id): ?>
                            <span style="color: var(--primary);">#<?php echo $log->referencia_id; ?></span>
                        <?php else: ?>
                            <span style="color: #ccc;">--</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php if(empty($datos['logs'])): ?>
    <div style="text-align: center; padding: 50px; background: white; border-radius: 12px; box-shadow: var(--shadow);">
        <div style="font-size: 3rem;">📋</div>
        <h3>No hay registros de actividad</h3>
        <p>Los registros aparecerán aquí conforme se realicen acciones en el sistema.</p>
    </div>
<?php endif; ?>

<div style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 10px;">
    <h3 style="margin-top: 0;">Información del Registro</h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
        <div class="card" style="padding: 15px;">
            <h4 style="color: var(--primary); margin-top: 0;">📊 Total de Registros</h4>
            <p style="font-size: 1.5rem; font-weight: bold; margin: 0;"><?php echo count($datos['logs']); ?></p>
        </div>
        <div class="card" style="padding: 15px;">
            <h4 style="color: var(--success); margin-top: 0;">🕒 Último Registro</h4>
            <p style="margin: 0;">
                <?php if(!empty($datos['logs'])): ?>
                    <?php echo date('d/m/Y H:i', strtotime($datos['logs'][0]->fecha)); ?>
                <?php else: ?>
                    Sin registros
                <?php endif; ?>
            </p>
        </div>
        <div class="card" style="padding: 15px;">
            <h4 style="color: var(--warning); margin-top: 0;">🔍 Filtros</h4>
            <p style="margin: 0; font-size: 0.9rem;">
                <button class="btn btn-outline" style="padding: 5px 10px; font-size: 0.8rem;">Por Usuario</button>
                <button class="btn btn-outline" style="padding: 5px 10px; font-size: 0.8rem;">Por Fecha</button>
            </p>
        </div>
    </div>
</div>