CREATE DATABASE IF NOT EXISTS rest CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE rest;

CREATE TABLE IF NOT EXISTS productos (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio_compra DECIMAL(10,2) NOT NULL,
    precio_venta DECIMAL(10,2) NOT NULL,
    existencia INT NOT NULL
);

INSERT INTO productos (nombre, descripcion, precio_compra, precio_venta, existencia) VALUES
('Mouse inalámbrico', 'Mouse óptico USB inalámbrico', 120.00, 199.00, 25),
('Teclado mecánico', 'Teclado con interruptores mecánicos', 520.00, 850.00, 12),
('Monitor 24 pulgadas', 'Monitor Full HD', 1750.00, 2499.00, 8);
