<?php
require_once __DIR__ . '/database.php';

class Order {
    private $conn;

    public function __construct() {
        // Use Database::connect() instead of Database::getInstance()
        $this->conn = Database::connect();
    }

    // Insert a new order
    public function createOrder($userID, $total, $paymentMethod, $unit, $state, $postcode, $city) {
        $stmt = $this->conn->prepare("INSERT INTO Orders (userID, totalAmount, paymentMethod, unit, state, postcode, city) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("idsssis", $userID, $total, $paymentMethod, $unit, $state, $postcode, $city);
        $stmt->execute();
        return $stmt->insert_id; // Return the newly created order ID
    }

    // Insert order items
    public function addOrderItems($orderID, $cart) {
        foreach ($cart as $item) {
            $stmt = $this->conn->prepare("INSERT INTO OrderItems (orderID, productID, quantity) VALUES (?, ?, ?)");
            $stmt->bind_param("iii", $orderID, $item['product_id'], $item['quantity']);
            $stmt->execute();
        }
    }

    // Get order history
    public function getOrderHistory($userID) {
        $stmt = $this->conn->prepare("SELECT orderID, date, totalAmount, orderStatus FROM Orders WHERE userID = ? ORDER BY date DESC");
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $result = $stmt->get_result();

        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
        return $orders;
    }

    public function reduceStock($cart) {
        foreach ($cart as $item) {
            $stmt = $this->conn->prepare("UPDATE ProductVariants SET stock = stock - ? WHERE productID = ? AND size = ?");
            $stmt->bind_param("iis", $item['quantity'], $item['product_id'], $item['size']);
            $stmt->execute();
        }
    }
}
?>