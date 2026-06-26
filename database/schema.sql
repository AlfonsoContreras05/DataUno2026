-- DataUno ProMax V11
-- Ejecutar en phpMyAdmin sobre la base: datauno1_datauno_db
-- Usuario admin inicial: admin
-- Clave inicial: la clave temporal definida para la instalación. Cambiar después de probar.

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS producto_imagenes;
DROP TABLE IF EXISTS producto_detalles;
DROP TABLE IF EXISTS productos;
DROP TABLE IF EXISTS categorias;
DROP TABLE IF EXISTS configuracion;
DROP TABLE IF EXISTS admin_users;

CREATE TABLE admin_users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(80) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  nombre VARCHAR(120) DEFAULT NULL,
  activo TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE categorias (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(120) NOT NULL,
  slug VARCHAR(140) NOT NULL UNIQUE,
  orden INT NOT NULL DEFAULT 0,
  activo TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE productos (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  categoria_id INT UNSIGNED NOT NULL,
  slug VARCHAR(180) NOT NULL UNIQUE,
  nombre VARCHAR(180) NOT NULL,
  descripcion_corta TEXT NOT NULL,
  descripcion_larga TEXT NULL,
  detalle TEXT NULL,
  precio_tipo ENUM('cotizar','desde','fijo') NOT NULL DEFAULT 'cotizar',
  precio_valor DECIMAL(12,2) NULL,
  imagen_principal VARCHAR(255) NOT NULL DEFAULT 'assets/img/placa-tech.jpg',
  badge VARCHAR(120) DEFAULT 'Validación DataUno',
  stock_estado VARCHAR(120) DEFAULT 'Consultar',
  destacado TINYINT(1) NOT NULL DEFAULT 0,
  activo TINYINT(1) NOT NULL DEFAULT 1,
  instalacion_disponible TINYINT(1) NOT NULL DEFAULT 1,
  orden INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_productos_categoria FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE RESTRICT ON UPDATE CASCADE,
  INDEX idx_productos_slug (slug),
  INDEX idx_productos_activo (activo),
  INDEX idx_productos_destacado (destacado),
  INDEX idx_productos_orden (orden)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE producto_imagenes (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  producto_id INT UNSIGNED NOT NULL,
  ruta VARCHAR(255) NOT NULL,
  alt VARCHAR(180) DEFAULT NULL,
  es_principal TINYINT(1) NOT NULL DEFAULT 0,
  orden INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_producto_imagenes_producto FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE producto_detalles (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  producto_id INT UNSIGNED NOT NULL,
  titulo VARCHAR(160) NOT NULL,
  contenido TEXT NOT NULL,
  orden INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_producto_detalles_producto FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE configuracion (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  clave VARCHAR(100) NOT NULL UNIQUE,
  valor TEXT NULL,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Hash generado con password_hash('DataUno2026#', PASSWORD_DEFAULT).
-- Cambiar la clave apenas el panel quede operativo.
INSERT INTO admin_users (username, password_hash, nombre, activo) VALUES
('admin', '$2y$12$HVVP.hy7m9ZzcFENg/N4EeolW8N.5ppK2v2Vfp7/MrMV6ORsc9Otu', 'Administrador DataUno', 1);

INSERT INTO categorias (nombre, slug, orden, activo) VALUES
('Almacenamiento', 'almacenamiento', 10, 1),
('Memorias RAM', 'memorias-ram', 20, 1),
('Accesorios', 'accesorios', 30, 1),
('Repuestos', 'repuestos', 40, 1),
('Mantención', 'mantencion', 50, 1),
('Periféricos', 'perifericos', 60, 1),
('Combos', 'combos', 70, 1);

INSERT INTO productos
(categoria_id, slug, nombre, descripcion_corta, descripcion_larga, detalle, precio_tipo, precio_valor, imagen_principal, badge, stock_estado, destacado, activo, instalacion_disponible, orden)
VALUES
((SELECT id FROM categorias WHERE slug='almacenamiento'), 'ssd-240-480', 'Disco SSD 240GB / 480GB', 'Ideal para acelerar computadores lentos, mejorar arranque y reducir tiempos de carga.', 'Unidad SSD recomendada para equipos que necesitan una mejora visible de velocidad sin cambiar el computador completo.', 'Instalación disponible según equipo. Capacidad y marca sujetas a stock.', 'cotizar', NULL, 'assets/img/placa-tech.jpg', 'Upgrade recomendado', 'Sujeto a stock', 1, 1, 1, 10),
((SELECT id FROM categorias WHERE slug='almacenamiento'), 'ssd-1tb', 'Disco SSD 1TB', 'Más espacio y velocidad para equipos de trabajo, estudio, diseño o uso diario intensivo.', 'Buena opción para usuarios que necesitan velocidad y mayor capacidad para archivos, programas y proyectos.', 'Recomendado para notebooks y PC que necesitan mayor capacidad.', 'cotizar', NULL, 'assets/img/servicio-tecnico.jpeg', 'Mayor capacidad', 'Sujeto a stock', 0, 1, 1, 20),
((SELECT id FROM categorias WHERE slug='memorias-ram'), 'ram-ddr4', 'Memoria RAM DDR4', 'Mejora multitarea, navegación, programas de oficina y rendimiento general del equipo.', 'Memoria para equipos compatibles DDR4. Antes de cotizar se valida formato, frecuencia y límite soportado.', 'Se valida compatibilidad antes de recomendar capacidad.', 'cotizar', NULL, 'assets/img/diagnostico.jpeg', 'Compatibilidad previa', 'Consultar', 1, 1, 1, 30),
((SELECT id FROM categorias WHERE slug='memorias-ram'), 'ram-ddr3', 'Memoria RAM DDR3', 'Opción para equipos antiguos que todavía pueden ganar estabilidad y fluidez.', 'Alternativa para extender vida útil de equipos antiguos compatibles con DDR3.', 'Sujeta a disponibilidad y revisión del modelo exacto.', 'cotizar', NULL, 'assets/img/placa-tech.jpg', 'Equipos antiguos', 'Consultar', 0, 1, 1, 40),
((SELECT id FROM categorias WHERE slug='accesorios'), 'cargador-notebook', 'Cargadores para notebook', 'Cargadores compatibles según voltaje, amperaje, punta y modelo del equipo.', 'Se recomienda validar cargador antes de comprar para evitar daños por voltaje, amperaje o punta incorrecta.', 'Se confirma compatibilidad para evitar daños al equipo.', 'cotizar', NULL, 'assets/img/soporte-tecnico.jpg', 'Según modelo', 'Variable', 1, 1, 0, 50),
((SELECT id FROM categorias WHERE slug='repuestos'), 'teclado-notebook', 'Teclados para notebook', 'Repuesto e instalación para teclados con teclas dañadas, fallas o derrames.', 'Teclados sujetos a marca y modelo exacto. Puede requerir revisión previa del equipo.', 'Cotización según marca, modelo y disponibilidad.', 'cotizar', NULL, 'assets/img/servicio-tecnico.jpeg', 'Instalación disponible', 'Consultar', 0, 1, 1, 60),
((SELECT id FROM categorias WHERE slug='repuestos'), 'bateria-notebook', 'Baterías para notebook', 'Reemplazo de batería para equipos con poca duración, hinchazón o carga irregular.', 'Repuesto recomendado cuando la batería presenta desgaste, hinchazón o falla de carga.', 'Se revisa modelo exacto antes de cotizar.', 'cotizar', NULL, 'assets/img/diagnostico.jpeg', 'Revisión previa', 'Consultar', 0, 1, 1, 70),
((SELECT id FROM categorias WHERE slug='accesorios'), 'adaptadores-cables', 'Cables y adaptadores', 'HDMI, USB, red, adaptadores de video, energía y conectividad para uso diario.', 'Accesorios de conectividad para resolver necesidades puntuales de oficina, estudio o soporte.', 'Disponibilidad variable según tipo de conexión.', 'cotizar', NULL, 'assets/img/soporte-tecnico.jpg', 'Conectividad', 'Variable', 0, 1, 0, 80),
((SELECT id FROM categorias WHERE slug='mantencion'), 'pasta-termica', 'Pasta térmica y limpieza', 'Insumos para mantención preventiva, control de temperatura y mejor ventilación.', 'Servicio orientado a bajar temperaturas y prevenir fallas por sobrecalentamiento.', 'Puede incluirse en servicio de mantención.', 'cotizar', NULL, 'assets/img/placa-tech.jpg', 'Mantención', 'Disponible', 0, 1, 1, 90),
((SELECT id FROM categorias WHERE slug='perifericos'), 'perifericos-basicos', 'Mouse, teclado y periféricos', 'Periféricos básicos para oficina, estudio, hogar o reemplazo rápido.', 'Opciones simples para complementar equipos de trabajo o estudio.', 'Cotización según disponibilidad y preferencia.', 'cotizar', NULL, 'assets/img/servicio-tecnico.jpeg', 'Uso diario', 'Variable', 0, 1, 0, 100),
((SELECT id FROM categorias WHERE slug='almacenamiento'), 'disco-respaldo', 'Discos para respaldo', 'Opciones para respaldar documentos, fotos, trabajos y archivos importantes.', 'Recomendado para mantener copias seguras de información importante.', 'Se recomienda capacidad según volumen de datos.', 'cotizar', NULL, 'assets/img/soporte-tecnico.jpg', 'Protege tus datos', 'Consultar', 0, 1, 0, 110),
((SELECT id FROM categorias WHERE slug='combos'), 'kit-upgrade', 'Combo upgrade equipo lento', 'Combinación recomendada de SSD, RAM y mantención para recuperar rendimiento.', 'Combo armado según diagnóstico, presupuesto y compatibilidad real del equipo.', 'Se arma según diagnóstico y presupuesto.', 'cotizar', NULL, 'assets/img/diagnostico.jpeg', 'Combo recomendado', 'Consultar', 1, 1, 1, 120);

INSERT INTO configuracion (clave, valor) VALUES
('whatsapp', '+56 9 9439 2133'),
('email', 'victordiaz.pc@gmail.com'),
('zona', 'Victoria, Araucanía');

SET FOREIGN_KEY_CHECKS = 1;
