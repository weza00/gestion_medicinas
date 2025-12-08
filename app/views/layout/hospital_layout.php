<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($data['titulo']) ? $data['titulo'] : 'Panel Hospitalario'; ?> - MediPlus</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/style.css">
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        /* Estilos para el layout hospitalario con sidebar */
        .hospital-layout {
            display: flex;
            min-height: 100vh;
            background: #f8f9fa;
        }

        .sidebar {
            width: 250px;
            background: linear-gradient(135deg, var(--text-dark) 0%, #1a1c33 100%);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid #333;
            text-align: center;
        }

        .sidebar-header h2 {
            color: white;
            margin: 0;
            font-size: 1.4rem;
        }

        .sidebar-header .user-info {
            margin-top: 10px;
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .sidebar-nav {
            padding: 20px 0;
        }

        .nav-item {
            margin: 5px 0;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }

        .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: white;
            border-left-color: var(--primary);
        }

        .nav-link.active {
            background: rgba(0,180,216,0.2);
            color: white;
            border-left-color: var(--primary);
        }

        .nav-icon {
            margin-right: 12px;
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }

        .main-content {
            margin-left: 250px;
            flex: 1;
            background: white;
            min-height: 100vh;
        }

        .top-bar {
            background: white;
            padding: 15px 30px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .content-area {
            padding: 30px;
        }

        .page-title {
            color: var(--text-dark);
            margin-bottom: 20px;
            font-size: 2rem;
            font-weight: 600;
        }

        .logout-btn {
            background: var(--danger);
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s;
        }

        .logout-btn:hover {
            background: #c82333;
        }

        /* Role badges */
        .role-badge {
            background: var(--primary);
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: bold;
            text-transform: uppercase;
        }

        .role-admin { background: #6f42c1; }
        .role-validador { background: #28a745; }
        .role-farmaceutico { background: #17a2b8; }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-250px);
                transition: transform 0.3s;
            }
            
            .sidebar.open {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <div class="hospital-layout">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-header">
                <h2>MediPlus</h2>
                <div class="user-info">
                    <div><?php echo $_SESSION['user_nombre']; ?></div>
                    <span class="role-badge role-<?php echo $_SESSION['user_rol']; ?>">
                        <?php echo ucfirst($_SESSION['user_rol']); ?>
                    </span>
                </div>
            </div>

            <div class="sidebar-nav">
                <!-- Inicio - Visible para todos -->
                <div class="nav-item">
                    <a href="<?php echo BASE_URL; ?>/hospital/inicio" class="nav-link <?php echo (isset($data['seccion']) && $data['seccion'] == 'inicio') ? 'active' : ''; ?>">
                        <i class="material-icons nav-icon">dashboard</i>
                        Inicio
                    </a>
                </div>

                <!-- Validar Pedido - Solo validador -->
                <?php if($_SESSION['user_rol'] == 'validador'): ?>
                <div class="nav-item">
                    <a href="<?php echo BASE_URL; ?>/hospital/validar" class="nav-link <?php echo (isset($data['seccion']) && $data['seccion'] == 'validar') ? 'active' : ''; ?>">
                        <i class="material-icons nav-icon">verified_user</i>
                        Validar Pedidos
                    </a>
                </div>
                <?php endif; ?>

                <!-- Entregar Pedido - Solo farmacéutico -->
                <?php if($_SESSION['user_rol'] == 'farmaceutico'): ?>
                <div class="nav-item">
                    <a href="<?php echo BASE_URL; ?>/hospital/entregar" class="nav-link <?php echo (isset($data['seccion']) && $data['seccion'] == 'entregar') ? 'active' : ''; ?>">
                        <i class="material-icons nav-icon">local_shipping</i>
                        Entregar Pedidos
                    </a>
                </div>
                <?php endif; ?>

                <!-- Medicamentos - Solo admin -->
                <?php if($_SESSION['user_rol'] == 'admin'): ?>
                <div class="nav-item">
                    <a href="<?php echo BASE_URL; ?>/hospital/medicamentos" class="nav-link <?php echo (isset($data['seccion']) && $data['seccion'] == 'medicamentos') ? 'active' : ''; ?>">
                        <i class="material-icons nav-icon">medication</i>
                        Medicamentos
                    </a>
                </div>

                <div class="nav-item">
                    <a href="<?php echo BASE_URL; ?>/hospital/categorias" class="nav-link <?php echo (isset($data['seccion']) && $data['seccion'] == 'categorias') ? 'active' : ''; ?>">
                        <i class="material-icons nav-icon">category</i>
                        Categorías
                    </a>
                </div>

                <!-- Usuarios - Solo admin -->
                <div class="nav-item">
                    <a href="<?php echo BASE_URL; ?>/hospital/usuarios" class="nav-link <?php echo (isset($data['seccion']) && $data['seccion'] == 'usuarios') ? 'active' : ''; ?>">
                        <i class="material-icons nav-icon">group</i>
                        Usuarios
                    </a>
                </div>

                <!-- Registro/Logs - Solo admin -->
                <div class="nav-item">
                    <a href="<?php echo BASE_URL; ?>/hospital/logs" class="nav-link <?php echo (isset($data['seccion']) && $data['seccion'] == 'logs') ? 'active' : ''; ?>">
                        <i class="material-icons nav-icon">description</i>
                        Registro del Sistema
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Bar -->
            <div class="top-bar">
                <div>
                    <h1 class="page-title" style="margin: 0; font-size: 1.5rem;">
                        <?php echo isset($data['titulo_pagina']) ? $data['titulo_pagina'] : 'Panel Hospitalario'; ?>
                    </h1>
                </div>
                <div>
                    <a href="<?php echo BASE_URL; ?>/home" style="color: var(--text-light); margin-right: 20px;">
                        <i class="material-icons" style="vertical-align: middle; margin-right: 5px;">public</i>Ver Sitio Público
                    </a>
                    <a href="<?php echo BASE_URL; ?>/auth/logout" class="logout-btn">
                        <i class="material-icons" style="vertical-align: middle; margin-right: 5px;">logout</i>Cerrar Sesión
                    </a>
                </div>
            </div>

            <!-- Content Area -->
            <div class="content-area">
                <?php echo $contenido; ?>
            </div>
        </div>
    </div>
</body>
</html>