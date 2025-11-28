<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['titulo']; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/style.css">
</head>
<body>
    <h1><?php echo $data['titulo']; ?></h1>
    <p>Si ves esto, la estructura MVC está funcionando correctamente.</p>
    
    <hr>
    <h3>Prueba de Base de Datos:</h3>
    <?php
        // Prueba rápida de conexión directamente en la vista (solo para debug)
        try {
            $db = new Database();
            echo "<p style='color:green'>Conexión a la BD exitosa.</p>";
        } catch (Exception $e) {
            echo "<p style='color:red'>Error: " . $e->getMessage() . "</p>";
        }
    ?>
</body>
</html>