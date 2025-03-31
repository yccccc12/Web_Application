CREATE DATABASE tub_db CHARACTER SET utf8 COLLATE utf8_general_ci;
USE tub_db;

-- User Table
CREATE TABLE Users (
    userID INT AUTO_INCREMENT,
    cartItemID INT,
    orderID INT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(13) NOT NULL,
    CONSTRAINT Users_userID_pk PRIMARY KEY(userID),
    CONSTRAINT Users_orderID_fk FOREIGN KEY (orderID) REFERENCES Orders(orderID),
    CONSTRAINT Users_cartItemID_fk FOREIGN KEY (cartItemID) REFERENCES CartItems(cartItemID)
);

-- Product Table
CREATE TABLE Products (
    productID INT AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(13,2) NOT NULL,
    size CHAR(2) NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    stock INT NOT NULL,
    category VARCHAR(20) NOT NULL,
    colour VARCHAR (20) NOT NULL,
    CONSTRAINT Products_productID_pk PRIMARY KEY(productID)
);

-- Cart Item Table
CREATE TABLE CartItems (
    cartItemID INT AUTO_INCREMENT,
    userID INT,
    productID INT,
    quantity INT,
    CONSTRAINT CartItems_cartItemID_pk PRIMARY KEY(cartItemID),
    CONSTRAINT CartItems_userID_fk FOREIGN KEY (userID) REFERENCES Users(userID),
    CONSTRAINT CartItems_productID_fk FOREIGN KEY (productID) REFERENCES Products(productID)
);

-- Order Table
CREATE TABLE Orders (
    orderID INT AUTO_INCREMENT,
    orderItemID INT,
    userID INT,
    orderStatus VARCHAR(50) DEFAULT 'Pending',
    totalAmount DECIMAL(13,2) NOT NULL,
    paymentMethod VARCHAR(50) NOT NULL,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    unit INT NOT NULL,
    state VARCHAR(50) NOT NULL,
    postcode INT(5) NOT NULL,
    city VARCHAR(255) NOT NULL,

    CONSTRAINT Orders_orderID_pk PRIMARY KEY(orderID),
    CONSTRAINT Orders_userID_fk FOREIGN KEY (userID) REFERENCES Users(userID),
    CONSTRAINT Orders_orderItemID_fk FOREIGN KEY (orderItemID) REFERENCES OrderItems(orderItemID)
);

-- Ordered Item Table
CREATE TABLE OrderItems (
    orderItemID INT AUTO_INCREMENT,
    orderID INT,
    productID INT,
    quantity INT,
    CONSTRAINT OrderItems_orderItemID_pk PRIMARY KEY(orderItemID),
    CONSTRAINT OrderItems_orderID_fk FOREIGN KEY (orderID) REFERENCES Orders(orderID),
    CONSTRAINT OrderItems_productID_fk FOREIGN KEY (productID) REFERENCES Products(productID)
);

-- Insert sample product
INSERT INTO Products (name, description, price, size, image_url, stock, category, colour) 
VALUES
('Men Lightweight Hoodies Jacket', 'A comfortable and stylish lightweight hoodie.', 199.90, 'M', '../img/item1.jpg', 50, 'Men', 'Green'),
('Men Oversized T-Shirt Short', 'Soft cotton oversized t-shirt for casual wear.', 159.90, 'XL', '../img/item2.jpg', 60, 'Men', 'Black'),
('Men Bomber Jacket', 'Trendy bomber jacket perfect for all seasons.', 219.90, 'L', '../img/item3.jpg', 40, 'Men', 'Black'),
('Men Oversized T-Shirt Short', 'A relaxed fit t-shirt in grey.', 159.90, 'M', '../img/item4.jpg', 55, 'Men', 'Grey'),
('Women Long-Sleeve Sweatshirt', 'Comfortable and stylish long-sleeve sweatshirt.', 119.90, 'M', '../img/item5.jpg', 45, 'Women', 'Black'),
('Women Oversized Denim Jacket', 'Trendy oversized denim jacket.', 169.90, 'S', '../img/item6.jpg', 30, 'Women', 'Blue'),
('Women Crop Polo Tee', 'Casual crop polo tee for daily wear.', 109.90, 'L', '../img/item7.jpg', 40, 'Women', 'Black'),
('Women Polo Tee', 'Classic polo tee with a relaxed fit.', 89.90, 'XL', '../img/item8.jpg', 50, 'Women', 'Black');