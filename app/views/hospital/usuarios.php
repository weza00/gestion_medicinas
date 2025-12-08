<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2 style="margin: 0;"><i class="material-icons" style="vertical-align: middle; margin-right: 10px;">group</i>Gestión de Usuarios</h2>
    <a href="<?php echo BASE_URL; ?>/hospital/crear_usuario" class="btn btn-success">
        <i class="material-icons" style="vertical-align: middle; margin-right: 5px;">person_add</i> Nuevo Usuario
    </a>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>DNI</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Fecha Registro</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($datos['usuarios'] as $user): ?>
                <tr>
                    <td>#<?php echo $user->id; ?></td>
                    <td><strong><?php echo $user->nombre; ?></strong></td>
                    <td><?php echo $user->dni; ?></td>
                    <td><?php echo $user->email; ?></td>
                    <td>
                        <span class="role-badge role-<?php echo $user->rol; ?>">
                            <?php 
                            $iconos_rol = [
                                'paciente' => 'local_hospital',
                                'admin' => 'admin_panel_settings',
                                'validador' => 'verified_user',
                                'farmaceutico' => 'medication'
                            ];
                            ?>
                            <i class="material-icons" style="font-size: 14px; vertical-align: middle;"><?php echo $iconos_rol[$user->rol]; ?></i>
                            <?php echo ucfirst($user->rol); ?>
                        </span>
                    </td>
                    <td>
                        <span class="status-badge status-<?php echo $user->estado ? 'activo' : 'inactivo'; ?>">
                            <i class="material-icons" style="font-size: 14px; vertical-align: middle;"><?php echo $user->estado ? 'check_circle' : 'cancel'; ?></i>
                            <?php echo $user->estado ? 'Activo' : 'Inactivo'; ?>
                        </span>
                    </td>
                    <td><?php echo date('d/m/Y H:i', strtotime($user->creado_en)); ?></td>
                    <td>
                        <button onclick="editarUsuario(<?php echo $user->id; ?>)" class="btn btn-outline" style="font-size: 0.8rem; padding: 5px 10px; margin-right: 5px;">
                            <i class="material-icons" style="font-size: 14px; vertical-align: middle;">edit</i> Editar
                        </button>
                        <?php if($user->id != $_SESSION['user_id']): ?>
                            <?php if($user->estado): ?>
                                <button onclick="confirmarDesactivar(<?php echo $user->id; ?>, '<?php echo htmlspecialchars($user->nombre); ?>')" class="btn btn-outline" style="font-size: 0.8rem; padding: 5px 10px; color: var(--danger); border-color: var(--danger);">
                                    <i class="material-icons" style="font-size: 14px; vertical-align: middle;">block</i> Desactivar
                                </button>
                            <?php else: ?>
                                <button onclick="confirmarActivar(<?php echo $user->id; ?>, '<?php echo htmlspecialchars($user->nombre); ?>')" class="btn btn-outline" style="font-size: 0.8rem; padding: 5px 10px; color: var(--success); border-color: var(--success);">
                                    <i class="material-icons" style="font-size: 14px; vertical-align: middle;">check_circle</i> Activar
                                </button>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
function editarUsuario(id) {
    window.location.href = '<?php echo BASE_URL; ?>/hospital/editar_usuario/' + id;
}

function confirmarDesactivar(id, nombre) {
    if (confirm('¿Está seguro que desea desactivar al usuario "' + nombre + '"?\n\nEsta acción impedirá que el usuario pueda acceder al sistema.')) {
        window.location.href = '<?php echo BASE_URL; ?>/hospital/desactivar_usuario/' + id;
    }
}

function confirmarActivar(id, nombre) {
    if (confirm('¿Está seguro que desea activar al usuario "' + nombre + '"?\n\nEsta acción permitirá que el usuario pueda acceder al sistema nuevamente.')) {
        window.location.href = '<?php echo BASE_URL; ?>/hospital/activar_usuario/' + id;
    }
}
</script>