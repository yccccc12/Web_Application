<?php
session_start();
require_once '../classes/product.php'; // Include your Product class
require_once '../classes/order.php';   // Include your Order class

// Retrieve cart and total price from session
$cart = $_SESSION['cart'] ?? [];
$total = 0;
foreach ($cart as $item) {
    $product = new Product();
    $productData = $product->getAProduct($item['product_id']);
    $price = $productData['price'] ?? 0;
    $subtotal = $price * $item['quantity'];
    $total += $subtotal;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userID = $_SESSION['user_id']; // Assuming user ID is stored in session
    $state = htmlspecialchars($_POST['state']);
    $city = htmlspecialchars($_POST['city']);
    $postcode = htmlspecialchars($_POST['postcode']);
    $unit = htmlspecialchars($_POST['unit']);
    $paymentMethod = htmlspecialchars($_POST['payment_method']);

    // Use the Order class to handle database operations
    $order = new Order();
    $orderID = $order->createOrder($userID, $total, $paymentMethod, $unit, $state, $postcode, $city);
    $order->addOrderItems($orderID, $cart);

    // Reduce stock levels
    $order->reduceStock($cart);

    // Clear the cart
    unset($_SESSION['cart']);

    // Redirect to a success page
    header("Location: /Web_Application/payment/success.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="../style/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css">
    <link rel="stylesheet" href="../style/payment.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <div class="checkout-container">
        <h1 style="font-size:30px;padding-left:40px; padding-top:20px;">Payment</h1>
        <p style="padding-left: 40px; padding-top: 10px;"><strong>Total Price:</strong> RM<?php echo number_format($total, 2); ?></p>
        <br>
        <form id="paymentForm" action="cart_processPayment.php" method="POST" style="padding-left: 40px; padding-right: 40px;">
            <h2>Shipping Address</h2>
            <label for="unit">Unit/House Number:</label>
            <input type="number" id="unit" name="unit"placeholder="Enter your House Number"  required>

            <label for="state">State:</label>
            <input type="text" id="state" name="state" placeholder="Enter your State"  required>

            <label for="city">City:</label>
            <input type="text" id="city" name="city" placeholder="Enter your city"  required>

            <div class="form-group">
                <label for="postcode">Postcode</label>
                <input type="number" id="postcode" name="postcode" placeholder="Enter your postcode" required>
            </div>

            <br>
            <h2>Payment Method</h2>
            <label>
                <input type="radio" name="payment_method" value="Online Banking" required> Bank Transfer
            </label>
            <label>
                <input type="radio" name="payment_method" value="E-Wallet" required> Touch 'n Go
            </label>
            <label>
                <input type="radio" name="payment_method" value="Credit Card" required> Credit Card
            </label>

            <br>
            <button type="submit" class="btn">Paid</button>
        </form>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const postcodeInput = document.getElementById("postcode");

            postcodeInput.addEventListener("input", function () {
                // Remove any non-numeric characters
                this.value = this.value.replace(/[^0-9]/g, "");
            });

            const form = document.getElementById("paymentForm");
            form.addEventListener("submit", function (e) {
                if (postcodeInput.value === "") {
                    e.preventDefault();
                    alert("Postcode is required and must be numeric.");
                }
            });
        });
    </script>
</body>
</html>