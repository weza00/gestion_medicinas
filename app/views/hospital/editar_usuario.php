<div style="max-width: 600px; margin: 0 auto;">
    <div class="card" style="padding: 30px;">
        <h2 style="margin-top: 0; color: var(--primary-dark);">
            <i class="material-icons" style="vertical-align: middle; margin-right: 10px;">edit</i>Editar Usuario
        </h2>
        <p style="color: var(--text-light); margin-bottom: 30px;">Modifique la información del usuario.</p>
        
        <form action="<?php echo BASE_URL; ?>/hospital/editar_usuario/<?php echo $datos['usuario']->id; ?>" method="POST" onsubmit="return validarFormulario()">
            
            <div class="form-group">
                <label><i class="material-icons" style="vertical-align: middle; margin-right: 5px;">person</i>Nombre Completo</label>
                <input type="text" name="nombre" value="<?php echo htmlspecialchars($datos['usuario']->nombre); ?>" placeholder="Ej: Juan Pérez" required>
            </div>

            <div class="form-group">
                <label><i class="material-icons" style="vertical-align: middle; margin-right: 5px;">badge</i>Cédula de Identidad</label>
                <input type="text" name="dni" id="dni" value="<?php echo htmlspecialchars($datos['usuario']->dni); ?>" placeholder="1234567890" maxlength="10" pattern="[0-9]{10}" required>
                <small style="color: #666; font-size: 0.85rem;">Cédula ecuatoriana de 10 dígitos</small>
                <div id="dni-error" style="color: #dc3545; font-size: 0.85rem; margin-top: 5px; display: none;"></div>
            </div>

            <div class="form-group">
                <label><i class="material-icons" style="vertical-align: middle; margin-right: 5px;">email</i>Correo Electrónico</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($datos['usuario']->email); ?>" placeholder="ejemplo@correo.com" required>
            </div>

            <div class="form-group">
                <label><i class="material-icons" style="vertical-align: middle; margin-right: 5px;">admin_panel_settings</i>Rol del Usuario</label>
                <select name="rol" required>
                    <option value="">Seleccione un rol...</option>
                    <option value="paciente" <?php echo ($datos['usuario']->rol == 'paciente') ? 'selected' : ''; ?>>
                        <i class="material-icons">local_hospital</i> Paciente
                    </option>
                    <option value="validador" <?php echo ($datos['usuario']->rol == 'validador') ? 'selected' : ''; ?>>
                        <i class="material-icons">verified_user</i> Validador
                    </option>
                    <option value="farmaceutico" <?php echo ($datos['usuario']->rol == 'farmaceutico') ? 'selected' : ''; ?>>
                        <i class="material-icons">medication</i> Farmacéutico
                    </option>
                    <option value="admin" <?php echo ($datos['usuario']->rol == 'admin') ? 'selected' : ''; ?>>
                        <i class="material-icons">admin_panel_settings</i> Administrador
                    </option>
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 30px;">
                <a href="<?php echo BASE_URL; ?>/hospital/usuarios" class="btn btn-outline">
                    <i class="material-icons" style="vertical-align: middle; margin-right: 5px;">arrow_back</i> Cancelar
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="material-icons" style="vertical-align: middle; margin-right: 5px;">save</i> Actualizar Usuario
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function validarCedulaEcuatoriana(cedula) {
    // Limpiar la cédula
    cedula = cedula.replace(/[^0-9]/g, '');
    
    // Verificar que tenga 10 dígitos
    if (cedula.length !== 10) {
        return false;
    }
    
    // Verificar provincia (01-24)
    const provincia = parseInt(cedula.substring(0, 2));
    if (provincia < 1 || provincia > 24) {
        return false;
    }
    
    // Algoritmo de validación
    const digitos = cedula.split('').map(Number);
    const digitoVerificador = digitos[9];
    
    let suma = 0;
    for (let i = 0; i < 9; i++) {
        let digito = digitos[i];
        if (i % 2 === 0) { // Posiciones impares
            digito *= 2;
            if (digito > 9) {
                digito -= 9;
            }
        }
        suma += digito;
    }
    
    const modulo = suma % 10;
    const digitoEsperado = modulo === 0 ? 0 : 10 - modulo;
    
    return digitoEsperado === digitoVerificador;
}

function validarFormulario() {
    const dniInput = document.getElementById('dni');
    const dniError = document.getElementById('dni-error');
    const dni = dniInput.value.trim();
    
    if (!validarCedulaEcuatoriana(dni)) {
        dniError.textContent = 'La cédula ingresada no es válida. Verifique los dígitos.';
        dniError.style.display = 'block';
        dniInput.focus();
        return false;
    }
    
    dniError.style.display = 'none';
    return true;
}

// Validación en tiempo real
document.getElementById('dni').addEventListener('input', function() {
    const dniError = document.getElementById('dni-error');
    const dni = this.value.trim();
    
    if (dni.length === 10) {
        if (!validarCedulaEcuatoriana(dni)) {
            dniError.textContent = 'Cédula no válida';
            dniError.style.display = 'block';
            this.style.borderColor = '#dc3545';
        } else {
            dniError.style.display = 'none';
            this.style.borderColor = '#28a745';
        }
    } else {
        dniError.style.display = 'none';
        this.style.borderColor = '';
    }
});
</script>