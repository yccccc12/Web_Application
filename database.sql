CREATE DATABASE tub_db CHARACTER SET utf8 COLLATE utf8_general_ci;
USE tub_db;

-- User Table
CREATE TABLE Users (
    userID INT AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(13) NOT NULL,
    birthday DATE,  
    gender ENUM('Male', 'Female') NULL DEFAULT NULL,  
    CONSTRAINT Users_userID_pk PRIMARY KEY(userID)
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

-- Order Table
CREATE TABLE Orders (
    orderID INT AUTO_INCREMENT,
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
    CONSTRAINT Orders_userID_fk FOREIGN KEY (userID) REFERENCES Users(userID)
);

-- Ordered Item Table
CREATE TABLE OrderItems (
    orderItemID INT AUTO_INCREMENT,
    orderID INT,
    productID INT,
    variantID INT,
    quantity INT,
    CONSTRAINT OrderItems_orderItemID_pk PRIMARY KEY(orderItemID),
    CONSTRAINT OrderItems_orderID_fk FOREIGN KEY (orderID) REFERENCES Orders(orderID),
    CONSTRAINT OrderItems_productID_fk FOREIGN KEY (productID) REFERENCES Products(productID),
    CONSTRAINT OrderItems_variantID_fk FOREIGN KEY (variantID) REFERENCES ProductVariants(variantID)
);

-- Review Table
CREATE TABLE Ratings (
    ratingID INT AUTO_INCREMENT,
    orderID INT,
    productID INT,
    size CHAR(2) NOT NULL,
    userID INT,
    rating INT,
    review TEXT,
    CONSTRAINT Ratings_ratingID_pk PRIMARY KEY(ratingID),
    CONSTRAINT Ratings_orderID_fk FOREIGN KEY (orderID) REFERENCES Orders(orderID),
    CONSTRAINT Ratings_productID_fk FOREIGN KEY (productID) REFERENCES Products(productID),
    CONSTRAINT Ratings_userID_fk FOREIGN KEY (userID) REFERENCES Users(userID)
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
('Women Oversized Short-Sleeve Shirt - Pink', 'Trendy oversized denim jacket.', 129.90, 'Women', 'Pink'),
('Men Short-Sleeve Oversized T-Shirt - Khaki', 'Soft cotton oversized t-shirt for casual wear.', 129.90, 'Men', 'Khaki'),
('Men Short-Sleeve Oversized T-Shirt - Black', 'Soft cotton oversized t-shirt for casual wear.', 129.90, 'Men', 'Black'),
('Men Short-Sleeve Graphic Tee - Black', 'Stylish graphic t-shirt for casual wear.', 59.90, 'Men', 'Black'),
('Men Short-Sleeve Graphic Tee - Khaki', 'Stylish graphic t-shirt for casual wear.', 59.90, 'Men', 'Khaki'),
('Women Knit Crop Top - Yellow', 'Stylish knit crop top for casual wear.', 119.90, 'Women', 'Yellow'),
('Women Knit Crop Top - Black', 'Stylish knit crop top for casual wear.', 119.90, 'Women', 'Black'),
('Women Long-Sleeve Crop Shirt - Black', 'Stylish long-sleeve crop shirt for casual wear.', 129.90, 'Women', 'Black'),
('Women Long-Sleeve Crop Shirt - Blue', 'Stylish long-sleeve crop shirt for casual wear.', 129.90, 'Women', 'Blue');

-- Insert sample product variants and images
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
(8, 'M', 70),
(9, 'L', 60),
(9, 'XL', 50),
(10, 'S', 80),
(10, 'M', 70),
(10, 'L', 60),
(11, 'S', 80),
(11, 'M', 70),
(12, 'L', 60),
(12, 'XL', 50),
(13, 'S', 80),
(13, 'M', 70),
(13, 'L', 60),
(14, 'M', 70),
(14, 'L', 60),
(15, 'S', 80),
(15, 'M', 70),
(15, 'L', 60),
(16, 'S', 80),
(16, 'M', 70),
(16, 'L', 60);

-- Insert sample product images
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
(8, '../img/product_8_back.jpg', 'Back'),
(9, '../img/product_9_front.jpg', 'Front'),
(9, '../img/product_9_back.jpg', 'Back'),
(10, '../img/product_10_front.jpg', 'Front'),
(10, '../img/product_10_back.jpg', 'Back'),
(11, '../img/product_11_front.jpg', 'Front'),
(11, '../img/product_11_back.jpg', 'Back'),
(12, '../img/product_12_front.jpg', 'Front'),
(12, '../img/product_12_back.jpg', 'Back'),
(13, '../img/product_13_front.jpg', 'Front'),
(13, '../img/product_13_back.jpg', 'Back'),
(14, '../img/product_14_front.jpg', 'Front'),
(14, '../img/product_14_back.jpg', 'Back'),
(15, '../img/product_15_front.jpg', 'Front'),
(15, '../img/product_15_back.jpg', 'Back'),
(16, '../img/product_16_front.jpg', 'Front'),
(16, '../img/product_16_back.jpg', 'Back');

-- Insert sample user
-- Password: Abcd1234#   * (Hashed using bcrypt)
INSERT INTO Users (name, email, password, phone, birthday, gender)
VALUES ('Test User', 'testuser@example.com', '$2y$10$FyFk9XUsFXuxy3WEEf5y/eT7Qe0eltCxW.pM2sFlB.P.uGP/EdCwC', '012-3456789', '1995-05-10', 'Male');

-- Assume inserted userID = 1
INSERT INTO Orders (userID, orderStatus, totalAmount, paymentMethod, date, unit, state, postcode, city)
VALUES
(1, 'Delivered', 749.50, 'Credit Card', '2024-01-10 09:15:00', 5, 'Selangor', 47301, 'Shah Alam'),
(1, 'Delivered', 119.90, 'E-Wallet', '2024-03-18 13:45:00', 8, 'Selangor', 47301, 'Shah Alam'),
(1, 'Delivered', 259.80, 'Online Banking', '2024-06-25 17:30:00', 2, 'Selangor', 47301, 'Shah Alam'),
(1, 'Delivered', 89.90, 'Credit Card', '2024-09-02 11:00:00', 10, 'Selangor', 47301, 'Shah Alam'),
(1, 'Delivered', 389.70, 'Credit Card', '2023-01-22 11:00:00', 3, 'Selangor', 47301, 'Shah Alam'),
(1, 'Delivered', 89.90, 'Online Banking', '2023-04-17 15:20:00', 6, 'Selangor', 47301, 'Shah Alam'),
(1, 'Delivered', 239.60, 'E-Wallet', '2023-07-08 13:05:00', 4, 'Selangor', 47301, 'Shah Alam'),
(1, 'Delivered', 299.80, 'Credit Card', '2023-10-29 18:45:00', 9, 'Selangor', 47301, 'Shah Alam');

-- Insert sample order items
INSERT INTO OrderItems (orderID, productID, variantID, quantity)
VALUES
(1, 1, 1, 5),
(2, 3, 7, 1),
(3, 9, 23, 2),
(4, 5, 13, 1),
(5, 10, 25, 3),
(6, 6, 16, 1),
(7, 11, 28, 4),
(8, 2, 5, 2);

-- Insert sample ratings and reviews
INSERT INTO Ratings (orderID, productID, size, userID, rating, review)
VALUES
(1, 1, 'M', 1, 5, 'Excellent quality hoodie.'),
(2, 3, 'S' , 1, 4, 'Good shirt, soft material.'),
(3, 9, 'L', 1, 5, 'Perfect fit and color.'),
(4, 5, 'S', 1, 3, 'Okay, not what I expected.'),
(5, 10, 'S', 1, 5, 'Great fabric and fit.'),
(6, 6, 'M', 1, 4, 'Nice color and material.'),
(7, 11, 'S', 1, 3, 'Looks okay, average quality.'),
(8, 2, 'S', 1, 5, 'Love the style and color!');