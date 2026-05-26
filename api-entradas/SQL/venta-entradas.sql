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

 ('Pink Floyd | Wish You Were Here Experience',
 'Un espectáculo inmersivo con proyecciones, luces y una banda tributo de nivel mundial.',
 'Sevilla',
 '2025-12-12 20:00:00',
 300, 300,
 'destacado'
 ),


-- 🌍 CONCIERTOS (4)

('Fleetwood Mac | Rumours Live',
 'Un concierto tributo con los mejores éxitos de Fleetwood Mac interpretados por una banda internacional.',
 'Madrid',
 '2025-09-10 21:00:00',
 200, 200,
 'destacado'
),

 ('Radiohead | A Moon Shaped Pool Tour',
 'Radiohead regresa con un espectáculo envolvente lleno de visuales experimentales.',
 'Barcelona',
 '2025-10-22 20:30:00',
 180, 180,
 'destacado'
),

('COLDPLAY', 
 'Coldplay regresa a España en 2025 con su gira mundial "Music of the Spheres".', 
 'Barcelona', 
 '2025-06-20 21:00:00', 
 155, 155, 
 'destacado'),



  ('AC/DC / METALLICA', 
 'Concierto conjunto de las legendarias bandas de rock AC/DC y Metallica.', 
 'Moscow', 
 '2024-11-15 18:00:00', 
 155, 155, 
 'destacado'),

('The Strokes | Is This It Anniversary Tour',
 'La banda neoyorquina celebra el aniversario de su álbum más icónico.',
 'Valencia',
 '2025-11-05 21:00:00',
 220, 220,
 'destacado'
 ),

('King Gizzard & The Lizard Wizard | European Tour',
 'Una experiencia psicodélica única con múltiples estilos y energía inagotable.',
 'Bilbao',
 '2025-08-18 20:00:00',
 250, 250,
 'destacado'
),

-- 🎬 Evento de cine
('Estreno: Dune Parte 3', 
 'La esperada continuación de la saga de ciencia ficción llega a los cines en 2026.', 
 'Madrid', 
 '2026-02-10 19:00:00', 
 155, 155, 
 'cine'),

 ('Interstellar | Proyección Especial',
 'Proyección remasterizada del clásico de Christopher Nolan con coloquio posterior.',
 'Madrid',
 '2026-03-15 19:00:00',
 155, 155,
 'cine'
 ),

('Project Hail Mary | Preestreno',
 'Adaptación cinematográfica de la novela de Andy Weir, creador de The Martian.',
 'Barcelona',
 '2026-04-20 20:00:00',
 155, 155,
 'cine'
),

('The Martian 1 Reestreno IMAX',
 'Vuelve a los cines la aclamada película de ciencia ficción protagonizada por Matt Damon.',
 'Valencia',
 '2026-06-01 19:30:00',
 155, 155,
 'cine'
 ),

('Star Wars: The Empire Strikes Back | Edición 50 Aniversario',
 'Proyección especial del Episodio V con material adicional restaurado.',
 'Sevilla',
 '2026-07-10 18:30:00',
 155, 155,
 'cine'
),

 -- 🌟 POPULARES 
 ('People We Meet on Vacation | Proyección + Coloquio',
 'Proyección especial de la película romántica basada en la novela de Emily Henry.',
 'Mallorca',
 '2026-08-12 20:30:00',
 155, 155,
 'popular'
 ), 

('Tomorrowland Winter',
 'La edición invernal del festival más famoso del mundo.',
 'Alpes Franceses',
 '2026-03-05 16:00:00',
 500, 500,
 'popular'
),

('Madrid Gaming Experience',
 'El mayor evento de videojuegos y tecnología en España.',
 'Madrid',
 '2026-10-18 10:00:00',
 400, 400,
 'popular'
),

('Comic Con Barcelona',
 'Convención de cómics, cine, series y cultura pop.',
 'Barcelona',
 '2026-05-12 09:00:00',
 350, 350,
 'popular'
),

('Festival Internacional de Jazz',
 'Artistas de todo el mundo se reúnen en un festival único.',
 'Granada',
 '2026-09-02 19:00:00',
 300, 300,
 'popular'
),

-- 🎬 Otro evento de cine
('People We Meet on Vacation', 
 'Una comedia romántica que sigue a dos amigos en sus vacaciones por Europa.', 
 'Mallorca', 
 '2026-08-12 20:30:00', 
 155, 155, 
 'cine');


