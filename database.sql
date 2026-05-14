-- =============================================
-- NYX MARKETPLACE - BASE DE DATOS
-- Ejecutar en phpMyAdmin sobre la DB "nyx"
-- =============================================

CREATE DATABASE IF NOT EXISTS nyx CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE nyx;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','customer') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    total DECIMAL(10,2) NOT NULL,
    status ENUM('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
    shipping_method VARCHAR(50),
    shipping_cost DECIMAL(10,2) DEFAULT 0,
    shipping_name VARCHAR(100),
    shipping_address TEXT,
    shipping_city VARCHAR(100),
    shipping_zip VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT,
    product_name VARCHAR(200) NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
);

CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_id INT NOT NULL,
    rating TINYINT NOT NULL,
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_review (product_id, user_id)
);

-- DATOS DE PRUEBA
INSERT INTO categories (name, slug) VALUES
('Electronica', 'electronica'),
('Ropa', 'ropa'),
('Hogar', 'hogar'),
('Deportes', 'deportes');

INSERT INTO products (category_id, name, description, price, stock) VALUES
(1, 'Audifonos Bluetooth', 'Sonido premium, bateria 20h, cancelacion de ruido.', 599.00, 15),
(1, 'Cable USB-C 2m', 'Carga rapida 65W, datos 480Mbps.', 89.00, 2),
(2, 'Playera Basica', '100% algodon, tallas S-XL, varios colores.', 199.00, 30),
(2, 'Sudadera con capucha', 'Fleece suave, bolsillo canguro.', 450.00, 1),
(3, 'Lampara LED Escritorio', 'Regulable, 3 tonos de luz, USB.', 320.00, 12),
(4, 'Botella Deportiva 1L', 'Acero inoxidable, mantiene temperatura 12h.', 250.00, 25);

-- Admin: admin@nyx.com / password
INSERT INTO users (name, email, password, role) VALUES
('Administrador', 'admin@nyx.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
