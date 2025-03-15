CREATE TABLE User (
    userID INT AUTO_INCREMENT PRIMARY KEY,
    cartItemID INT,
    orderID INT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    telephone VARCHAR(13) NOT NULL,
    FOREIGN KEY (orderID) REFERENCES Order(orderID),
    FOREIGN KEY (cartItemID) REFERENCES CartItem(cartItemID)
);

CREATE TABLE Product (
    productID INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(13,2) NOT NULL,
    size CHAR(1) NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    stock INT NOT NULL,
    category VARCHAR(20) NOT NULL
);

CREATE TABLE CartItem (
    cartItemID INT AUTO_INCREMENT PRIMARY KEY,
    userID INT,
    productID INT,
    quantity INT,
    FOREIGN KEY (userID) REFERENCES User(userID),
    FOREIGN KEY (productID) REFERENCES Product(productID)
);

CREATE TABLE Order (
    orderID INT AUTO_INCREMENT PRIMARY KEY,
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
    FOREIGN KEY (userID) REFERENCES User(userID),
    FOREIGN KEY (orderItemID) REFERENCES OrderItem(orderItemID)
);

CREATE TABLE OrderItem (
    orderItemID INT AUTO_INCREMENT PRIMARY KEY,
    orderID INT,
    productID INT,
    quantity INT,
    FOREIGN KEY (orderID) REFERENCES Order(orderID),
    FOREIGN KEY (productID) REFERENCES Product(productID)
);
