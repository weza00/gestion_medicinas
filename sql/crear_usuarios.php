<?php
// Script para crear usuarios del sistema con hashes correctos
// Ejecutar desde línea de comandos: php crear_usuarios.php

// Cargar configuración
require_once '../config/config.php';
require_once '../app/core/Database.php';

try {
    $db = new Database();
    
    // Contraseña común para todos (cámbiala por una más segura en producción)
    $password_comun = "123456";
    $password_hash = password_hash($password_comun, PASSWORD_DEFAULT);
    
    echo "=== CREANDO USUARIOS DEL SISTEMA ===\n";
    echo "Contraseña para todos: $password_comun\n";
    echo "Hash generado: $password_hash\n\n";
    
    // VALIDADOR
    echo "1. Creando Validador...\n";
    $db->query('INSERT INTO usuarios (nombre, dni, email, password, rol, estado, creado_en) VALUES (:nombre, :dni, :email, :password, :rol, :estado, NOW())');
    $db->bind(':nombre', 'Dr. María Fernández');
    $db->bind(':dni', '1712345678'); // Cédula ecuatoriana válida
    $db->bind(':email', 'validador@hospital.com');
    $db->bind(':password', $password_hash);
    $db->bind(':rol', 'validador');
    $db->bind(':estado', 1); // Activo
    
    if ($db->execute()) {
        echo "   [OK] Validador creado exitosamente\n";
    } else {
        echo "   [ERROR] Error al crear validador\n";
    }
    
    // FARMACÉUTICO
    echo "2. Creando Farmacéutico...\n";
    $db->query('INSERT INTO usuarios (nombre, dni, email, password, rol, estado, creado_en) VALUES (:nombre, :dni, :email, :password, :rol, :estado, NOW())');
    $db->bind(':nombre', 'Farm. Carlos Ruiz');
    $db->bind(':dni', '0912345679'); // Cédula ecuatoriana válida
    $db->bind(':email', 'farmaceutico@hospital.com');
    $db->bind(':password', $password_hash);
    $db->bind(':rol', 'farmaceutico');
    $db->bind(':estado', 1); // Activo
    
    if ($db->execute()) {
        echo "   [OK] Farmacéutico creado exitosamente\n";
    } else {
        echo "   [ERROR] Error al crear farmacéutico\n";
    }
    
    // ADMINISTRADOR
    echo "3. Creando Administrador...\n";
    $db->query('INSERT INTO usuarios (nombre, dni, email, password, rol, estado, creado_en) VALUES (:nombre, :dni, :email, :password, :rol, :estado, NOW())');
    $db->bind(':nombre', 'Administrador Sistema');
    $db->bind(':dni', '1712345680'); // Cédula ecuatoriana válida
    $db->bind(':email', 'admin@hospital.com');
    $db->bind(':password', $password_hash);
    $db->bind(':rol', 'admin');
    $db->bind(':estado', 1); // Activo
    $db->bind(':nombre', 'Administrador Sistema');
    $db->bind(':email', 'admin@hospital.com');
    $db->bind(':password', $password_hash);
    $db->bind(':rol', 'admin');
    
    if ($db->execute()) {
        echo "   [OK] Administrador creado exitosamente\n";
    } else {
        echo "   [ERROR] Error al crear administrador\n";
    }
    
    echo "\n=== USUARIOS CREADOS ===\n";
    echo "VALIDADOR     - DNI: 1712345678 - Email: validador@hospital.com    - Pass: $password_comun\n";
    echo "FARMACÉUTICO  - DNI: 0912345679 - Email: farmaceutico@hospital.com - Pass: $password_comun\n";
    echo "ADMINISTRADOR - DNI: 1712345680 - Email: admin@hospital.com        - Pass: $password_comun\n";
    echo "\n=== NOTA IMPORTANTE ===\n";
    echo "El sistema ahora usa DNI como método principal de autenticación.\n";
    echo "Para iniciar sesión usar: DNI + Contraseña\n";
    echo "\n=== VERIFICACIÓN ===\n";
    
    // Verificar usuarios creados
    $db->query("SELECT id, nombre, dni, email, rol, estado, creado_en FROM usuarios WHERE rol IN ('validador', 'farmaceutico', 'admin') ORDER BY id");
    $usuarios = $db->registers();
    
    foreach ($usuarios as $usuario) {
        $estadoTexto = $usuario->estado ? 'Activo' : 'Inactivo';
        echo "ID: {$usuario->id} | {$usuario->nombre} | DNI: {$usuario->dni} | {$usuario->email} | {$usuario->rol} | Estado: $estadoTexto | {$usuario->creado_en}\n";
    }
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}