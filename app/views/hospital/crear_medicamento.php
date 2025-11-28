<!-- Vista de Crear Medicamento -->
<div class="page-header">
    <h1><?php echo $datos['titulo_pagina']; ?></h1>
    <a href="<?php echo BASE_URL; ?>/hospital/medicamentos" class="btn btn-secondary">← Volver al Inventario</a>
</div>

<div class="card">
    <form method="POST" class="form">
        <div class="form-row">
            <div class="form-group">
                <label for="nombre">Nombre del Medicamento *</label>
                <input type="text" 
                       id="nombre" 
                       name="nombre" 
                       required 
                       maxlength="100"
                       placeholder="Ej: Paracetamol, Ibuprofeno, Amoxicilina..."
                       class="form-control">
                <small class="form-text">Nombre comercial o genérico del medicamento.</small>
            </div>

            <div class="form-group">
                <label for="categoria_id">Categoría *</label>
                <select id="categoria_id" name="categoria_id" required class="form-control">
                    <option value="">Seleccionar categoría...</option>
                    <?php foreach($datos['categorias'] as $categoria): ?>
                        <option value="<?php echo $categoria->id; ?>">
                            <?php echo htmlspecialchars($categoria->nombre); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <small class="form-text">
                    Si no encuentras la categoría, 
                    <a href="<?php echo BASE_URL; ?>/hospital/crear_categoria" target="_blank">créala aquí</a>.
                </small>
            </div>
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción *</label>
            <textarea id="descripcion" 
                      name="descripcion" 
                      required
                      rows="3" 
                      maxlength="500"
                      placeholder="Descripción detallada del medicamento, indicaciones, componente activo..."
                      class="form-control"></textarea>
            <small class="form-text">Máximo 500 caracteres. Información que aparecerá en el catálogo público.</small>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="presentacion">Presentación *</label>
                <input type="text" 
                       id="presentacion" 
                       name="presentacion" 
                       required 
                       maxlength="100"
                       placeholder="Ej: Tabletas 500mg x 20, Jarabe 100ml, Ampolla 10ml..."
                       class="form-control">
                <small class="form-text">Forma farmacéutica y contenido del medicamento.</small>
            </div>

            <div class="form-group">
                <label for="precio">Precio *</label>
                <div class="input-group">
                    <span class="input-prefix">$</span>
                    <input type="number" 
                           id="precio" 
                           name="precio" 
                           required 
                           min="0.01"
                           step="0.01"
                           placeholder="0.00"
                           class="form-control">
                </div>
                <small class="form-text">Precio de venta por unidad o presentación.</small>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="stock">Stock Inicial *</label>
                <input type="number" 
                       id="stock" 
                       name="stock" 
                       required 
                       min="0"
                       placeholder="0"
                       class="form-control">
                <small class="form-text">Cantidad disponible en inventario.</small>
            </div>

            <div class="form-group">
                <label for="estado_info">Estado</label>
                <div class="estado-info">
                    ✅ <strong>Activo</strong> - El medicamento se creará como activo y disponible en el catálogo.
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn">💾 Crear Medicamento</button>
            <a href="<?php echo BASE_URL; ?>/hospital/medicamentos" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<style>
.form {
    max-width: 800px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 0;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
    color: var(--primary-dark);
}

.form-control {
    width: 100%;
    padding: 12px;
    border: 2px solid #e1e5e9;
    border-radius: 8px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

.form-control:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(var(--primary-rgb), 0.1);
}

.input-group {
    display: flex;
    position: relative;
}

.input-prefix {
    background: #f8f9fa;
    border: 2px solid #e1e5e9;
    border-right: none;
    padding: 12px;
    border-radius: 8px 0 0 8px;
    color: var(--primary-dark);
    font-weight: 600;
}

.input-group .form-control {
    border-left: none;
    border-radius: 0 8px 8px 0;
}

.input-group .form-control:focus {
    border-left: 2px solid var(--primary);
}

.form-text {
    display: block;
    margin-top: 5px;
    font-size: 0.85rem;
    color: var(--text-light);
}

.form-text a {
    color: var(--primary);
    text-decoration: none;
}

.form-text a:hover {
    text-decoration: underline;
}

.estado-info {
    background: #d4edda;
    border: 1px solid #c3e6cb;
    border-radius: 8px;
    padding: 12px;
    color: #155724;
    font-size: 0.9rem;
}

.form-actions {
    display: flex;
    gap: 10px;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #5a6268;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .form {
        max-width: none;
    }
}
</style>