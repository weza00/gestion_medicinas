<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2 style="margin: 0;">
        <i class="material-icons" style="vertical-align: middle; margin-right: 10px;">description</i>
        Registro de Actividades del Sistema
    </h2>
    <div>
        <span style="font-size: 0.9rem; color: var(--text-light);">Mostrando los últimos 200 registros</span>
    </div>
</div>

<!-- Filtros -->
<div class="card" style="padding: 20px; margin-bottom: 20px;">
    <h3 style="margin-top: 0; color: var(--primary-dark);">
        <i class="material-icons" style="vertical-align: middle; margin-right: 10px;">filter_list</i>
        Filtros de Búsqueda
    </h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; align-items: end;">
        <div class="form-group">
            <label><i class="material-icons" style="vertical-align: middle; margin-right: 5px;">person</i>Usuario</label>
            <select id="filtroUsuario" onchange="aplicarFiltros()">
                <option value="">Todos los usuarios</option>
                <?php 
                $usuarios_unicos = array_unique(array_map(function($log) { return $log->usuario_nombre; }, $datos['logs']));
                foreach($usuarios_unicos as $usuario): 
                ?>
                    <option value="<?php echo htmlspecialchars($usuario); ?>"><?php echo htmlspecialchars($usuario); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label><i class="material-icons" style="vertical-align: middle; margin-right: 5px;">admin_panel_settings</i>Rol</label>
            <select id="filtroRol" onchange="aplicarFiltros()">
                <option value="">Todos los roles</option>
                <option value="admin">Administrador</option>
                <option value="validador">Validador</option>
                <option value="farmaceutico">Farmacéutico</option>
                <option value="paciente">Paciente</option>
            </select>
        </div>
        
        <div class="form-group">
            <label><i class="material-icons" style="vertical-align: middle; margin-right: 5px;">event</i>Fecha</label>
            <input type="date" id="filtroFecha" onchange="aplicarFiltros()" style="padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
        </div>
        
        <div class="form-group">
            <label><i class="material-icons" style="vertical-align: middle; margin-right: 5px;">search</i>Acción</label>
            <input type="text" id="filtroAccion" placeholder="Buscar acción..." onkeyup="aplicarFiltros()" style="padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
        </div>
        
        <div class="form-group">
            <button onclick="limpiarFiltros()" class="btn btn-outline" style="padding: 10px 15px;">
                <i class="material-icons" style="vertical-align: middle; margin-right: 5px;">clear</i>
                Limpiar
            </button>
        </div>
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
        <i class="material-icons" style="font-size: 4rem; color: var(--text-light); margin-bottom: 15px;">description</i>
        <h3>No hay registros de actividad</h3>
        <p>Los registros aparecerán aquí conforme se realicen acciones en el sistema.</p>
    </div>
<?php endif; ?>

<script>
function aplicarFiltros() {
    const filtroUsuario = document.getElementById('filtroUsuario').value.toLowerCase();
    const filtroRol = document.getElementById('filtroRol').value;
    const filtroFecha = document.getElementById('filtroFecha').value;
    const filtroAccion = document.getElementById('filtroAccion').value.toLowerCase();
    
    const filas = document.querySelectorAll('tbody tr');
    
    filas.forEach(fila => {
        const usuario = fila.cells[1].textContent.toLowerCase();
        const rol = fila.querySelector('.role-badge').classList.contains('role-' + filtroRol) || filtroRol === '';
        const fecha = fila.cells[4].textContent;
        const accion = fila.cells[3].textContent.toLowerCase();
        
        let mostrarFila = true;
        
        if (filtroUsuario && !usuario.includes(filtroUsuario)) {
            mostrarFila = false;
        }
        
        if (filtroRol && !rol) {
            mostrarFila = false;
        }
        
        if (filtroFecha) {
            const fechaFila = new Date(fecha.split(' ')[0].split('/').reverse().join('-'));
            const fechaFiltro = new Date(filtroFecha);
            if (fechaFila.toDateString() !== fechaFiltro.toDateString()) {
                mostrarFila = false;
            }
        }
        
        if (filtroAccion && !accion.includes(filtroAccion)) {
            mostrarFila = false;
        }
        
        fila.style.display = mostrarFila ? '' : 'none';
    });
}

function limpiarFiltros() {
    document.getElementById('filtroUsuario').value = '';
    document.getElementById('filtroRol').value = '';
    document.getElementById('filtroFecha').value = '';
    document.getElementById('filtroAccion').value = '';
    aplicarFiltros();
}
</script>