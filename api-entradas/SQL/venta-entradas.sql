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
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    categoria VARCHAR(50) DEFAULT 'concierto'
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

ALTER TABLE usuarios ADD COLUMN rol ENUM('admin', 'cliente') DEFAULT 'cliente';
UPDATE usuarios SET rol = 'admin' WHERE id = 1;
ALTER TABLE eventos ADD COLUMN imagen VARCHAR(255) NULL;


INSERT INTO eventos 
(nombre, descripcion, ubicacion, fecha, entradas_totales, entradas_disponibles, categoria) 
VALUES
-- 🎵 Concierto normal
('EVERYONE`S A STAR - 5SOS - TOUR', 
 '5 Seconds of Summer volverán a España en 2026 con dos únicos conciertos: el 30 de abril en el Palacio Vistalegre de Madrid', 
 'Madrid', 
 '2024-12-01 20:00:00', 
 155, 155, 
 'concierto'),

-- 🎸 Evento destacado
('AC/DC / METALLICA', 
 'Concierto conjunto de las legendarias bandas de rock AC/DC y Metallica.', 
 'Moscow', 
 '2024-11-15 18:00:00', 
 155, 155, 
 'destacado'),

-- 🌍 Concierto normal
('COLDPLAY', 
 'Coldplay regresa a España en 2025 con su gira mundial "Music of the Spheres".', 
 'Barcelona', 
 '2025-06-20 21:00:00', 
 155, 155, 
 'concierto'),

-- 🎬 Evento de cine
('Estreno: Dune Parte 3', 
 'La esperada continuación de la saga de ciencia ficción llega a los cines en 2026.', 
 'Madrid', 
 '2026-02-10 19:00:00', 
 155, 155, 
 'cine'),

-- ⭐ Evento popular
('Festival Primavera Sound', 
 'Uno de los festivales más importantes de Europa, con artistas internacionales.', 
 'Barcelona', 
 '2026-05-30 17:00:00', 
 155, 155, 
 'popular'),

-- 🎬 Otro evento de cine
('People We Meet on Vacation', 
 'Una comedia romántica que sigue a dos amigos en sus vacaciones por Europa.', 
 'Mallorca', 
 '2026-08-12 20:30:00', 
 155, 155, 
 'cine');


