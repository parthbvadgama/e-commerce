CREATE DATABASE IF NOT EXISTS goldcart CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE goldcart;
CREATE TABLE IF NOT EXISTS users (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100) NOT NULL,
 email VARCHAR(190) NOT NULL UNIQUE,
 password_hash VARCHAR(255) NOT NULL,
 role ENUM('customer','admin') NOT NULL DEFAULT 'customer',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS products (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(180) NOT NULL,
 category VARCHAR(80) NOT NULL DEFAULT 'Electronics',
 price DECIMAL(10,2) NOT NULL,
 image VARCHAR(500) DEFAULT '',
 description TEXT,
 active TINYINT(1) NOT NULL DEFAULT 1,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS orders (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 user_id INT UNSIGNED NOT NULL,
 order_no VARCHAR(32) NOT NULL UNIQUE,
 total DECIMAL(10,2) NOT NULL,
 address TEXT NOT NULL,
 status ENUM('Pending','Processing','Shipped','Delivered','Cancelled') NOT NULL DEFAULT 'Pending',
 payment_status ENUM('Unpaid','Paid','Failed','Refunded') NOT NULL DEFAULT 'Unpaid',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY (user_id) REFERENCES users(id)
);
CREATE TABLE IF NOT EXISTS order_items (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 order_id INT UNSIGNED NOT NULL,
 product_id INT UNSIGNED NULL,
 product_name VARCHAR(180) NOT NULL,
 price DECIMAL(10,2) NOT NULL,
 quantity INT UNSIGNED NOT NULL,
 FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
 FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
);
INSERT INTO products(name,category,price,image,description) VALUES
('Wireless Headphones','Electronics',2499,'assets/placeholder.svg','Immersive sound for everyday listening.'),
('Smart Watch','Electronics',3999,'assets/placeholder.svg','Stay connected and track your day.'),
('Bluetooth Speaker','Electronics',1799,'assets/placeholder.svg','Compact sound with bold style.'),
('Everyday Backpack','Accessories',1299,'assets/placeholder.svg','A practical companion for daily use.')
;
-- Create your first user through register.php, then promote that account to admin:
-- UPDATE users SET role='admin' WHERE email='your-email@example.com';
