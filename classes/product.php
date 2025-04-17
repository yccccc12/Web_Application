<?php
require_once __DIR__ . '/database.php';

class Product {
    private $conn;

    public function __construct() {
        $this->conn = Database::connect();
    }

    public function getAllProducts() {
        $sql = "
            SELECT 
                p.productID,
                p.name,
                p.description,
                p.price,
                p.category,
                p.colour,
                pi.imageID,
                pi.image_url,
                pi.image_type,
                pv.variantID,
                pv.size,
                pv.stock
            FROM Products p
            LEFT JOIN ProductImages pi ON p.productID = pi.productID
            LEFT JOIN ProductVariants pv ON p.productID = pv.productID
        ";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $productID = $row['productID'];
    
            // Initialize product if not already added
            if (!isset($products[$productID])) {
                $products[$productID] = [
                    'productID' => $row['productID'],
                    'name' => $row['name'],
                    'description' => $row['description'],
                    'price' => $row['price'],
                    'category' => $row['category'],
                    'colour' => $row['colour'],
                    'images' => [],
                    'variants' => []
                ];
            }
    
            if ($row['imageID']) {
                $imageExists = false;
                foreach ($products[$productID]['images'] as $image) {
                    if ($image['imageID'] === $row['imageID']) {
                        $imageExists = true;
                        break;
                    }
                }
                if (!$imageExists) {
                    $products[$productID]['images'][] = [
                        'imageID' => $row['imageID'],
                        'image_url' => $row['image_url'],
                        'image_type' => $row['image_type']
                    ];
                }
            }
            
            // Add variant if it exists and is not already added
            if ($row['variantID']) {
                $variantExists = false;
                foreach ($products[$productID]['variants'] as $variant) {
                    if ($variant['variantID'] === $row['variantID']) {
                        $variantExists = true;
                        break;
                    }
                }
                if (!$variantExists) {
                    $products[$productID]['variants'][] = [
                        'variantID' => $row['variantID'],
                        'size' => $row['size'],
                        'stock' => $row['stock']
                    ];
                }
            }
        }
    
        // Re-index the array to return a sequential array of products
        return array_values($products);
    }
    
    public function getAProduct($id) {
        $sql = "
            SELECT 
                p.productID,
                p.name,
                p.description,
                p.price,
                p.category,
                p.colour,
                pi.imageID,
                pi.image_url,
                pi.image_type,
                pv.variantID,
                pv.size,
                pv.stock
            FROM Products p
            LEFT JOIN ProductImages pi ON p.productID = pi.productID
            LEFT JOIN ProductVariants pv ON p.productID = pv.productID
            WHERE p.productID = ?
        ";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $product = null;
        while ($row = $result->fetch_assoc()) {
            if (!$product) {
                $product = [
                    'productID' => $row['productID'],
                    'name' => $row['name'],
                    'description' => $row['description'],
                    'price' => $row['price'],
                    'category' => $row['category'],
                    'colour' => $row['colour'],
                    'images' => [],
                    'variants' => []
                ];
            }
    
            if ($row['imageID']) {
                $imageExists = false;
                foreach ($product['images'] as $image) {
                    if ($image['imageID'] === $row['imageID']) {
                        $imageExists = true;
                        break;
                    }
                }
                if (!$imageExists) {
                    $product['images'][] = [
                        'imageID' => $row['imageID'],
                        'image_url' => $row['image_url'],
                        'image_type' => $row['image_type']
                    ];
                }
            }
            
            if ($row['variantID']) {
                $variantExists = false;
                foreach ($product['variants'] as $variant) {
                    if ($variant['variantID'] === $row['variantID']) {
                        $variantExists = true;
                        break;
                    }
                }
                if (!$variantExists) {
                    $product['variants'][] = [
                        'variantID' => $row['variantID'],
                        'size' => $row['size'],
                        'stock' => $row['stock']
                    ];
                }
            }
        }
    
        return $product;
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

    public function getProductVariant($productID, $size) {
        $sql = "SELECT stock FROM ProductVariants WHERE productID = ? AND size = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("is", $productID, $size);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getProductVariantsID($productID, $size){
        $stmt = $this->conn->prepare("SELECT variantID FROM ProductVariants WHERE productID = ? AND size = ?");
        $stmt->bind_param("is", $productID, $size);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row ? $row['variantID'] : null; // return just the value or null if not found
    }

    public function __destruct() {
        // Remove the call to Database::close()
        // Let the database connection remain open for the script lifecycle
    }
}
?>