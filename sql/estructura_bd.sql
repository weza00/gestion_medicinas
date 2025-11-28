-- ------------------------------------------------------------------
-- Script de creación de la base de datos "gestion_medicinas"
-- Generado a partir de: documentacion_sistema_medicamentos.md
-- Fecha: 2025-11-24
-- ------------------------------------------------------------------

DROP DATABASE IF EXISTS `gestion_medicinas`;
CREATE DATABASE IF NOT EXISTS `gestion_medicinas`
-- Crear base de datos y usarla
	DEFAULT CHARACTER SET = utf8mb4
	DEFAULT COLLATE = utf8mb4_unicode_ci;
USE `gestion_medicinas`;

-- -----------------------------------------------------
-- Tabla: usuarios
-- -----------------------------------------------------
CREATE TABLE `usuarios` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`nombre` VARCHAR(100) NOT NULL,
	`email` VARCHAR(150) NOT NULL,
	`password` VARCHAR(255) NOT NULL,
	`rol` ENUM('paciente','validador','farmaceutico','admin') NOT NULL DEFAULT 'paciente',
	`creado_en` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	UNIQUE KEY `uk_usuarios_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Tabla: categorias
-- -----------------------------------------------------
CREATE TABLE `categorias` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`nombre` VARCHAR(100) NOT NULL,
	`descripcion` TEXT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `uk_categorias_nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Tabla: medicamentos
-- -----------------------------------------------------
CREATE TABLE `medicamentos` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`categoria_id` INT UNSIGNED NOT NULL,
	`nombre` VARCHAR(150) NOT NULL,
	`descripcion` TEXT NULL,
	`presentacion` VARCHAR(100) NULL,
	`precio` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
	`stock` INT NOT NULL DEFAULT 0,
	`estado` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1=activo,0=inactivo',
	PRIMARY KEY (`id`),
	KEY `fk_medicamentos_categoria_idx` (`categoria_id`),
	CONSTRAINT `fk_medicamentos_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Tabla: pedidos
-- -----------------------------------------------------
CREATE TABLE `pedidos` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`usuario_id` INT UNSIGNED NOT NULL,
	`receta_archivo` VARCHAR(255) NULL COMMENT 'ruta o nombre del archivo subido (PDF/imagen)',
	`estado` ENUM('pendiente','aprobado','rechazado','entregado') NOT NULL DEFAULT 'pendiente',
	`codigo_retiro` VARCHAR(20) NULL,
	`creado_en` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	KEY `fk_pedidos_usuario_idx` (`usuario_id`),
	CONSTRAINT `fk_pedidos_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Tabla: pedido_detalle
-- -----------------------------------------------------
CREATE TABLE `pedido_detalle` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`pedido_id` INT UNSIGNED NOT NULL,
	`medicamento_id` INT UNSIGNED NOT NULL,
	`cantidad` INT NOT NULL DEFAULT 1,
	PRIMARY KEY (`id`),
	KEY `fk_pedido_detalle_pedido_idx` (`pedido_id`),
	KEY `fk_pedido_detalle_medicamento_idx` (`medicamento_id`),
	CONSTRAINT `fk_pedido_detalle_pedido` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT `fk_pedido_detalle_medicamento` FOREIGN KEY (`medicamento_id`) REFERENCES `medicamentos` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Tabla: logs
-- -----------------------------------------------------
CREATE TABLE `logs` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`usuario_id` INT UNSIGNED NOT NULL,
	`accion` VARCHAR(255) NOT NULL,
	`fecha` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`referencia_id` INT NULL,
	PRIMARY KEY (`id`),
	KEY `fk_logs_usuario_idx` (`usuario_id`),
	CONSTRAINT `fk_logs_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Índices y optimizaciones adicionales
-- -----------------------------------------------------
ALTER TABLE `medicamentos` ADD FULLTEXT KEY `ft_medicamentos_nombre_descripcion` (`nombre`, `descripcion`);

-- Fin del script

