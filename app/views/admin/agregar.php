<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Medicamento</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/style.css">
</head>
<body>

    <nav class="navbar">
        <div class="logo">Panel<span>Admin</span></div>
        <div class="nav-links">
            <a href="<?php echo BASE_URL; ?>/admin/index">Volver al Listado</a>
        </div>
    </nav>

    <div class="auth-wrapper" style="min-height: auto; margin-top: 40px; padding-bottom: 40px;">
        <div class="auth-card" style="max-width: 600px; text-align: left;">
            <h2 class="section-title" style="margin-top: 0;">Registrar Medicamento</h2>
            
            <form action="<?php echo BASE_URL; ?>/admin/agregar" method="POST">
                
                <div class="form-group">
                    <label>Nombre del Medicamento</label>
                    <input type="text" name="nombre" placeholder="Ej: Aspirina Forte" required>
                </div>

                <div class="form-group">
                    <label>Categoría (Sección)</label>
                    <select name="categoria_id" required>
                        <option value="">Seleccione una sección...</option>
                        <?php foreach($data['categorias'] as $cat): ?>
                            <option value="<?php echo $cat->id; ?>"><?php echo $cat->nombre; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Descripción</label>
                    <textarea name="descripcion" rows="3" placeholder="Detalles del producto..."></textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label>Presentación</label>
                        <input type="text" name="presentacion" placeholder="Ej: Caja 10 un." required>
                    </div>
                    <div class="form-group">
                        <label>Stock Inicial</label>
                        <input type="number" name="stock" placeholder="0" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Precio Unitario ($)</label>
                    <input type="number" step="0.01" name="precio" placeholder="0.00" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Guardar en Catálogo</button>
            </form>
        </div>
    </div>

</body>
</html>