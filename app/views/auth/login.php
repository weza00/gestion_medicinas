<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; padding-top: 50px; }
        form { border: 1px solid #ccc; padding: 20px; border-radius: 5px; width: 300px; }
        input { width: 100%; margin-bottom: 10px; padding: 8px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #007bff; color: white; border: none; cursor: pointer; }
        .error { color: red; font-size: 0.9em; }
    </style>
</head>
<body>
    <form action="<?php echo BASE_URL; ?>/auth/login" method="POST">
        <h2>Iniciar Sesión</h2>
        <?php if(isset($data['error']) && !empty($data['error'])): ?>
            <p class="error"><?php echo $data['error']; ?></p>
        <?php endif; ?>
        
        <label>Email</label>
        <input type="email" name="email" required>
        
        <label>Password</label>
        <input type="password" name="password" required>
        
        <button type="submit">Ingresar</button>
        <p>¿No tienes cuenta? <a href="<?php echo BASE_URL; ?>/auth/registro">Regístrate aquí</a></p>
    </form>
</body>
</html>