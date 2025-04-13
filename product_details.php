<?php
// Include database connection
include 'classes/product.php';

// Get product ID from URL
$productID = isset($_GET['id']) ? intval($_GET['id']) : 0;

$product = new Product();
$productData = $product->getAProduct($productID);

if (!$productData) {
    header("Location: products.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($productData['name']); ?> - Product Details</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="style/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css">
    <link rel="stylesheet" href="style/product.css">

</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="product-details-container"> 
        <div class="product-details">
            <?php if (!empty($productData["images"])): ?>
                <?php foreach ($productData["images"] as $image): ?>
                    <img src="img/<?php echo htmlspecialchars($image['image_url']); ?>" alt="<?php echo htmlspecialchars($productData['name']); ?>">
                <?php endforeach; ?>
            <?php else: ?>
                <img src="img/default.jpg" alt="Default product image">
            <?php endif; ?>
        </div>

        <div class="product-info">
            <h1 class="product-title"><?php echo htmlspecialchars($productData['name']); ?></h1>
            <p class="product-price">RM<?php echo number_format($productData['price'], 2); ?></p>

            <p class="size-label">Size</p>
            <div class="size-options">
                <?php foreach ($productData['variants'] as $variant): ?>
                    <?php if ($variant['stock'] > 0): ?>
                        <button class="size-btn" 
                            data-size="<?php echo htmlspecialchars($variant['size']); ?>" 
                            data-stock="<?php echo $variant['stock']; ?>" 
                            style="margin-bottom:10px;">
                            <?php echo htmlspecialchars($variant['size']); ?>
                        </button>

                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <p class="stock-display" id="stockDisplay"></p>

            <p class="colour-label">Colour</p>
            <div class="colour-option">
                <button class="colour-btn active">
                    <?php echo htmlspecialchars($productData['colour']); ?>
                </button>
            </div>

            <div class="description">
                <p class="description-label">Description</p>
                <p class="product-description"><?php echo htmlspecialchars($productData['description']); ?></p>
            </div>

            <div class="quantity-section-container">
                <label for="quantity">Quantity</label>
                <div class="quantity-wrapper">
                    <div class="quantity-box">
                        <button type="button" class="quantity-btn decrease">-</button>
                        <input type="number" id="quantity" name="quantity" value="1" min="1" class="quantity-input" readonly>
                        <button type="button" class="quantity-btn increase">+</button>
                    </div>
                </div>
            </div>

            <button class="add-to-cart-btn">Add to Cart</button>
            <button class="checkout-btn">Checkout</button>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const sizeButtons = document.querySelectorAll('.size-btn');
        const quantityInput = document.getElementById('quantity');
        const decreaseBtn = document.querySelector('.quantity-btn.decrease');
        const increaseBtn = document.querySelector('.quantity-btn.increase');            
        const stockDisplay = document.getElementById('stockDisplay');

        let maxStock = 1;

        if (sizeButtons.length > 0) {
            const firstBtn = sizeButtons[0];
            firstBtn.classList.add('active');
            maxStock = parseInt(firstBtn.dataset.stock);
            quantityInput.max = maxStock;
            quantityInput.value = 1;
            stockDisplay.textContent = `In stock: ${maxStock}`;
        }

        sizeButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                sizeButtons.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                maxStock = parseInt(btn.dataset.stock);
                quantityInput.max = maxStock;
                quantityInput.value = 1;
                stockDisplay.textContent = `In stock: ${maxStock}`;
            });
        });

        increaseBtn.addEventListener('click', () => {
            let current = parseInt(quantityInput.value);
            if (current < maxStock) {
                quantityInput.value = current + 1;
            }
        });

        decreaseBtn.addEventListener('click', () => {
            let current = parseInt(quantityInput.value);
            if (current > 1) {
                quantityInput.value = current - 1;
            }
        });

        // Prevent typing manually
        quantityInput.addEventListener('keydown', (e) => e.preventDefault());

        // Add to Cart & Checkout
        const addToCartBtn = document.querySelector('.add-to-cart-btn');
        const checkoutBtn = document.querySelector('.checkout-btn');
        const colour = "<?php echo htmlspecialchars($productData['colour']); ?>";

        addToCartBtn.addEventListener('click', () => {
            const activeSizeBtn = document.querySelector('.size-btn.active');
            if (!activeSizeBtn) {
                alert('Please select a size before adding to cart.');
                return;
            }

            const size = activeSizeBtn.dataset.size;
            const quantity = parseInt(quantityInput.value);

            fetch('add_to_cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    product_id: <?php echo $productID; ?>,
                    size: size,
                    quantity: quantity,
                    colour: colour,
                }),
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while adding to cart.');
            });
        });

        checkoutBtn.addEventListener('click', () => {
            window.location.href = 'check_out.php';
        });
    });
    </script>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
