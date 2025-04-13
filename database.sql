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
    birthday DATE,  
    gender ENUM('Male', 'Female') NULL DEFAULT NULL,  
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
    category VARCHAR(20) NOT NULL,
    colour VARCHAR (20) NOT NULL,
    CONSTRAINT Products_productID_pk PRIMARY KEY(productID)
);

-- Images of product Table
CREATE TABLE ProductImages (
    imageID INT AUTO_INCREMENT,
    productID INT,
    image_url VARCHAR(255) NOT NULL,
    image_type ENUM('Front', 'Back') NOT NULL,
    CONSTRAINT ProductImages_imageID_pk PRIMARY KEY(imageID),
    CONSTRAINT ProductImages_productID_fk FOREIGN KEY (productID) REFERENCES Products(productID)
);

-- Size and stock of product Table
CREATE TABLE ProductVariants (
    variantID INT AUTO_INCREMENT,
    productID INT,
    size CHAR(2) NOT NULL,
    stock INT NOT NULL,
    CONSTRAINT ProductVariants_variantID_pk PRIMARY KEY(variantID),
    CONSTRAINT ProductVariants_productID_fk FOREIGN KEY (productID) REFERENCES Products(productID)
);

-- Cart Item Table
CREATE TABLE CartItems (
    cartItemID INT AUTO_INCREMENT,
    userID INT,
    productID INT,
    size VARCHAR(3), 
    quantity INT,
    colour VARCHAR (20) NOT NULL,
    addedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Optional
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

INSERT INTO Products (name, description, price, category, colour) 
VALUES
('Men Long-Sleeve Oversized Sweatshirt Hoodie - Black', 'A comfortable and stylish lightweight hoodie.', 149.90, 'Men', 'Black'),
('Men Long-Sleeve Oversized Sweatshirt Hoodie - Blue', 'A comfortable and stylish lightweight hoodie.', 149.90, 'Men', 'Blue'),
('Men Oversized T-Shirt Short Sleeve - Black', 'Soft cotton oversized t-shirt for casual wear.', 119.90, 'Men', 'Black'),
('Men Oversized T-Shirt Short Sleeve - Pink', 'Soft cotton oversized t-shirt for casual wear.', 119.90, 'Men', 'Pink'),
('Women Short-Sleeve Fashion Tee - Black', 'Comfortable and stylish long-sleeve sweatshirt.', 89.90, 'Women', 'Black'),
('Women Short-Sleeve Fashion Tee - Pink', 'Comfortable and stylish long-sleeve sweatshirt.', 89.90, 'Women', 'Pink'),
('Women Oversized Short-Sleeve Shirt - Black', 'Trendy oversized denim jacket.', 129.90, 'Women', 'Black'),
('Women Oversized Short-Sleeve Shirt - Pink', 'Trendy oversized denim jacket.', 129.90, 'Women', 'Pink');

INSERT INTO ProductVariants (productID, size, stock)
VALUES
(1, 'M', 40),
(1, 'L', 40),
(1, 'XL', 40),
(2, 'S', 50),
(2, 'M', 40),
(2, 'L', 30),
(3, 'S', 60),
(3, 'M', 50),
(3, 'L', 40),
(4, 'M', 50),
(4, 'L', 40),
(4, 'XL', 60),
(5, 'S', 70),
(5, 'M', 60),
(6, 'S', 70),
(6, 'M', 60),
(6, 'L', 50),
(7, 'S', 80),
(7, 'M', 70),
(7, 'L', 60),
(8, 'S', 80),
(8, 'M', 70);

INSERT INTO ProductImages (productID, image_url, image_type)
VALUES
(1, '../img/product_1_front.jpg', 'Front'),
(1, '../img/product_1_back.jpg', 'Back'),
(2, '../img/product_2_front.jpg', 'Front'),
(2, '../img/product_2_back.jpg', 'Back'),
(3, '../img/product_3_front.jpg', 'Front'),
(3, '../img/product_3_back.jpg', 'Back'),
(4, '../img/product_4_front.jpg', 'Front'),
(4, '../img/product_4_back.jpg', 'Back'),
(5, '../img/product_5_front.jpg', 'Front'),
(5, '../img/product_5_back.jpg', 'Back'),
(6, '../img/product_6_front.jpg', 'Front'),
(6, '../img/product_6_back.jpg', 'Back'),
(7, '../img/product_7_front.jpg', 'Front'),
(7, '../img/product_7_back.jpg', 'Back'),
(8, '../img/product_8_front.jpg', 'Front'),
(8, '../img/product_8_back.jpg', 'Back');
