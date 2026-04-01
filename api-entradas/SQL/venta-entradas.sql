-- CREAR BASE DE DATOS
CREATE DATABASE IF NOT EXISTS venta_entradas CHARACTER SET utf8mb4;
USE venta_entradas;

-- CREAR TABLAS
-- CREAR TABLA DE USUARIOS
CREATE TABLE usuarios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    foto_perfil VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- CREAR TABLA DE EVENTOS
CREATE TABLE eventos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    descripcion TEXT,
    ubicacion VARCHAR(255),
    fecha DATETIME,
    entradas_totales INT UNSIGNED,
    entradas_disponibles INT UNSIGNED,
    imagen VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-- CREAR TABLA DE RESERVAS
CREATE TABLE reservas (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT UNSIGNED,
    evento_id INT UNSIGNED,
    cantidad INT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (evento_id) REFERENCES eventos(id) ON DELETE CASCADE
);
-- INSERTAR DATOS DE PRUEBA

INSERT INTO eventos (nombre, descripcion, ubicacion, fecha, entradas_totales, entradas_disponibles) VALUES
('EVERYONE`S A STAR - 5SOS - TOUR', '5 Seconds of Summer volverán a España en 2026 con dos únicos conciertos: el 30 de abril en el Palacio Vistalegre de Madrid', 'Madrid', '2024-12-01 20:00:00', 1000, 1000),
('AC/DC / METALLICA', 'Concierto conjunto de las legendarias bandas de rock AC/DC y Metallica.', 'Moscow', '2024-11-15 18:00:00', 500, 500),
('COLDPLAY', 'Coldplay regresa a España en 2025 con su gira mundial "Music of the Spheres".', 'Barcelona', '2025-06-20 21:00:00', 2000, 2000);


