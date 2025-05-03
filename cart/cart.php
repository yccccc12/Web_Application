<?php
session_start();
include '../classes/product.php'; // Include your Product class

// Retrieve cart from session
$cart = $_SESSION['cart'] ?? [];
$total = 0;
$product = new Product();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="../style/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css">
    <link rel="stylesheet" href="../style/product.css">
    <style>
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <div class="checkout-container">
    <h1 class="cart-heading">Your Cart</h1>

    <?php if (empty($cart)): ?>
        <p class="empty-cart-message">Your cart is empty.</p>
    <?php else: ?>
        
    <table class="cart-table">
        <thead>
        <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Unit Price (RM)</th>
            <th>Subtotal (RM)</th>
        </tr>
        </thead>
        <tbody>
            <?php foreach ($cart as $item):
                $productData = $product->getAProduct($item['product_id']);
                $productName = $productData['name'] ?? 'Unknown Product';
                $price = $productData['price'] ?? 0;
                $image = $productData['images'][0]['image_url'] ?? 'default.jpg';
                $subtotal = $price * $item['quantity'];
                $total += $subtotal;
                $maxStock = $product->getMaxStock($item['product_id'], $item['size']);
            ?>
            <tr>
                <td class="cart-item">
                    <div class="product-display">
                        <img src="<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($productName); ?>" class="product-img">
                        <div class="product-info">
                            <p><?php echo htmlspecialchars($productName); ?></p>
                            <p>RM<?php echo number_format($price, 2); ?></p>
                            <p>Size: <?php echo htmlspecialchars($item['size'] ?? 'N/A'); ?></p>
                            <p>Colour: <?php echo htmlspecialchars($productData['colour'] ?? 'N/A'); ?></p>
                        </div>
                    </div>
                </td>
                <td>
                    <form method="POST" action="update_cart.php" class="quantity-form">
                        <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                        <input type="hidden" name="size" value="<?php echo $item['size']; ?>">
                        <input type="hidden" id="max-stock-<?php echo $item['product_id']; ?>-<?php echo $item['size']; ?>" value="<?php echo $maxStock; ?>">
                        <button type="submit" name="action" value="decrease" class="quantity-btn">-</button>
                        <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" class="quantity-input" readonly>
                        <button type="submit" name="action" value="increase" class="quantity-btn">+</button>
                        <button type="submit" name="action" value="delete" class="quantity-btn delete">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </form>
                    <div id="stock-message-<?php echo $item['product_id']; ?>-<?php echo $item['size']; ?>" style="display:none; color: red;"></div>
                </td>
                <td style="text-align:center;"><?php echo number_format($price, 2); ?></td>
                <td style="text-align:center;"><?php echo number_format($subtotal, 2); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p class="cart-total"><strong>Total: RM<?php echo number_format($total, 2); ?></strong></p>

    <!-- Checkout Button -->
    <div class="checkout-container">
        <?php if (!empty($cart)): ?>
            <button class="checkout-btn" onclick="proceedToCheckout()">Checkout</button>
        <?php endif; ?>
    </div>

        <script>
            document.querySelectorAll("form").forEach(form => {
                const increaseBtn = form.querySelector("button[value='increase']");
                const quantityInput = form.querySelector("input[name='quantity']");
                const maxStockInput = form.querySelector("input[id^='max-stock']"); // ^='max-stock' means select all item start with 'max-stock'

                increaseBtn.addEventListener("click", function(event) {
                    const currentQty = parseInt(quantityInput.value);
                    const maxStock = parseInt(maxStockInput.value);

                    if (currentQty >= maxStock) {
                        event.preventDefault();
                        const stockMessage = document.getElementById("stock-message-" + maxStockInput.id.split('-')[2] + "-" + maxStockInput.id.split('-')[3]);
                        stockMessage.innerHTML = '<i class="ri-error-warning-fill"></i> Maximum stock reached!';
                        stockMessage.style.fontSize = "14px";
                        stockMessage.style.display = "block";
                    }
                });
            });

            function proceedToCheckout() {
                window.location.href = '/Web_Application/payment/cart_payment.php';
            }
        </script>
        
        <?php endif; ?>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>