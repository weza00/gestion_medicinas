<?php
// Iniciar sesión PHP
session_start();

// Cargar configuración
require_once '../config/config.php';

// Cargar librerías base (Autoload manual simple)
require_once '../app/core/Database.php';
require_once '../app/core/Controller.php';
require_once '../app/core/App.php';

// Iniciar la App
$iniciar = new App();
?>