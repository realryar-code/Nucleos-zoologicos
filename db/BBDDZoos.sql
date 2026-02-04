CREATE DATABASE IF NOT EXISTS db_zoologicos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_zoologicos;

CREATE TABLE IF NOT EXISTS `nucleos_zoologicos` (
    `id_zoologico` VARCHAR(255) NOT NULL PRIMARY KEY,
    `fecha_alta` VARCHAR(255) NOT NULL,
    `titular` VARCHAR(255),
    `municipio` VARCHAR(255),
    `provincia` VARCHAR(255),
    `imagen` VARCHAR(255)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `animales` (
    `id_animal` INT AUTO_INCREMENT PRIMARY KEY,
    `id_zoologico` VARCHAR(255) NOT NULL,
    `nombre` VARCHAR(255) NOT NULL,
    CONSTRAINT `fk_animal_nucleo` FOREIGN KEY (`id_zoologico`) 
        REFERENCES `nucleos_zoologicos`(`id_zoologico`) 
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `usuarios` (
    `nombre` VARCHAR(255) NOT NULL,
    `apellidos` VARCHAR(255) NOT NULL,
    `correo` VARCHAR(255) NOT NULL PRIMARY KEY,
    `contrasena` VARCHAR(255) NOT NULL,
    `fotoPerfil` VARCHAR(255) NOT NULL,
    `administrador` BOOLEAN DEFAULT FALSE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `resenas` (
    `correo` VARCHAR(255) NOT NULL,
    `id_zoologico` VARCHAR(255) NOT NULL,
    `titulo` VARCHAR(255) NOT NULL,
    `comentario` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`correo`, `id_zoologico`),
    CONSTRAINT `fk_resena_nucleo` FOREIGN KEY (`id_zoologico`) 
        REFERENCES `nucleos_zoologicos`(`id_zoologico`) 
        ON DELETE CASCADE,
    CONSTRAINT `fk_resena_usuario` FOREIGN KEY (`correo`) 
        REFERENCES `usuarios`(`correo`) 
        ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO `usuarios` (`nombre`, `apellidos`, `correo`, `contrasena`, `fotoPerfil`, `administrador`) 
VALUES ('Admin', 'Sistema', 'admin@zoologico.com', MD5('admin123'), 'Oso.png', 1);
