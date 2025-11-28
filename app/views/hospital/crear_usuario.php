<div style="max-width: 600px; margin: 0 auto;">
    <div class="card" style="padding: 30px;">
        <h2 style="margin-top: 0; color: var(--primary-dark);">Crear Nuevo Usuario</h2>
        <p style="color: var(--text-light); margin-bottom: 30px;">Complete la información para crear una nueva cuenta de usuario.</p>
        
        <form action="<?php echo BASE_URL; ?>/hospital/crear_usuario" method="POST">
            
            <div class="form-group">
                <label>Nombre Completo</label>
                <input type="text" name="nombre" placeholder="Ej: Juan Pérez" required>
            </div>

            <div class="form-group">
                <label>Correo Electrónico</label>
                <input type="email" name="email" placeholder="ejemplo@correo.com" required>
            </div>

            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="password" placeholder="Mínimo 6 caracteres" required minlength="6">
            </div>

            <div class="form-group">
                <label>Rol del Usuario</label>
                <select name="rol" required>
                    <option value="">Seleccione un rol...</option>
                    <option value="paciente">Paciente</option>
                    <option value="validador">Validador</option>
                    <option value="farmaceutico">Farmacéutico</option>
                    <option value="admin">Administrador</option>
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 30px;">
                <a href="<?php echo BASE_URL; ?>/hospital/usuarios" class="btn btn-outline">
                    ← Cancelar
                </a>
                <button type="submit" class="btn btn-success">
                    Crear Usuario
                </button>
            </div>
        </form>
    </div>

    <div class="card" style="margin-top: 20px; padding: 20px; background: #f8f9fa;">
        <h3 style="margin-top: 0; color: var(--primary-dark);">Información sobre Roles</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
            <div>
                <h4 style="color: var(--primary); margin-bottom: 5px;">👥 Paciente</h4>
                <p style="font-size: 0.9rem; margin: 0; color: var(--text-light);">Puede ver medicamentos y realizar pedidos con receta médica.</p>
            </div>
            <div>
                <h4 style="color: #28a745; margin-bottom: 5px;">✅ Validador</h4>
                <p style="font-size: 0.9rem; margin: 0; color: var(--text-light);">Revisa y aprueba recetas médicas de pacientes.</p>
            </div>
            <div>
                <h4 style="color: #17a2b8; margin-bottom: 5px;">💊 Farmacéutico</h4>
                <p style="font-size: 0.9rem; margin: 0; color: var(--text-light);">Entrega medicamentos usando códigos de pedido.</p>
            </div>
            <div>
                <h4 style="color: #6f42c1; margin-bottom: 5px;">⚙️ Admin</h4>
                <p style="font-size: 0.9rem; margin: 0; color: var(--text-light);">Control total del sistema, usuarios y medicamentos.</p>
            </div>
        </div>
    </div>
</div>