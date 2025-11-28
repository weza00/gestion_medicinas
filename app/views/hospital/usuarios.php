<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2 style="margin: 0;">Gestión de Usuarios</h2>
    <a href="<?php echo BASE_URL; ?>/hospital/crear_usuario" class="btn btn-success">
        + Nuevo Usuario
    </a>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Fecha Registro</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($datos['usuarios'] as $user): ?>
                <tr>
                    <td>#<?php echo $user->id; ?></td>
                    <td><strong><?php echo $user->nombre; ?></strong></td>
                    <td><?php echo $user->email; ?></td>
                    <td>
                        <span class="role-badge role-<?php echo $user->rol; ?>">
                            <?php echo ucfirst($user->rol); ?>
                        </span>
                    </td>
                    <td><?php echo date('d/m/Y H:i', strtotime($user->creado_en)); ?></td>
                    <td>
                        <button class="btn btn-outline" style="font-size: 0.8rem; padding: 5px 10px;">✏️ Editar</button>
                        <?php if($user->id != $_SESSION['user_id']): ?>
                            <button class="btn btn-outline" style="font-size: 0.8rem; padding: 5px 10px; color: var(--danger); border-color: var(--danger);">🚫 Desactivar</button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 10px;">
    <h3 style="margin-top: 0;">Estadísticas de Usuarios</h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
        <?php
        $stats = ['paciente' => 0, 'admin' => 0, 'validador' => 0, 'farmaceutico' => 0];
        foreach($datos['usuarios'] as $u) {
            $stats[$u->rol]++;
        }
        ?>
        <div class="card" style="text-align: center; padding: 15px;">
            <h4 style="color: var(--primary);"><?php echo $stats['paciente']; ?></h4>
            <p>Pacientes</p>
        </div>
        <div class="card" style="text-align: center; padding: 15px;">
            <h4 style="color: #6f42c1;"><?php echo $stats['admin']; ?></h4>
            <p>Administradores</p>
        </div>
        <div class="card" style="text-align: center; padding: 15px;">
            <h4 style="color: #28a745;"><?php echo $stats['validador']; ?></h4>
            <p>Validadores</p>
        </div>
        <div class="card" style="text-align: center; padding: 15px;">
            <h4 style="color: #17a2b8;"><?php echo $stats['farmaceutico']; ?></h4>
            <p>Farmacéuticos</p>
        </div>
    </div>
</div>