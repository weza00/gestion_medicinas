<?php
class HospitalController extends Controller {
    private $pedidoModelo;
    private $medicamentoModelo;
    private $usuarioModelo;
    private $categoriaModelo;
    private $logModelo;

    public function __construct() {
        // Verificar que el usuario tenga un rol hospitalario
        if (!isset($_SESSION['user_rol']) || 
            !in_array($_SESSION['user_rol'], ['admin', 'validador', 'farmaceutico'])) {
            header('location: ' . BASE_URL . '/auth/login');
            exit();
        }

        // Cargar modelos
        $this->pedidoModelo = $this->model('Pedido');
        $this->medicamentoModelo = $this->model('Medicamento');
        $this->categoriaModelo = $this->model('Categoria');
        $this->logModelo = $this->model('Log');
        
        // Cargar modelo Usuario solo si es admin (para gestión de usuarios)
        if ($_SESSION['user_rol'] == 'admin') {
            $this->usuarioModelo = $this->model('Usuario');
        }
    }

    // Página de inicio - Visible para todos los roles hospitalarios
    public function inicio() {
        // Registrar acceso al panel
        $this->logModelo->registrar($_SESSION['user_id'], 'Acceso al panel hospitalario');

        $datos = [
            'titulo' => 'Panel Hospitalario',
            'titulo_pagina' => 'Bienvenido al Panel Hospitalario',
            'seccion' => 'inicio',
            'user_nombre' => $_SESSION['user_nombre'],
            'user_rol' => $_SESSION['user_rol']
        ];

        $contenido = $this->renderInicioContent($datos);
        $this->renderLayout($contenido, $datos);
    }

    // Validación de pedidos - Solo validador
    public function validar() {
        if ($_SESSION['user_rol'] != 'validador') {
            die('Acceso denegado.');
        }

        $pendientes = $this->pedidoModelo->obtenerPendientes();
        
        // Obtener detalles de cada pedido
        foreach ($pendientes as $pedido) {
            $pedido->detalles = $this->pedidoModelo->obtenerDetalles($pedido->id);
            
            // Calcular totales
            $pedido->total_medicamentos = 0;
            $pedido->total_precio = 0;
            
            foreach ($pedido->detalles as $detalle) {
                $pedido->total_medicamentos += $detalle->cantidad;
                $pedido->total_precio += $detalle->cantidad * $detalle->precio;
            }
        }

        $datos = [
            'titulo' => 'Validar Pedidos',
            'titulo_pagina' => 'Validación de Recetas Médicas',
            'seccion' => 'validar',
            'pendientes' => $pendientes
        ];

        $contenido = $this->renderValidarContent($datos);
        $this->renderLayout($contenido, $datos);
    }

