<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px;">
    <h2 style="margin: 0;">Inventario de Medicamentos</h2>
    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
        <a href="<?php echo BASE_URL; ?>/hospital/categorias" class="btn" style="background: var(--warning); color: white;">
            🏷️ Gestionar Categorías
        </a>
        <a href="<?php echo BASE_URL; ?>/hospital/crear_medicamento" class="btn btn-success">
            + Nuevo Medicamento
        </a>
    </div>
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
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($datos['medicamentos'] as $med): ?>
                <tr>
                    <td>#<?php echo $med->id; ?></td>
                    <td>
                        <strong><?php echo $med->nombre; ?></strong>
                        <br><small style="color: var(--text-light);"><?php echo substr($med->descripcion, 0, 50); ?>...</small>
                    </td>
                    <td>
                        <span class="card-cat" style="font-size: 0.7rem; margin: 0;"><?php echo $med->categoria_nombre; ?></span>
                    </td>
                    <td><?php echo $med->presentacion; ?></td>
                    
                    <td>
                        <?php if($med->stock < 10): ?>
                            <span style="color: var(--danger); font-weight: bold;"><?php echo $med->stock; ?> (Bajo)</span>
                        <?php elseif($med->stock < 50): ?>
                            <span style="color: var(--warning); font-weight: bold;"><?php echo $med->stock; ?> (Medio)</span>
                        <?php else: ?>
                            <span style="color: var(--success); font-weight: bold;"><?php echo $med->stock; ?></span>
                        <?php endif; ?>
                    </td>
                    
                    <td>$<?php echo number_format($med->precio, 2); ?></td>
                    
                    <td>
                        <?php if($med->estado == 1): ?>
                            <span class="badge-status status-aprobado">Activo</span>
                        <?php else: ?>
                            <span class="badge-status status-rechazado">Inactivo</span>
                        <?php endif; ?>
                    </td>
                    
                    <td>
                        <a href="<?php echo BASE_URL; ?>/hospital/editar_medicamento/<?php echo $med->id; ?>" 
                           class="btn btn-outline" style="font-size: 0.8rem; padding: 5px 10px;">✏️ Editar</a>
                        <button onclick="confirmarEliminar(<?php echo $med->id; ?>, '<?php echo addslashes($med->nombre); ?>')" 
                                class="btn btn-outline" style="font-size: 0.8rem; padding: 5px 10px; color: var(--danger); border-color: var(--danger);">🗑️ Eliminar</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php if(empty($datos['medicamentos'])): ?>
    <div style="text-align: center; padding: 50px; background: white; border-radius: 12px; box-shadow: var(--shadow);">
        <div style="font-size: 3rem;">📦</div>
        <h3>No hay medicamentos registrados</h3>
        <p>Comience agregando medicamentos al inventario.</p>
        <a href="<?php echo BASE_URL; ?>/hospital/crear_medicamento" class="btn btn-success">+ Agregar Primer Medicamento</a>
    </div>
<?php endif; ?>

<script>
function confirmarEliminar(id, nombre) {
    if (confirm('¿Estás seguro de que deseas eliminar el medicamento "' + nombre + '"?\n\nEsta acción no se puede deshacer y afectará el inventario.')) {
        window.location.href = '<?php echo BASE_URL; ?>/hospital/eliminar_medicamento/' + id;
    }
}
</script>