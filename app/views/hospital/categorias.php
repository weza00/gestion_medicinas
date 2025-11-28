<!-- Vista de Gestión de Categorías -->
<div class="page-header">
    <h1><?php echo $datos['titulo_pagina']; ?></h1>
    <a href="<?php echo BASE_URL; ?>/hospital/crear_categoria" class="btn">+ Nueva Categoría</a>
</div>

<div class="card">
    <?php if(empty($datos['categorias'])): ?>
        <p style="text-align: center; color: var(--text-light); padding: 40px;">
            No hay categorías registradas. <a href="<?php echo BASE_URL; ?>/hospital/crear_categoria">Crear la primera categoría</a>
        </p>
    <?php else: ?>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($datos['categorias'] as $categoria): ?>
                        <tr>
                            <td><?php echo $categoria->id; ?></td>
                            <td><strong><?php echo htmlspecialchars($categoria->nombre); ?></strong></td>
                            <td><?php echo htmlspecialchars($categoria->descripcion ?: 'Sin descripción'); ?></td>
                            <td>
                                <div class="actions">
                                    <a href="<?php echo BASE_URL; ?>/hospital/editar_categoria/<?php echo $categoria->id; ?>" class="btn btn-small" style="background: var(--warning);">
                                        ✏️ Editar
                                    </a>
                                    <button onclick="confirmarEliminar(<?php echo $categoria->id; ?>, '<?php echo addslashes($categoria->nombre); ?>')" 
                                            class="btn btn-small" style="background: var(--danger);">
                                        🗑️ Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script>
function confirmarEliminar(id, nombre) {
    if (confirm('¿Estás seguro de que deseas eliminar la categoría "' + nombre + '"?\n\nEsta acción no se puede deshacer.')) {
        window.location.href = '<?php echo BASE_URL; ?>/hospital/eliminar_categoria/' + id;
    }
}
</script>

<style>
.actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.btn-small {
    padding: 6px 12px;
    font-size: 0.85rem;
    text-decoration: none;
    border: none;
    cursor: pointer;
}

.table-container {
    overflow-x: auto;
}

.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 0;
}

.table th, .table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.table th {
    background-color: #f8f9fa;
    font-weight: 600;
    color: var(--primary-dark);
}

.table tr:hover {
    background-color: #f8f9fa;
}

@media (max-width: 768px) {
    .actions {
        flex-direction: column;
    }
    
    .btn-small {
        font-size: 0.8rem;
        padding: 4px 8px;
    }
}
</style>