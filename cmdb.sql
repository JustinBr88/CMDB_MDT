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
  estado ENUM('activo','baja','reparacion','descarte','donado','inventario') DEFAULT 'activo',
  descripcion TEXT,
  FOREIGN KEY (categoria_id) REFERENCES categorias(id)
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

COMMIT;