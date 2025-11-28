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
    $db->query('INSERT INTO usuarios (nombre, email, password, rol, creado_en) VALUES (:nombre, :email, :password, :rol, NOW())');
    $db->bind(':nombre', 'Dr. María Fernández');
    $db->bind(':email', 'validador@hospital.com');
    $db->bind(':password', $password_hash);
    $db->bind(':rol', 'validador');
    
    if ($db->execute()) {
        echo "   ✓ Validador creado exitosamente\n";
    } else {
        echo "   ✗ Error al crear validador\n";
    }
    
    // FARMACÉUTICO
    echo "2. Creando Farmacéutico...\n";
    $db->query('INSERT INTO usuarios (nombre, email, password, rol, creado_en) VALUES (:nombre, :email, :password, :rol, NOW())');
    $db->bind(':nombre', 'Farm. Carlos Ruiz');
    $db->bind(':email', 'farmaceutico@hospital.com');
    $db->bind(':password', $password_hash);
    $db->bind(':rol', 'farmaceutico');
    
    if ($db->execute()) {
        echo "   ✓ Farmacéutico creado exitosamente\n";
    } else {
        echo "   ✗ Error al crear farmacéutico\n";
    }
    
    // ADMINISTRADOR
    echo "3. Creando Administrador...\n";
    $db->query('INSERT INTO usuarios (nombre, email, password, rol, creado_en) VALUES (:nombre, :email, :password, :rol, NOW())');
    $db->bind(':nombre', 'Administrador Sistema');
    $db->bind(':email', 'admin@hospital.com');
    $db->bind(':password', $password_hash);
    $db->bind(':rol', 'admin');
    
    if ($db->execute()) {
        echo "   ✓ Administrador creado exitosamente\n";
    } else {
        echo "   ✗ Error al crear administrador\n";
    }
    
    echo "\n=== USUARIOS CREADOS ===\n";
    echo "VALIDADOR     - Email: validador@hospital.com    - Pass: $password_comun\n";
    echo "FARMACÉUTICO  - Email: farmaceutico@hospital.com - Pass: $password_comun\n";
    echo "ADMINISTRADOR - Email: admin@hospital.com        - Pass: $password_comun\n";
    echo "\n=== VERIFICACIÓN ===\n";
    
    // Verificar usuarios creados
    $db->query("SELECT id, nombre, email, rol, creado_en FROM usuarios WHERE rol IN ('validador', 'farmaceutico', 'admin') ORDER BY id");
    $usuarios = $db->registers();
    
    foreach ($usuarios as $usuario) {
        echo "ID: {$usuario->id} | {$usuario->nombre} | {$usuario->email} | {$usuario->rol} | {$usuario->creado_en}\n";
    }
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}