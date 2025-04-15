<?php
// Connect to your database
$conn = new mysqli("localhost", "root", "", "tub_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$productID = $_POST['productID'] ?? null;
$quantity = $_POST['quantity'] ?? 1;

if ($productID) {
    // Sanitize inputs
    $productID = intval($productID);
    $quantity = intval($quantity);

    // Fetch product info
    $sql = "SELECT * FROM Products WHERE productID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($product = $result->fetch_assoc()) {
        $productName = $product['name'];
        $price = $product['price'];
        $total = $price * $quantity;
    } else {
        die("Product not found.");
    }
} else {
    die("Invalid product.");
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Checkout</title>
</head>
<body>
  <h2>Checkout</h2>
  <p><strong>Product:</strong> <?= htmlspecialchars($productName) ?></p>
  <p><strong>Price per unit:</strong> RM<?= number_format($price, 2) ?></p>
  <p><strong>Quantity:</strong> <?= $quantity ?></p>
  <p><strong>Total:</strong> RM<?= number_format($total, 2) ?></p>

  <!-- You can continue with a payment form here -->
  <form action="process_payment.php" method="POST">
    <input type="hidden" name="productID" value="<?= $productID ?>">
    <input type="hidden" name="quantity" value="<?= $quantity ?>">
    <input type="hidden" name="total" value="<?= $total ?>">
    <button type="submit">Pay Now</button>
  </form>
</body>
</html>
