<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>MediPlus - Farmacia Hospitalaria</title>
    <link rel="stylesheet" href="<?php echo asset('css/style.css'); ?>">
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        /* Estilos específicos del Home */
        .hero {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 80px 20px;
            text-align: center;
            border-radius: 0 0 50% 50% / 20px; /* Curva sutil abajo */
        }
        .hero h1 { color: white; font-size: 3rem; margin-bottom: 20px; }
        .hero p { font-size: 1.2rem; opacity: 0.9; margin-bottom: 30px; }
        .features { display: flex; justify-content: center; gap: 30px; margin-top: -50px; padding: 0 20px; flex-wrap: wrap; }
        .feature-card { background: white; padding: 30px; border-radius: 15px; box-shadow: var(--shadow); width: 300px; text-align: center; }
        .icon { font-size: 2.5rem; margin-bottom: 15px; display: block; }
        
        /* Estilos para búsqueda y filtros */
        .search-section {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: var(--shadow);
            margin: 40px 0;
        }
        .search-filters {
            display: grid;
            grid-template-columns: 2fr 1fr auto;
            gap: 15px;
            align-items: end;
        }
        .search-input {
            padding: 12px;
            border: 2px solid #eee;
            border-radius: 8px;
            font-size: 1rem;
        }
        .search-input:focus {
            border-color: var(--primary);
            outline: none;
        }
        .filter-select {
            padding: 12px;
            border: 2px solid #eee;
            border-radius: 8px;
            font-size: 1rem;
        }
        .search-btn {
            padding: 12px 20px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }
        .search-btn:hover { background: var(--primary-dark); }
        
        /* Botones deshabilitados para usuarios no logueados */
        .btn-disabled {
            background: #ccc !important;
            color: #666 !important;
            cursor: not-allowed !important;
            pointer-events: none;
        }
        .login-notice {
            background: #fff3cd;
            color: #856404;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            margin: 20px 0;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="logo">Medi<span>Plus</span></div>
        <div class="nav-links">
            <?php if(isset($_SESSION['user_id'])): ?>
                <?php if($_SESSION['user_rol'] == 'paciente'): ?>
                    <a href="<?php echo url('pedido/mis_pedidos'); ?>"><i class="material-icons">receipt</i> Mis Pedidos</a>
                    <a href="<?php echo url('carrito'); ?>"><i class="material-icons">shopping_cart</i> Carrito <span style="background:var(--primary); color:white; padding:2px 6px; border-radius:10px; font-size:0.8em;"><?php echo isset($_SESSION['carrito']) ? array_sum($_SESSION['carrito']) : 0; ?></span></a>
                <?php else: ?>
                    <a href="<?php echo url('hospital/inicio'); ?>" class="btn-nav"><i class="material-icons">dashboard</i> Volver al Panel</a>
                <?php endif; ?>
                <a href="<?php echo url('auth/logout'); ?>" style="color: var(--danger);"><i class="material-icons">logout</i> Salir</a>
            <?php else: ?>
                <a href="<?php echo url('auth/login'); ?>" class="btn-nav"><i class="material-icons">login</i> Ingresar</a>
            <?php endif; ?>
        </div>
    </nav>

    <header class="hero">
        <h1>Tu salud es nuestra prioridad</h1>
        <p>Consulta nuestro catálogo de medicamentos disponibles en el hospital.</p>
    </header>

    <section class="features">
        <div class="feature-card">
            <i class="material-icons icon">medication</i>
            <h3>Catálogo Completo</h3>
            <p>Consulta todos los medicamentos disponibles en stock en tiempo real.</p>
        </div>
        <div class="feature-card">
            <i class="material-icons icon">receipt</i>
            <h3>Pedidos con Receta</h3>
            <p>Los pacientes autorizados pueden subir su receta médica para validación.</p>
        </div>
        <div class="feature-card">
            <i class="material-icons icon">local_hospital</i>
            <h3>Retiro Seguro</h3>
            <p>Sistema de códigos únicos para retiro controlado de medicamentos.</p>
        </div>
    </section>

    <!-- Sección de Catálogo -->
    <div class="container">
        <div style="text-align: center; padding: 40px 0;">
            <h2 style="color: var(--primary-dark); font-size: 2.5rem;">Catálogo de Medicamentos</h2>
            <p style="color: var(--text-light);">Explora nuestro inventario disponible</p>
        </div>

        <!-- Búsqueda y Filtros -->
        <div class="search-section">
            <h3 style="margin-top: 0; color: var(--primary-dark);">
                <i class="material-icons" style="vertical-align: middle; margin-right: 10px;">search</i>Buscar Medicamentos
            </h3>
            <div class="search-filters">
                <input type="text" id="searchInput" class="search-input" placeholder="Buscar por nombre de medicamento...">
                <select id="categoryFilter" class="filter-select">
                    <option value="">Todas las categorías</option>
                    <?php foreach($data['medicamentos_agrupados'] as $categoria => $medicinas): ?>
                        <option value="<?php echo $categoria; ?>"><?php echo $categoria; ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="button" class="search-btn" onclick="filtrarMedicamentos()">
                    <i class="material-icons" style="vertical-align: middle; margin-right: 5px;">search</i> Buscar
                </button>
            </div>
        </div>

        <?php if(!isset($_SESSION['user_id'])): ?>
            <div class="login-notice">
                <i class="material-icons" style="vertical-align: middle; margin-right: 5px;">warning</i>
                <strong>Aviso:</strong> Para realizar pedidos debe iniciar sesión con una cuenta autorizada por el hospital.
            </div>
        <?php endif; ?>

        <!-- Medicamentos por Categoría -->
        <?php foreach($data['medicamentos_agrupados'] as $categoria => $medicinas): ?>
            
            <h2 class="section-title" data-categoria="<?php echo $categoria; ?>"><?php echo $categoria; ?></h2>

            <div class="grid-medicamentos" data-categoria="<?php echo $categoria; ?>">
                <?php foreach($medicinas as $med): ?>
                    <?php if($med->estado == 1): ?>
                        <div class="card" data-nombre="<?php echo strtolower($med->nombre); ?>" data-categoria="<?php echo $categoria; ?>">
                            <div>
                                <span class="card-cat"><?php echo $categoria; ?></span>
                                <h3><?php echo $med->nombre; ?></h3>
                                <p><?php echo $med->descripcion; ?></p>
                                <small style="color: var(--text-light); display:block; margin-bottom:10px;">
                                    Presentación: <?php echo $med->presentacion; ?>
                                </small>
                                <small style="color: var(--text-light); display:block; margin-bottom:10px;">
                                    <?php if($med->stock > 0): ?>
                                        <span style="color: var(--success); font-weight: bold;">
                                            <i class="material-icons" style="font-size: 16px; vertical-align: middle;">check_circle</i>
                                            Disponible (<?php echo $med->stock; ?> unidades)
                                        </span>
                                    <?php else: ?>
                                        <span style="color: var(--danger); font-weight: bold;">
                                            <i class="material-icons" style="font-size: 16px; vertical-align: middle;">cancel</i>
                                            Sin stock
                                        </span>
                                    <?php endif; ?>
                                </small>
                            </div>
                            
                            <div class="card-footer">
                                <div class="price">$<?php echo $med->precio; ?></div>
                                
                                <?php if(isset($_SESSION['user_id']) && $_SESSION['user_rol'] == 'paciente' && $med->stock > 0): ?>
                                    <form action="<?php echo url('carrito/agregar'); ?>" method="POST">
                                        <input type="hidden" name="id" value="<?php echo $med->id; ?>">
                                        <button type="submit" class="btn-add">
                                            <i class="material-icons" style="font-size: 16px; vertical-align: middle;">add_shopping_cart</i>
                                            Agregar
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <button class="btn-add btn-disabled" title="<?php echo !isset($_SESSION['user_id']) ? 'Inicie sesión para comprar' : ($med->stock == 0 ? 'Sin stock disponible' : 'No autorizado'); ?>">
                                        <?php if($med->stock > 0): ?>
                                            <i class="material-icons" style="font-size: 16px; vertical-align: middle;">add_shopping_cart</i>
                                            Agregar
                                        <?php else: ?>
                                            <i class="material-icons" style="font-size: 16px; vertical-align: middle;">block</i>
                                            Sin Stock
                                        <?php endif; ?>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

        <?php endforeach; ?>
        
        <?php if(empty($data['medicamentos_agrupados'])): ?>
            <p style="text-align: center; margin-top: 50px;">No hay medicamentos disponibles en este momento.</p>
        <?php endif; ?>

        <br><br><br>
    </div>

    <script>
        function filtrarMedicamentos() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const categoryFilter = document.getElementById('categoryFilter').value;
            const cards = document.querySelectorAll('.card');
            const categoryTitles = document.querySelectorAll('.section-title');
            const categoryGrids = document.querySelectorAll('.grid-medicamentos');
            
            // Ocultar todas las secciones primero
            categoryTitles.forEach(title => title.style.display = 'none');
            categoryGrids.forEach(grid => grid.style.display = 'none');
            
            // Mostrar cards que coincidan con los filtros
            cards.forEach(card => {
                const nombre = card.dataset.nombre;
                const categoria = card.dataset.categoria;
                
                const matchesSearch = !searchTerm || nombre.includes(searchTerm);
                const matchesCategory = !categoryFilter || categoria === categoryFilter;
                
                if (matchesSearch && matchesCategory) {
                    card.style.display = 'block';
                    // Mostrar la sección padre
                    const parentGrid = card.closest('.grid-medicamentos');
                    const parentTitle = document.querySelector(`[data-categoria="${categoria}"].section-title`);
                    if (parentGrid) parentGrid.style.display = 'grid';
                    if (parentTitle) parentTitle.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Si no hay filtros, mostrar todo
            if (!searchTerm && !categoryFilter) {
                categoryTitles.forEach(title => title.style.display = 'block');
                categoryGrids.forEach(grid => grid.style.display = 'grid');
                cards.forEach(card => card.style.display = 'block');
            }
        }
        
        // Filtro en tiempo real mientras se escribe
        document.getElementById('searchInput').addEventListener('input', filtrarMedicamentos);
        document.getElementById('categoryFilter').addEventListener('change', filtrarMedicamentos);
    </script>

</body>
</html>