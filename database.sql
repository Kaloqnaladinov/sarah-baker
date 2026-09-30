CREATE DATABASE IF NOT EXISTS sarah_baker
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE sarah_baker;

CREATE TABLE admins (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(190) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(120) NOT NULL UNIQUE,
    sort_order INT NOT NULL DEFAULT 0,
    active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE products (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id INT UNSIGNED NOT NULL,
    name VARCHAR(150) NOT NULL,
    description TEXT NULL,
    price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    image_url VARCHAR(500) NULL,
    available TINYINT(1) NOT NULL DEFAULT 1,
    featured TINYINT(1) NOT NULL DEFAULT 0,
    sort_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_products_category
        FOREIGN KEY (category_id)
        REFERENCES categories(id)
        ON DELETE CASCADE
);

CREATE TABLE orders (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(150) NOT NULL,
    customer_phone VARCHAR(50) NOT NULL,
    customer_email VARCHAR(190) NULL,

    order_type ENUM('pickup', 'reservation')
        NOT NULL DEFAULT 'pickup',

    requested_at DATETIME NULL,

    status ENUM(
        'pending',
        'confirmed',
        'preparing',
        'completed',
        'cancelled'
    ) NOT NULL DEFAULT 'pending',

    notes TEXT NULL,

    total DECIMAL(10,2) NOT NULL DEFAULT 0.00,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE order_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NOT NULL,
    product_name VARCHAR(150) NOT NULL,
    quantity INT UNSIGNED NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,

    CONSTRAINT fk_order_items_order
        FOREIGN KEY (order_id)
        REFERENCES orders(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_order_items_product
        FOREIGN KEY (product_id)
        REFERENCES products(id)
        ON DELETE RESTRICT
);

CREATE INDEX idx_products_category
ON products(category_id);

CREATE INDEX idx_products_available
ON products(available);

CREATE INDEX idx_orders_status
ON orders(status);

CREATE INDEX idx_orders_created
ON orders(created_at);

-- Default admin.
-- Password: 123456
-- Stored securely using bcrypt.
INSERT INTO admins (email, password_hash)
VALUES (
    'admin@sarahbaker.local',
    '$2y$12$TGJWTJuVR5bfvQuWdpds3uF8gP91NrRo/2/m5nIari5WH.vffs8Ra'
);

INSERT INTO categories (name, slug, sort_order) VALUES
('Viennoiseries', 'viennoiseries', 1),
('Pâtisseries', 'patisseries', 2),
('Boulangerie', 'boulangerie', 3),
('Breakfast', 'breakfast', 4),
('Lunch', 'lunch', 5),
('Hot Drinks', 'hot-drinks', 6),
('Cold Drinks', 'cold-drinks', 7);

-- Demo products.
-- Replace these with Sarah Baker's verified menu before presenting
-- the production version as the real menu.

INSERT INTO products
(category_id, name, description, price, featured, sort_order)
VALUES
(1, 'Croissant', 'Fresh French butter croissant.', 1.20, 1, 1),
(1, 'Pain au chocolat', 'Classic French pastry.', 1.40, 1, 2),
(2, 'Seasonal pastry', 'Ask the team about today’s selection.', 4.50, 1, 1),
(4, 'Breakfast', 'A selection of bakery favourites and coffee.', 9.90, 0, 1),
(6, 'Café crème', 'Espresso with steamed milk.', 3.50, 1, 1),
(6, 'Cappuccino', 'Espresso with textured milk.', 4.00, 1, 2),
(7, 'Iced coffee', 'Refreshing cold coffee.', 4.50, 0, 1);
