-- Limpia la base y crea la estructura necesaria
DROP DATABASE IF EXISTS cmdb;
CREATE DATABASE cmdb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cmdb;

-- Departamentos
CREATE TABLE departamentos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  ubicacion VARCHAR(150)
);

-- Categorías
CREATE TABLE categorias (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL,
  descripcion TEXT
);

-- Colaboradores
CREATE TABLE colaboradores (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL,
  apellido VARCHAR(50) NOT NULL,
  identificacion VARCHAR(50) NOT NULL UNIQUE,
  foto VARCHAR(255),
  direccion VARCHAR(150),
  ubicacion VARCHAR(100),
  telefono VARCHAR(20),
  correo VARCHAR(100),
  departamento_id INT,
  activo TINYINT(1) DEFAULT 1,
  FOREIGN KEY (departamento_id) REFERENCES departamentos(id)
);

-- Inventario
CREATE TABLE inventario (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre_equipo VARCHAR(100) NOT NULL,
  categoria_id INT NOT NULL,
  marca VARCHAR(50),
  modelo VARCHAR(50),
  numero_serie VARCHAR(100) UNIQUE,
  costo DECIMAL(10,2),
  fecha_ingreso DATE,
  tiempo_depreciacion INT, -- en meses
  estado ENUM('activo','baja','reparacion','descarte','donado','inventario','solicitado','asignado') DEFAULT 'activo',
  descripcion TEXT,
  imagen VARCHAR(255), -- Ruta o nombre de archivo de la imagen
  FOREIGN KEY (categoria_id) REFERENCES categorias(id)
);

-- Solicitudes (solo para admins)
CREATE TABLE solicitudes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  inventario_id INT NOT NULL, -- El equipo/software solicitado
  colaborador_id INT NOT NULL, -- Quién solicita
  fecha_solicitud DATETIME DEFAULT CURRENT_TIMESTAMP,
  estado ENUM('pendiente','aceptada','rechazada') DEFAULT 'pendiente',
  usuario_admin_id INT, -- Quién responde (NULL hasta que admin actúe)
  fecha_respuesta DATETIME,
  FOREIGN KEY (inventario_id) REFERENCES inventario(id),
  FOREIGN KEY (colaborador_id) REFERENCES colaboradores(id),
  FOREIGN KEY (usuario_admin_id) REFERENCES usuarios(id)
);

-- Asignaciones
CREATE TABLE asignaciones (
  id INT AUTO_INCREMENT PRIMARY KEY,
  inventario_id INT NOT NULL,
  colaborador_id INT NOT NULL,
  fecha_asignacion DATE,
  fecha_retiro DATE,
  estado ENUM('asignado','retirado') DEFAULT 'asignado',
  FOREIGN KEY (inventario_id) REFERENCES inventario(id),
  FOREIGN KEY (colaborador_id) REFERENCES colaboradores(id)
);

-- Usuarios
CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  correo VARCHAR(100) NOT NULL UNIQUE,
  contrasena VARCHAR(255) NOT NULL,
  rol ENUM('admin','tecnico') DEFAULT 'tecnico',
  activo TINYINT(1) DEFAULT 1,
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE historial_accesos_colaborador (
    id INT AUTO_INCREMENT PRIMARY KEY,
    colaborador_id INT NOT NULL,
    fecha_hora DATETIME NOT NULL,
    ip VARCHAR(45),
    user_agent VARCHAR(255),
    FOREIGN KEY (colaborador_id) REFERENCES colaboradores(id) ON DELETE CASCADE
);

-- Ejemplo de datos base
INSERT INTO categorias (nombre, descripcion) VALUES
('Software', 'Programas informáticos'),
('Hardware', 'Equipo o componentes electrónicos'),
('Equipo de red', 'Dispositivos de red'),
('Equipo de cómputo', 'Computadoras y periféricos'),
('Equipo de telefonía', 'Teléfonos y accesorios');


-- 1. Software
INSERT INTO inventario (nombre_equipo, categoria_id, marca, modelo, numero_serie, costo, fecha_ingreso, tiempo_depreciacion, estado, descripcion, imagen)
VALUES
('Microsoft Office 2021', 1, 'Microsoft', 'Office 2021', 'LICMSOFF2021', 240.00, '2024-05-10', 36, 'inventario', 'Licencia de suite ofimática actualizada.', 'office2021.png'),
('Adobe Photoshop CC', 1, 'Adobe', 'Photoshop CC 2024', 'LICADPS2024', 360.00, '2024-06-15', 24, 'inventario', 'Licencia de edición de imágenes profesional.', 'photoshopcc.png');

-- 2. Hardware
INSERT INTO inventario (nombre_equipo, categoria_id, marca, modelo, numero_serie, costo, fecha_ingreso, tiempo_depreciacion, estado, descripcion, imagen)
VALUES
('Disco Duro SSD 1TB', 2, 'Kingston', 'A2000', 'SNKINGSSD1TB', 95.00, '2024-01-20', 36, 'inventario', 'SSD NVMe Kingston de alto rendimiento.', 'ssd1tb.png'),
('Fuente de poder 600W', 2, 'EVGA', '600 BR', 'SNEVGA600BR', 60.00, '2023-12-10', 48, 'inventario', 'Fuente de poder certificada para PC.', 'fuente600w.png');

-- 3. Equipo de red
INSERT INTO inventario (nombre_equipo, categoria_id, marca, modelo, numero_serie, costo, fecha_ingreso, tiempo_depreciacion, estado, descripcion, imagen)
VALUES
('Switch Cisco 16 puertos', 3, 'Cisco', 'SF110D-16', 'SNCISCO16', 150.00, '2024-03-18', 48, 'inventario', 'Switch no gestionable para oficina.', 'cisco16p.png'),
('Router TP-Link Archer', 3, 'TP-Link', 'Archer C6', 'SNTPARCHERC6', 70.00, '2024-02-05', 36, 'inventario', 'Router doble banda para red principal.', 'tplinkarcher.png');

-- 4. Equipo de cómputo
INSERT INTO inventario (nombre_equipo, categoria_id, marca, modelo, numero_serie, costo, fecha_ingreso, tiempo_depreciacion, estado, descripcion, imagen)
VALUES
('Laptop Dell Latitude', 4, 'Dell', 'Latitude 5410', 'SNDLAT5410', 1200.00, '2023-11-22', 36, 'inventario', 'Laptop para usuario administrativo.', 'delllatitude.png'),
('PC HP ProDesk', 4, 'HP', 'ProDesk 400', 'SNHPPD400', 850.00, '2024-04-10', 36, 'inventario', 'Equipo de escritorio para oficina.', 'hpprodesk.png');

-- 5. Equipo de telefonía
INSERT INTO inventario (nombre_equipo, categoria_id, marca, modelo, numero_serie, costo, fecha_ingreso, tiempo_depreciacion, estado, descripcion, imagen)
VALUES
('Teléfono IP Yealink', 5, 'Yealink', 'T21P E2', 'SNYEALINKT21', 55.00, '2024-07-01', 24, 'inventario', 'Teléfono IP para sala de reuniones.', 'yealinkt21p.png'),
('Auriculares Jabra Evolve', 5, 'Jabra', 'Evolve 20', 'SNJABRAEV20', 70.00, '2024-06-20', 24, 'inventario', 'Auriculares con micrófono para call center.', 'jabraevolve20.png');
COMMIT;