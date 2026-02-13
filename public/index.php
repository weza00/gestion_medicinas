<?php
// Iniciar sesión PHP (se configurará en config.php)
// session_start(); // Movido a config.php para usar configuración personalizada

// Cargar configuración (incluye variables de entorno)
require_once '../config/config.php';

// Iniciar sesión después de cargar configuración
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cargar librerías base (Autoload manual simple)
require_once '../app/core/Database.php';
require_once '../app/core/Controller.php';
require_once '../app/core/UrlHelper.php';  // Helper para URLs
require_once '../app/core/App.php';

// Iniciar la App
$iniciar = new App();
?>