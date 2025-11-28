<!-- Vista de Crear Categoría -->
<div class="page-header">
    <h1><?php echo $datos['titulo_pagina']; ?></h1>
    <a href="<?php echo BASE_URL; ?>/hospital/categorias" class="btn btn-secondary">← Volver al Listado</a>
</div>

<div class="card">
    <form method="POST" class="form">
        <div class="form-group">
            <label for="nombre">Nombre de la Categoría *</label>
            <input type="text" 
                   id="nombre" 
                   name="nombre" 
                   required 
                   maxlength="100"
                   placeholder="Ej: Analgésicos, Antibióticos, Vitaminas..."
                   class="form-control">
            <small class="form-text">Máximo 100 caracteres. Este nombre aparecerá en el catálogo público.</small>
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción (Opcional)</label>
            <textarea id="descripcion" 
                      name="descripcion" 
                      rows="3" 
                      maxlength="255"
                      placeholder="Descripción detallada de la categoría..."
                      class="form-control"></textarea>
            <small class="form-text">Máximo 255 caracteres. Descripción informativa para el personal.</small>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn">💾 Crear Categoría</button>
            <a href="<?php echo BASE_URL; ?>/hospital/categorias" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<style>
.form {
    max-width: 500px;
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

.form-text {
    display: block;
    margin-top: 5px;
    font-size: 0.85rem;
    color: var(--text-light);
}

.form-actions {
    display: flex;
    gap: 10px;
    margin-top: 30px;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #5a6268;
}

@media (max-width: 768px) {
    .form-actions {
        flex-direction: column;
    }
    
    .form {
        max-width: none;
    }
}
</style>