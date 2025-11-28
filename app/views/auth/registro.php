<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro Paciente</title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; padding-top: 50px; }
        form { border: 1px solid #ccc; padding: 20px; border-radius: 5px; width: 300px; }
        input { width: 100%; margin-bottom: 10px; padding: 8px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #28a745; color: white; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <form action="<?php echo BASE_URL; ?>/auth/registro" method="POST">
        <h2>Registro de Paciente</h2>
        
        <label>Nombre Completo</label>
        <input type="text" name="nombre" required>

        <label>Email</label>
        <input type="email" name="email" required>
        
        <label>Password</label>
        <input type="password" name="password" required>
        
        <button type="submit">Registrarse</button>
        <p>¿Ya tienes cuenta? <a href="<?php echo BASE_URL; ?>/auth/login">Ingresa aquí</a></p>
    </form>
</body>
</html>