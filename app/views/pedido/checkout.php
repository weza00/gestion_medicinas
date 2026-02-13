<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Finalizar Pedido</title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; padding-top: 50px; background-color: #f9f9f9; }
        .box { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); width: 400px; text-align: center; }
        input[type="file"] { margin: 20px 0; }
        .btn { background: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 1.1em; }
        .alert { background: #fff3cd; color: #856404; padding: 10px; border-radius: 5px; font-size: 0.9em; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="box">
        <h2>Confirmar Pedido</h2>
        <div class="alert">
            ⚠ Para dispensar estos medicamentos, es obligatorio adjuntar su receta médica válida.
        </div>

        <form action="<?php echo url('pedido/procesar'); ?>" method="POST" enctype="multipart/form-data">
            
            <label>Subir Receta (PDF o Imagen):</label><br>
            <input type="file" name="receta" accept=".pdf, .jpg, .jpeg, .png" required>
            <br>
            
            <p>Al hacer clic en finalizar, su pedido quedará en estado <strong>PENDIENTE</strong> hasta que un validador lo revise.</p>
            
            <button type="submit" class="btn">Finalizar y Enviar Pedido</button>
        </form>
        <br>
        <a href="<?php echo url('carrito'); ?>">Volver al carrito</a>
    </div>
</body>
</html>