<?php
session_start();
include 'classes/product.php'; // Include your Product class

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
    
    <link rel="stylesheet" href="style/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css">
    <link rel="stylesheet" href="style/product.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="checkout-container">
        <h1 style="font-size:30px;padding-left:40px; padding-top:20px;">Your Cart</h1>
        <?php if (empty($cart)): ?>
            <p style="padding-left: 40px; padding-top: 20px;">Your cart is empty.</p>
        <?php else: ?>
            
        <table style="width:100%; margin:20px; border-collapse: collapse;">
            <thead style="border-bottom: 1px solid #000;">
            <tr>
                <th style="text-align:left; width:40%; padding:25px;">Product</th>
                <th style="text-align:center; vertical-align:middle; padding:10px;">Quantity</th>
                <th style="text-align:center; vertical-align:middle; padding:10px;">Unit Price (RM)</th>
                <th style="text-align:center; vertical-align:middle; padding:10px;">Subtotal (RM)</th>
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
                            <img src="img/<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($productName); ?>" class="product-img">
                            <div class="product-info" style="margin-top: 10px;">
                                <p style="margin: 10px 0;font-weight:bold;"><?php echo htmlspecialchars($productName); ?></p>
                                <p style="margin: 10px 0;">RM<?php echo number_format($price, 2); ?></p>
                                <p style="margin: 10px 0;">Size: <?php echo htmlspecialchars($item['size'] ?? 'N/A'); ?></p>
                                <p style="margin: 10px 0;">Colour: <?php echo htmlspecialchars($productData['colour'] ?? 'N/A'); ?></p>
                            </div>
                        </div>
                    </td>
                    <td style="text-align:center;">
                        <form method="POST" action="update_cart.php" style="display:inline-flex; align-items:center; justify-content:center;">
                            <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                            <input type="hidden" name="size" value="<?php echo $item['size']; ?>">
                            <input type="hidden" id="max-stock-<?php echo $item['product_id']; ?>-<?php echo $item['size']; ?>" value="<?php echo $maxStock; ?>">
                            <button type="submit" name="action" value="decrease" class="quantity-btn">-</button>
                            <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" class="quantity-input" style="width:40px; text-align:center; margin: 0 5px;">
                            <button type="submit" name="action" value="increase" class="quantity-btn">+</button>
                        </form>
                    </td>
                    <td style="text-align:center;"><?php echo number_format($price, 2); ?></td>
                    <td style="text-align:center;"><?php echo number_format($subtotal, 2); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p style="padding-right:80px;padding-bottom:80px;text-align:right;"><strong>Total: RM<?php echo number_format($total, 2); ?></strong></p>

        <!-- Add Checkout Button -->
        <div style="text-align: right; padding-right: 80px; padding-bottom: 40px;">
            <?php if (!empty($cart)): ?>
                <button class="checkout-btn" onclick="proceedToCheckout()">
                    Checkout
                </button>
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
                        alert("Maximum stock reached!");
                    }
                });
            });

            function proceedToCheckout() {
                window.location.href = '/Web_Application/payment/cart_payment.php';
            }
        </script>
        
        <?php endif; ?>
    </div>
</body>
</html>
