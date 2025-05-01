<?php
session_start();
require_once '../classes/product.php'; // Include your Product class
require_once '../classes/order.php';   // Include your Order class
$product = new Product();
$total = 0;

// Check if the user is coming from the product details page
if (isset($_GET['product_id'], $_GET['size'], $_GET['quantity'], $_GET['colour'])) {
    $productID = intval($_GET['product_id']);
    $size = htmlspecialchars($_GET['size']);
    $quantity = intval($_GET['quantity']);
    $colour = htmlspecialchars($_GET['colour']);

    // Fetch product details
    $productData = $product->getAProduct($productID);
    $variantID = $product->getProductVariantsID($productID, $size);
    $price = $productData['price'] ?? 0;
    $subtotal = $price * $quantity;
    $total += $subtotal;
} else {
    // Redirect to cart if no product is selected
    header("Location: /Web_Application/cart.php");
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
        <p class="total-price"><strong>Total Price:</strong> RM<?php echo number_format($total, 2); ?></p>
        <br>
        <form id="paymentForm" action="processPayment.php" method="POST" style="padding-left: 40px; padding-right: 40px;">
            <input type="hidden" name="product_id" value="<?php echo $productID; ?>">
            <input type="hidden" name="size" value="<?php echo $size; ?>">
            <input type="hidden" name="quantity" value="<?php echo $quantity; ?>">
            <input type="hidden" name="colour" value="<?php echo $colour; ?>">
            <input type="hidden" name="total" value="<?php echo $subtotal; ?>">

            <h2>Shipping Address</h2>
            <label for="unit">Unit/House Number:</label>
            <input type="text" id="unit" name="unit" placeholder="Enter your House Number" required>

            <label for="state">State:</label>
            <input type="text" id="state" name="state" placeholder="Enter your State" required>

            <label for="city">City:</label>
            <input type="text" id="city" name="city" placeholder="Enter your City" required>

            <div class="form-group">
                <label for="postcode">Postcode:</label>
                <input type="text" id="postcode" name="postcode" placeholder="Enter your Postcode" required>
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
            <button type="submit" class="btn">Pay Now</button>
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