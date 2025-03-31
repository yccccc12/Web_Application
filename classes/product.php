<?php
require_once __DIR__ . '/database.php';

class Product {
    private $conn;

    public function __construct() {
        $this->conn = Database::connect();
    }

    // Fetch all products
    public function getAllProducts() {
        $sql = "SELECT productID, name, description, price, size, image_url, stock, category, colour FROM products";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }

        return $products;
    }

    // Fetch a single product by ID
    public function getProductById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE productID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc(); // Return the product as an array
    }

    // Insert a new product
    public function addProduct($name, $price, $stock, $imagePath, $color, $size) {
        $stmt = $this->conn->prepare("INSERT INTO products (name, price, stock, image_path, color, size) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sdisss", $name, $price, $stock, $imagePath, $color, $size);

        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    // Update an existing product
    public function updateProduct($id, $name, $price, $stock, $imagePath, $color, $size) {
        $stmt = $this->conn->prepare("UPDATE products SET name=?, price=?, stock=?, image_path=?, color=?, size=? WHERE id=?");
        $stmt->bind_param("sdisssi", $name, $price, $stock, $imagePath, $color, $size, $id);

        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    // Delete a product
    public function deleteProduct($id) {
        $stmt = $this->conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);

        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    public function __destruct() {
        Database::close();
    }
}
?>
