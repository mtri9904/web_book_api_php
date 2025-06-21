CREATE DATABASE IF NOT EXISTS my_store;
USE my_store;

CREATE TABLE category (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT
);

CREATE TABLE product (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    category_id INT,
    FOREIGN KEY (category_id) REFERENCES category(id)
);

CREATE TABLE orders (
id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(255) NOT NULL,
phone VARCHAR(20) NOT NULL,
address TEXT NOT NULL,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE order_details (
id INT AUTO_INCREMENT PRIMARY KEY,
order_id INT NOT NULL,
product_id INT NOT NULL,
quantity INT NOT NULL,
price DECIMAL(10, 2) NOT NULL,
FOREIGN KEY (order_id) REFERENCES orders(id)
);

CREATE TABLE IF NOT EXISTS voucher (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,
    discount_amount DECIMAL(10,2),
    discount_percent INT,
    min_order_amount DECIMAL(10,2),
    start_date DATETIME NOT NULL,
    end_date DATETIME NOT NULL,
    max_uses INT DEFAULT 1,
    current_uses INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)

ALTER TABLE product
ADD COLUMN quantity INT NOT NULL DEFAULT 0;

ALTER TABLE orders
ADD COLUMN voucher_code VARCHAR(100) DEFAULT NULL,
ADD FOREIGN KEY (voucher_code) REFERENCES voucher(CODE);


CREATE TABLE account (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE orders
ADD COLUMN user_id INT NOT NULL;
ALTER TABLE orders
ADD COLUMN total DECIMAL(10, 2) DEFAULT 0;

ALTER TABLE voucher ADD COLUMN discount_type ENUM('fixed', 'percent') NOT NULL DEFAULT 'fixed';

ALTER TABLE account ADD COLUMN fullname VARCHAR(100) AFTER username;

ALTER TABLE orders ADD COLUMN voucher_discount DECIMAL(10,2) DEFAULT 0;
ALTER TABLE account ADD COLUMN email VARCHAR(100) AFTER fullname;
ALTER TABLE account ADD COLUMN age INT AFTER email;
ALTER TABLE account ADD COLUMN phone VARCHAR(15) AFTER age;
ALTER TABLE account ADD COLUMN security_question VARCHAR(255) AFTER phone;
ALTER TABLE account ADD COLUMN security_answer VARCHAR(255) AFTER security_question;
ALTER TABLE account ADD COLUMN is_active BOOLEAN DEFAULT TRUE;

ALTER TABLE account ADD COLUMN google_id VARCHAR(255) NULL;

ALTER TABLE account
MODIFY COLUMN role ENUM('user', 'admin', 'manager') NOT NULL DEFAULT 'user';


-- Tạo bảng lưu đánh giá sản phẩm
CREATE TABLE IF NOT EXISTS product_reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_id INT NOT NULL,
    order_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES product(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES account(id) ON DELETE CASCADE,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    -- Một người dùng chỉ được đánh giá một sản phẩm trong một đơn hàng một lần
    UNIQUE KEY (product_id, user_id, order_id)
);

-- Thêm cột average_rating vào bảng product để lưu điểm đánh giá trung bình
ALTER TABLE product ADD COLUMN average_rating DECIMAL(3,2) DEFAULT 0;
ALTER TABLE product ADD COLUMN review_count INT DEFAULT 0; 


-- Thêm các cột lưu trữ thông tin sản phẩm vào bảng order_details
ALTER TABLE order_details
ADD COLUMN product_name VARCHAR(255) NULL AFTER product_id,
ADD COLUMN product_description TEXT NULL AFTER product_name,
ADD COLUMN product_image VARCHAR(255) NULL AFTER product_description,
ADD COLUMN product_category_id INT NULL AFTER product_image,
ADD COLUMN product_category_name VARCHAR(100) NULL AFTER product_category_id;

-- Cập nhật dữ liệu cho các đơn hàng hiện có (nếu có)
UPDATE order_details od
LEFT JOIN product p ON od.product_id = p.id
LEFT JOIN category c ON p.category_id = c.id
SET 
    od.product_name = COALESCE(p.name, '[Sản phẩm đã bị xóa]'),
    od.product_description = p.description,
    od.product_image = p.image,
    od.product_category_id = p.category_id,
    od.product_category_name = c.name
WHERE od.product_name IS NULL; 