    // Entrega de pedidos - Solo farmacéutico
    public function entregar() {
        if ($_SESSION['user_rol'] != 'farmaceutico') {
            die('Acceso denegado.');
        }

        $resultado = null;
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['codigo'])) {
            $codigo = trim($_POST['codigo']);
            $pedido = $this->pedidoModelo->obtenerPorCodigo($codigo);

            if ($pedido) {
                if ($pedido->estado == 'aprobado') {
                    // Obtener detalles completos del pedido
                    $pedido->detalles = $this->pedidoModelo->obtenerDetalles($pedido->id);
                    
                    // Calcular totales
                    $pedido->total_medicamentos = 0;
                    $pedido->total_precio = 0;
                    
                    foreach ($pedido->detalles as $detalle) {
                        $pedido->total_medicamentos += $detalle->cantidad;
                        $pedido->total_precio += $detalle->cantidad * $detalle->precio;
                    }
                    
                    $resultado = $pedido;
                } elseif ($pedido->estado == 'entregado') {
                    $error = "Este pedido YA FUE ENTREGADO anteriormente.";
                } else {
                    $error = "El pedido no está aprobado (Estado: " . $pedido->estado . ")";
                }
            } else {
                $error = "Código no encontrado.";
            }
        }

        $datos = [
            'titulo' => 'Entregar Pedidos',
            'titulo_pagina' => 'Entrega de Medicamentos',
            'seccion' => 'entregar',
            'resultado' => $resultado,
            'error' => $error
        ];

        $contenido = $this->renderEntregarContent($datos);
        $this->renderLayout($contenido, $datos);
    }

    // Gestión de medicamentos - Solo admin
    public function medicamentos() {
        if ($_SESSION['user_rol'] != 'admin') {
            die('Acceso denegado.');
        }

        $medicamentos = $this->medicamentoModelo->obtenerTodos();

        $datos = [
            'titulo' => 'Gestión de Medicamentos',
            'titulo_pagina' => 'Inventario de Medicamentos',
            'seccion' => 'medicamentos',
            'medicamentos' => $medicamentos
        ];

        $contenido = $this->renderMedicamentosContent($datos);
        $this->renderLayout($contenido, $datos);
    }

    // Gestión de usuarios - Solo admin
    public function usuarios() {
        if ($_SESSION['user_rol'] != 'admin') {
            die('Acceso denegado.');
        }

        // Obtener todos los usuarios del sistema
        $db = new Database();
        $db->query("SELECT id, nombre, dni, email, rol, estado, creado_en FROM usuarios ORDER BY creado_en DESC");
        $usuarios = $db->registers();

        $datos = [
            'titulo' => 'Gestión de Usuarios',
            'titulo_pagina' => 'Usuarios del Sistema',
            'seccion' => 'usuarios',
            'usuarios' => $usuarios
        ];

        $contenido = $this->renderUsuariosContent($datos);
        $this->renderLayout($contenido, $datos);
    }

    // Crear nuevo usuario (solo admin)
    public function crear_usuario() {
        if ($_SESSION['user_rol'] != 'admin') {
            die('Acceso denegado.');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = trim($_POST['nombre']);
            $dni = trim($_POST['dni']);
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $rol = trim($_POST['rol']);

            // Validar cédula ecuatoriana
            if (!$this->usuarioModelo->validarCedulaEcuatoriana($dni)) {
                echo "<script>alert('Error: La cédula ingresada no es válida'); window.history.back();</script>";
                return;
            }

            // Validar que el DNI no exista
            $db = new Database();
            $db->query("SELECT id FROM usuarios WHERE dni = :dni");
            $db->bind(':dni', $dni);
            $existeDNI = $db->register();

            if ($existeDNI) {
                echo "<script>alert('Error: La cédula ya está registrada'); window.history.back();</script>";
                return;
            }

            // Validar que el email no exista
            $db->query("SELECT id FROM usuarios WHERE email = :email");
            $db->bind(':email', $email);
            $existeEmail = $db->register();

            if ($existeEmail) {
                echo "<script>alert('Error: El email ya está registrado'); window.history.back();</script>";
                return;
            }

            // Crear usuario
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $db->query('INSERT INTO usuarios (nombre, dni, email, password, rol, creado_en) VALUES (:nombre, :dni, :email, :password, :rol, NOW())');
            $db->bind(':nombre', $nombre);
            $db->bind(':dni', $dni);
            $db->bind(':email', $email);
            $db->bind(':password', $password_hash);
            $db->bind(':rol', $rol);

            if ($db->execute()) {
                $this->logModelo->registrar($_SESSION['user_id'], "Usuario creado: $nombre ($rol) - DNI: $dni");
                echo "<script>alert('Usuario creado exitosamente'); window.location.href='" . BASE_URL . "/hospital/usuarios';</script>";
            } else {
                echo "<script>alert('Error al crear usuario'); window.history.back();</script>";
            }
        } else {
            // Mostrar formulario
            $datos = [
                'titulo' => 'Crear Usuario',
                'titulo_pagina' => 'Crear Nuevo Usuario',
                'seccion' => 'usuarios'
            ];

            $contenido = $this->renderCrearUsuarioContent($datos);
            $this->renderLayout($contenido, $datos);
        }
    }

    // Editar usuario - Solo admin
    public function editar_usuario($id = null) {
        if ($_SESSION['user_rol'] != 'admin') {
            die('Acceso denegado.');
        }

        if (!$id) {
            header('location: ' . BASE_URL . '/hospital/usuarios');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = trim($_POST['nombre']);
            $dni = trim($_POST['dni']);
            $email = trim($_POST['email']);
            $rol = trim($_POST['rol']);

            // Validar cédula ecuatoriana
            if (!$this->usuarioModelo->validarCedulaEcuatoriana($dni)) {
                echo "<script>alert('Error: La cédula ingresada no es válida'); window.history.back();</script>";
                return;
            }

            // Validar que el DNI no exista en otro usuario
            $db = new Database();
            $db->query("SELECT id FROM usuarios WHERE dni = :dni AND id != :id");
            $db->bind(':dni', $dni);
            $db->bind(':id', $id);
            $existeDNI = $db->register();

            if ($existeDNI) {
                echo "<script>alert('Error: La cédula ya está registrada en otro usuario'); window.history.back();</script>";
                return;
            }

            // Validar que el email no exista en otro usuario
            $db->query("SELECT id FROM usuarios WHERE email = :email AND id != :id");
            $db->bind(':email', $email);
            $db->bind(':id', $id);
            $existeEmail = $db->register();

            if ($existeEmail) {
                echo "<script>alert('Error: El email ya está registrado en otro usuario'); window.history.back();</script>";
                return;
            }

            // Actualizar usuario
            $db->query('UPDATE usuarios SET nombre = :nombre, dni = :dni, email = :email, rol = :rol WHERE id = :id');
            $db->bind(':nombre', $nombre);
            $db->bind(':dni', $dni);
            $db->bind(':email', $email);
            $db->bind(':rol', $rol);
            $db->bind(':id', $id);

            if ($db->execute()) {
                $this->logModelo->registrar($_SESSION['user_id'], "Usuario editado: $nombre ($rol) - DNI: $dni - ID: $id");
                echo "<script>alert('Usuario actualizado exitosamente'); window.location.href='" . BASE_URL . "/hospital/usuarios';</script>";
            } else {
                echo "<script>alert('Error al actualizar usuario'); window.history.back();</script>";
            }
        } else {
            // Obtener datos del usuario
            $db = new Database();
            $db->query("SELECT * FROM usuarios WHERE id = :id");
            $db->bind(':id', $id);
            $usuario = $db->register();

            if (!$usuario) {
                header('location: ' . BASE_URL . '/hospital/usuarios');
                exit();
            }

            $datos = [
                'titulo' => 'Editar Usuario',
                'titulo_pagina' => 'Editar Usuario',
                'seccion' => 'usuarios',
                'usuario' => $usuario
            ];

            $contenido = $this->renderEditarUsuarioContent($datos);
            $this->renderLayout($contenido, $datos);
        }
    }

    // Desactivar usuario - Solo admin
    public function desactivar_usuario($id = null) {
        if ($_SESSION['user_rol'] != 'admin') {
            die('Acceso denegado.');
        }

        if (!$id || $id == $_SESSION['user_id']) {
            header('location: ' . BASE_URL . '/hospital/usuarios');
            exit();
        }

        // Obtener datos del usuario antes de desactivar
        $db = new Database();
        $db->query("SELECT nombre, dni, email, rol FROM usuarios WHERE id = :id AND estado = 1");
        $db->bind(':id', $id);
        $usuario = $db->register();

        if (!$usuario) {
            echo "<script>alert('Usuario no encontrado o ya está inactivo'); window.location.href='" . BASE_URL . "/hospital/usuarios';</script>";
            return;
        }

        // Desactivar usuario (cambiar estado a 0)
        $db->query('UPDATE usuarios SET estado = 0 WHERE id = :id');
        $db->bind(':id', $id);

        if ($db->execute()) {
            $this->logModelo->registrar($_SESSION['user_id'], "Usuario desactivado: {$usuario->nombre} ({$usuario->rol}) - DNI: {$usuario->dni} - ID: $id");
            echo "<script>alert('Usuario desactivado exitosamente'); window.location.href='" . BASE_URL . "/hospital/usuarios';</script>";
        } else {
            echo "<script>alert('Error al desactivar usuario'); window.location.href='" . BASE_URL . "/hospital/usuarios';</script>";
        }
    }

    // Activar usuario - Solo admin
    public function activar_usuario($id = null) {
        if ($_SESSION['user_rol'] != 'admin') {
            die('Acceso denegado.');
        }

        if (!$id || $id == $_SESSION['user_id']) {
            header('location: ' . BASE_URL . '/hospital/usuarios');
            exit();
        }

        // Obtener datos del usuario antes de activar
        $db = new Database();
        $db->query("SELECT nombre, dni, email, rol FROM usuarios WHERE id = :id AND estado = 0");
        $db->bind(':id', $id);
        $usuario = $db->register();

        if (!$usuario) {
            echo "<script>alert('Usuario no encontrado o ya está activo'); window.location.href='" . BASE_URL . "/hospital/usuarios';</script>";
            return;
        }

        // Activar usuario (cambiar estado a 1)
        $db->query('UPDATE usuarios SET estado = 1 WHERE id = :id');
        $db->bind(':id', $id);

        if ($db->execute()) {
            $this->logModelo->registrar($_SESSION['user_id'], "Usuario activado: {$usuario->nombre} ({$usuario->rol}) - DNI: {$usuario->dni} - ID: $id");
            echo "<script>alert('Usuario activado exitosamente'); window.location.href='" . BASE_URL . "/hospital/usuarios';</script>";
        } else {
            echo "<script>alert('Error al activar usuario'); window.location.href='" . BASE_URL . "/hospital/usuarios';</script>";
        }
    }

    // Registro del sistema - Solo admin
    public function logs() {
        if ($_SESSION['user_rol'] != 'admin') {
            die('Acceso denegado.');
        }

        $logs = $this->logModelo->obtenerTodos(200); // Últimos 200 registros

        $datos = [
            'titulo' => 'Registro del Sistema',
            'titulo_pagina' => 'Log de Actividades',
            'seccion' => 'logs',
            'logs' => $logs
        ];

        $contenido = $this->renderLogsContent($datos);
        $this->renderLayout($contenido, $datos);
    }

    // Aprobar pedido (validador)
    public function aprobar($id) {
        if ($_SESSION['user_rol'] != 'validador') {
            die('Acceso denegado.');
        }

        $codigo = 'MED-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 6));

        if ($this->pedidoModelo->actualizarEstado($id, 'aprobado', $codigo)) {
            $this->logModelo->registrar($_SESSION['user_id'], "Pedido #$id aprobado con código $codigo", $id);
            header('location: ' . BASE_URL . '/hospital/validar');
        } else {
            die('Error al aprobar');
        }
    }

    // Rechazar pedido (validador)
    public function rechazar($id) {
        if ($_SESSION['user_rol'] != 'validador') {
            die('Acceso denegado.');
        }

        if ($this->pedidoModelo->actualizarEstado($id, 'rechazado', null)) {
            $this->logModelo->registrar($_SESSION['user_id'], "Pedido #$id rechazado", $id);
            header('location: ' . BASE_URL . '/hospital/validar');
        } else {
            die('Error al rechazar');
        }
    }

    // Confirmar entrega (farmacéutico)
    public function confirmar_entrega($id) {
        if ($_SESSION['user_rol'] != 'farmaceutico') {
            die('Acceso denegado.');
        }

        $db = new Database();
        $db->query("UPDATE pedidos SET estado = 'entregado' WHERE id = :id");
        $db->bind(':id', $id);
        
        if ($db->execute()) {
            $this->logModelo->registrar($_SESSION['user_id'], "Medicamentos entregados para pedido #$id", $id);
            echo "<script>alert('¡Medicamentos entregados correctamente!'); window.location.href='" . BASE_URL . "/hospital/entregar';</script>";
        } else {
            die('Error al confirmar entrega');
        }
    }

    // Render del layout principal
    private function renderLayout($contenido, $datos) {
        $data = $datos;
        ob_start();
        echo $contenido;
        $contenido = ob_get_clean();
        
        require_once '../app/views/layout/hospital_layout.php';
    }

    // Renderizar contenido de inicio
    private function renderInicioContent($datos) {
        ob_start();
        ?>
        <div class="card" style="text-align: center; padding: 40px;">
            <h2 style="color: var(--primary-dark); margin-bottom: 20px;">
                Bienvenido, <?php echo $datos['user_nombre']; ?>
            </h2>
            <p style="font-size: 1.2rem; color: var(--text-light); margin-bottom: 30px;">
                Has iniciado sesión como <span style="font-weight: bold; color: var(--primary);"><?php echo ucfirst($datos['user_rol']); ?></span>
            </p>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 30px;">
                <?php if($datos['user_rol'] == 'validador'): ?>
                    <div class="card" style="background: #e8f5e8; border-left: 4px solid #28a745;">
                        <h3>👨‍⚕️ Validador de Recetas</h3>
                        <p>Revise y valide las recetas médicas presentadas por los pacientes.</p>
                        <a href="<?php echo BASE_URL; ?>/hospital/validar" class="btn btn-success">Ir a Validación</a>
                    </div>
                <?php endif; ?>

                <?php if($datos['user_rol'] == 'farmaceutico'): ?>
                    <div class="card" style="background: #e8f4f8; border-left: 4px solid #17a2b8;">
                        <h3>💊 Farmacéutico</h3>
                        <p>Gestione la entrega de medicamentos a los pacientes.</p>
                        <a href="<?php echo BASE_URL; ?>/hospital/entregar" class="btn" style="background: #17a2b8; color: white;">Ir a Entregas</a>
                    </div>
                <?php endif; ?>

                <?php if($datos['user_rol'] == 'admin'): ?>
                    <div class="card" style="background: #f3e8ff; border-left: 4px solid #6f42c1;">
                        <h3>⚙️ Administrador</h3>
                        <p>Gestión completa del sistema, usuarios y medicamentos.</p>
                        <a href="<?php echo BASE_URL; ?>/hospital/medicamentos" class="btn" style="background: #6f42c1; color: white;">Panel Admin</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    // Renderizar contenido de validación
    private function renderValidarContent($datos) {
        ob_start();
        require_once '../app/views/hospital/validar.php';
        return ob_get_clean();
    }

    // Renderizar contenido de entrega
    private function renderEntregarContent($datos) {
        ob_start();
        require_once '../app/views/hospital/entregar.php';
        return ob_get_clean();
    }

    // Renderizar contenido de medicamentos
    private function renderMedicamentosContent($datos) {
        ob_start();
        require_once '../app/views/hospital/medicamentos.php';
        return ob_get_clean();
    }

    // Renderizar contenido de usuarios
    private function renderUsuariosContent($datos) {
        ob_start();
        require_once '../app/views/hospital/usuarios.php';
        return ob_get_clean();
    }

    // Renderizar contenido de logs
    private function renderLogsContent($datos) {
        ob_start();
        require_once '../app/views/hospital/logs.php';
        return ob_get_clean();
    }

    // Renderizar contenido de crear usuario
    private function renderCrearUsuarioContent($datos) {
        ob_start();
        require_once '../app/views/hospital/crear_usuario.php';
        return ob_get_clean();
    }

    // Renderizar contenido de editar usuario
    private function renderEditarUsuarioContent($datos) {
        ob_start();
        require_once '../app/views/hospital/editar_usuario.php';
        return ob_get_clean();
    }

    // Gestión de Categorías - Solo admin
    public function categorias() {
        if ($_SESSION['user_rol'] != 'admin') {
            die('Acceso denegado. Solo administradores pueden gestionar categorías.');
        }

        $categoriaModelo = new Categoria();
        $categorias = $categoriaModelo->obtenerTodas();

        $datos = [
            'titulo' => 'Gestión de Categorías',
            'titulo_pagina' => 'Gestión de Categorías de Medicamentos',
            'seccion' => 'medicamentos',
            'categorias' => $categorias
        ];

        $contenido = $this->renderCategoriasContent($datos);
        $this->renderLayout($contenido, $datos);
    }

    // Crear categoría - Solo admin
    public function crear_categoria() {
        if ($_SESSION['user_rol'] != 'admin') {
            die('Acceso denegado.');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = trim($_POST['nombre']);
            $descripcion = trim($_POST['descripcion'] ?? '');

            if (empty($nombre)) {
                echo "<script>alert('El nombre de la categoría es obligatorio'); window.history.back();</script>";
                return;
            }

            $categoriaModelo = new Categoria();
            
            // Verificar si ya existe
            if ($categoriaModelo->existe($nombre)) {
                echo "<script>alert('Error: Ya existe una categoría con ese nombre'); window.history.back();</script>";
                return;
            }

            if ($categoriaModelo->crear($nombre, $descripcion)) {
                $this->logModelo->registrar($_SESSION['user_id'], "Categoría creada: $nombre");
                echo "<script>alert('Categoría creada exitosamente'); window.location.href='" . BASE_URL . "/hospital/categorias';</script>";
            } else {
                echo "<script>alert('Error al crear la categoría'); window.history.back();</script>";
            }
        } else {
            // Mostrar formulario
            $datos = [
                'titulo' => 'Crear Categoría',
                'titulo_pagina' => 'Crear Nueva Categoría',
                'seccion' => 'medicamentos'
            ];

            $contenido = $this->renderCrearCategoriaContent($datos);
            $this->renderLayout($contenido, $datos);
        }
    }

    // Editar categoría - Solo admin
    public function editar_categoria($id) {
        if ($_SESSION['user_rol'] != 'admin') {
            die('Acceso denegado.');
        }

        $categoriaModelo = new Categoria();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = trim($_POST['nombre']);
            $descripcion = trim($_POST['descripcion'] ?? '');

            if (empty($nombre)) {
                echo "<script>alert('El nombre de la categoría es obligatorio'); window.history.back();</script>";
                return;
            }

            // Verificar si ya existe (excluyendo la actual)
            if ($categoriaModelo->existe($nombre, $id)) {
                echo "<script>alert('Error: Ya existe otra categoría con ese nombre'); window.history.back();</script>";
                return;
            }

            if ($categoriaModelo->actualizar($id, $nombre, $descripcion)) {
                $this->logModelo->registrar($_SESSION['user_id'], "Categoría actualizada: $nombre (ID: $id)");
                echo "<script>alert('Categoría actualizada exitosamente'); window.location.href='" . BASE_URL . "/hospital/categorias';</script>";
            } else {
                echo "<script>alert('Error al actualizar la categoría'); window.history.back();</script>";
            }
        } else {
            // Mostrar formulario con datos
            $categoria = $categoriaModelo->obtenerPorId($id);
            if (!$categoria) {
                echo "<script>alert('Categoría no encontrada'); window.location.href='" . BASE_URL . "/hospital/categorias';</script>";
                return;
            }

            $datos = [
                'titulo' => 'Editar Categoría',
                'titulo_pagina' => 'Editar Categoría',
                'seccion' => 'medicamentos',
                'categoria' => $categoria
            ];

            $contenido = $this->renderEditarCategoriaContent($datos);
            $this->renderLayout($contenido, $datos);
        }
    }

    // Eliminar categoría - Solo admin
    public function eliminar_categoria($id) {
        if ($_SESSION['user_rol'] != 'admin') {
            die('Acceso denegado.');
        }

        $categoriaModelo = new Categoria();
        $categoria = $categoriaModelo->obtenerPorId($id);
        
        if (!$categoria) {
            echo "<script>alert('Categoría no encontrada'); window.location.href='" . BASE_URL . "/hospital/categorias';</script>";
            return;
        }

        $resultado = $categoriaModelo->eliminar($id);
        
        if ($resultado === true) {
            $this->logModelo->registrar($_SESSION['user_id'], "Categoría eliminada: {$categoria->nombre} (ID: $id)");
            echo "<script>alert('Categoría eliminada exitosamente'); window.location.href='" . BASE_URL . "/hospital/categorias';</script>";
        } else if (is_array($resultado) && isset($resultado['error'])) {
            echo "<script>alert('{$resultado['error']}'); window.location.href='" . BASE_URL . "/hospital/categorias';</script>";
        } else {
            echo "<script>alert('Error al eliminar la categoría'); window.location.href='" . BASE_URL . "/hospital/categorias';</script>";
        }
    }

    // Renderizar contenido de categorías
    private function renderCategoriasContent($datos) {
        ob_start();
        require_once '../app/views/hospital/categorias.php';
        return ob_get_clean();
    }

    // Renderizar contenido de crear categoría
    private function renderCrearCategoriaContent($datos) {
        ob_start();
        require_once '../app/views/hospital/crear_categoria.php';
        return ob_get_clean();
    }

    // Renderizar contenido de editar categoría
    private function renderEditarCategoriaContent($datos) {
        ob_start();
        require_once '../app/views/hospital/editar_categoria.php';
        return ob_get_clean();
    }

    // Crear medicamento - Solo admin
    public function crear_medicamento() {
        if ($_SESSION['user_rol'] != 'admin') {
            die('Acceso denegado. Solo administradores pueden crear medicamentos.');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validar datos
            $nombre = trim($_POST['nombre']);
            $descripcion = trim($_POST['descripcion']);
            $categoria_id = intval($_POST['categoria_id']);
            $presentacion = trim($_POST['presentacion']);
            $precio = floatval($_POST['precio']);
            $stock = intval($_POST['stock']);

            // Validaciones básicas
            if (empty($nombre) || empty($descripcion) || empty($presentacion) || $categoria_id <= 0 || $precio <= 0 || $stock < 0) {
                echo "<script>alert('Todos los campos son obligatorios y deben tener valores válidos'); window.history.back();</script>";
                return;
            }

            // Preparar datos
            $datos = [
                'categoria_id' => $categoria_id,
                'nombre' => $nombre,
                'descripcion' => $descripcion,
                'presentacion' => $presentacion,
                'precio' => $precio,
                'stock' => $stock
            ];

            // Intentar crear medicamento
            if ($this->medicamentoModelo->agregar($datos)) {
                $this->logModelo->registrar($_SESSION['user_id'], "Medicamento creado: $nombre (Stock: $stock)");
                echo "<script>alert('Medicamento creado exitosamente'); window.location.href='" . BASE_URL . "/hospital/medicamentos';</script>";
            } else {
                echo "<script>alert('Error al crear el medicamento'); window.history.back();</script>";
            }
        } else {
            // Mostrar formulario
            $categorias = $this->categoriaModelo->obtenerTodas();
            
            $datos = [
                'titulo' => 'Crear Medicamento',
                'titulo_pagina' => 'Crear Nuevo Medicamento',
                'seccion' => 'medicamentos',
                'categorias' => $categorias
            ];

            $contenido = $this->renderCrearMedicamentoContent($datos);
            $this->renderLayout($contenido, $datos);
        }
    }

    // Editar medicamento - Solo admin
    public function editar_medicamento($id) {
        if ($_SESSION['user_rol'] != 'admin') {
            die('Acceso denegado. Solo administradores pueden editar medicamentos.');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validar datos
            $nombre = trim($_POST['nombre']);
            $descripcion = trim($_POST['descripcion']);
            $categoria_id = intval($_POST['categoria_id']);
            $presentacion = trim($_POST['presentacion']);
            $precio = floatval($_POST['precio']);
            $stock = intval($_POST['stock']);
            $estado = intval($_POST['estado']);

            // Validaciones básicas
            if (empty($nombre) || empty($descripcion) || empty($presentacion) || $categoria_id <= 0 || $precio <= 0 || $stock < 0) {
                echo "<script>alert('Todos los campos son obligatorios y deben tener valores válidos'); window.history.back();</script>";
                return;
            }

            // Actualizar medicamento (necesitamos crear este método en el modelo)
            $resultado = $this->actualizarMedicamento($id, [
                'categoria_id' => $categoria_id,
                'nombre' => $nombre,
                'descripcion' => $descripcion,
                'presentacion' => $presentacion,
                'precio' => $precio,
                'stock' => $stock,
                'estado' => $estado
            ]);

            if ($resultado) {
                $this->logModelo->registrar($_SESSION['user_id'], "Medicamento actualizado: $nombre (ID: $id)");
                echo "<script>alert('Medicamento actualizado exitosamente'); window.location.href='" . BASE_URL . "/hospital/medicamentos';</script>";
            } else {
                echo "<script>alert('Error al actualizar el medicamento'); window.history.back();</script>";
            }
        } else {
            // Mostrar formulario con datos del medicamento
            $medicamento = $this->medicamentoModelo->obtenerPorId($id);
            if (!$medicamento) {
                echo "<script>alert('Medicamento no encontrado'); window.location.href='" . BASE_URL . "/hospital/medicamentos';</script>";
                return;
            }

            $categorias = $this->categoriaModelo->obtenerTodas();
            
            $datos = [
                'titulo' => 'Editar Medicamento',
                'titulo_pagina' => 'Editar Medicamento',
                'seccion' => 'medicamentos',
                'medicamento' => $medicamento,
                'categorias' => $categorias
            ];

            $contenido = $this->renderEditarMedicamentoContent($datos);
            $this->renderLayout($contenido, $datos);
        }
    }

    // Método auxiliar para actualizar medicamento
    private function actualizarMedicamento($id, $datos) {
        $db = new Database();
        $db->query('UPDATE medicamentos SET categoria_id = :categoria_id, nombre = :nombre, descripcion = :descripcion, 
                    presentacion = :presentacion, precio = :precio, stock = :stock, estado = :estado WHERE id = :id');
        
        $db->bind(':id', $id);
        $db->bind(':categoria_id', $datos['categoria_id']);
        $db->bind(':nombre', $datos['nombre']);
        $db->bind(':descripcion', $datos['descripcion']);
        $db->bind(':presentacion', $datos['presentacion']);
        $db->bind(':precio', $datos['precio']);
        $db->bind(':stock', $datos['stock']);
        $db->bind(':estado', $datos['estado']);
        
        return $db->execute();
    }

    // Renderizar contenido de crear medicamento
    private function renderCrearMedicamentoContent($datos) {
        ob_start();
        require_once '../app/views/hospital/crear_medicamento.php';
        return ob_get_clean();
    }

    // Renderizar contenido de editar medicamento
    private function renderEditarMedicamentoContent($datos) {
        ob_start();
        require_once '../app/views/hospital/editar_medicamento.php';
        return ob_get_clean();
    }

    // Eliminar medicamento - Solo admin
    public function eliminar_medicamento($id) {
        if ($_SESSION['user_rol'] != 'admin') {
            die('Acceso denegado. Solo administradores pueden eliminar medicamentos.');
        }

        $medicamento = $this->medicamentoModelo->obtenerPorId($id);
        
        if (!$medicamento) {
            echo "<script>alert('Medicamento no encontrado'); window.location.href='" . BASE_URL . "/hospital/medicamentos';</script>";
            return;
        }

        // Verificar si hay pedidos pendientes con este medicamento
        $db = new Database();
        $db->query("SELECT COUNT(*) as total FROM pedido_detalle pd 
                    INNER JOIN pedidos p ON pd.pedido_id = p.id 
                    WHERE pd.medicamento_id = :id AND p.estado IN ('pendiente', 'en_proceso')");
        $db->bind(':id', $id);
        $result = $db->register();

        if ($result->total > 0) {
            echo "<script>alert('No se puede eliminar el medicamento porque tiene pedidos pendientes asociados.'); window.location.href='" . BASE_URL . "/hospital/medicamentos';</script>";
            return;
        }

        // Eliminar medicamento
        $db->query("DELETE FROM medicamentos WHERE id = :id");
        $db->bind(':id', $id);
        
        if ($db->execute()) {
            $this->logModelo->registrar($_SESSION['user_id'], "Medicamento eliminado: {$medicamento->nombre} (ID: $id)");
            echo "<script>alert('Medicamento eliminado exitosamente'); window.location.href='" . BASE_URL . "/hospital/medicamentos';</script>";
        } else {
            echo "<script>alert('Error al eliminar el medicamento'); window.location.href='" . BASE_URL . "/hospital/medicamentos';</script>";
        }
    }
}
?>