<?php
// Include database connection
include '../classes/product.php';

// Start session to check login status
session_start();

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['user_id']);

// Get product ID from URL
$productID = isset($_GET['id']) ? intval($_GET['id']) : 0;
$size = isset($_GET['size']) ? $_GET['size'] : '';

$product = new Product();
$productData = $product->getAProduct($productID);
$variantID = $product->getProductVariantsID($productID, $size);

if (!$productData) {
    header("Location: products_listing.php");
    exit;
}

$cartQuantities = [];

if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        if ($item['product_id'] == $productID) {
            $cartQuantities[$item['size']] = $item['quantity'];
        }
    }
}

$reviews = $product->getRecentReviews($productID);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($productData['name']); ?> - Product Details</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="../style/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css">
    <link rel="stylesheet" href="../style/product.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
    .reviews-section {
      max-width: 1200px;
      margin: auto;
    }

    .reviews-header {
      margin-bottom: 30px;
      text-align: center;
    }

    .reviews-header h2 {
      font-size: 2rem;
      margin: 40px 0 10px;
    }
    .stars {
      font-size: 1.5rem;
    }

    .reviews-carousel {
      display: flex;
      overflow-x: auto;
      scroll-snap-type: x mandatory;
      -webkit-overflow-scrolling: touch;
      gap: 20px;
      padding: 20px 0;
    }

    .review-card {
      flex: 0 0 300px;
      background: #f9f9f9;
      border-radius: 10px;
      padding: 20px;
      scroll-snap-align: center;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    .review-size{
        font-size: 0.9rem;
        color: #666;
    }

    .review-title {
      font-weight: bold;
      margin: 10px 0;
    }

    .review-text {
      font-size: 0.95rem;
      margin: 10px 0;
      color: #333;
    }

    .arrow {
      font-size: 2rem;
      color: #ccc;
      cursor: pointer;
      user-select: none;
      margin: 0 10px;
    }

    .carousel-container {
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 50px;
    }

    .review-rating {
      font-size: 1.2rem;
      margin-bottom: 5px;
    }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="product-details-container"> 
        <div class="product-details">
            <img src="<?php echo htmlspecialchars($productData["images"][0]['image_url']); ?>" alt="<?php echo htmlspecialchars($productData['name']); ?>">
            <img src="<?php echo htmlspecialchars($productData["images"][1]['image_url']); ?>" alt="<?php echo htmlspecialchars($productData['name']); ?>">
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
                <button class="colour-btn">
                    <?php echo htmlspecialchars($productData['colour']); ?>
                </button>
            </div>

            <div class="description">
                <p class="description-label">Description</p>
                <p class="product-description"><strong></strong> <?php echo htmlspecialchars($productData['description']); ?></p>
            </div>

            <div class="quantity-section-container">
                <label for="quantity">Quantity</label>
                <div class="quantity-wrapper">
                    <div class="quantity-box">
                        <button type="button" class="quantity-btn decrease">-</button>
                        <input type="text" id="quantity" name="quantity" value="1" class="quantity-input">
                        <button type="button" class="quantity-btn increase">+</button>
                    </div>
                </div>
            </div>
            <div id="stock-message" style="display: none; color: red;"></div>
            <button class="add-to-cart-btn">Add to Cart</button>
            <button class="checkout-btn">Checkout</button>
        </div>
    </div>
    <div class="reviews-section">
        <div class="reviews-header">
            <h2>Customer review</h2>
            <p>from <?php echo htmlspecialchars(count($reviews))?> reviews</p>
        </div>
        <div class="carousel-container">
            <div class="arrow" onclick="scrollCarousel(-1)">&#10094;</div>
            <div class="reviews-carousel" id="carousel">
                <?php foreach ($reviews as $review): ?>
                    <div class="review-card">
                    <div class="review-rating">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="ri-star<?= $i <= $review['rating'] ? '-fill' : '-line' ?>"></i>
                        <?php endfor; ?>
                    </div>
                    <div class="review-size">Size: <?= htmlspecialchars($review['size']) ?></div>
                    <div class="review-text"><?= htmlspecialchars($review['review']) ?></div>
                    <div class="review-title"><?= htmlspecialchars($review['userName']) ?></div>
                    </div>
                <?php endforeach; ?>

                <?php if (empty($reviews)): ?>
                    <p>No reviews found.</p>
                <?php endif; ?>
            </div>
            <div class="arrow" onclick="scrollCarousel(1)">&#10095;</div>
        </div>
    </div>
    
    <?php include '../includes/footer.php'; ?>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const sizeButtons = document.querySelectorAll('.size-btn');
        const quantityInput = document.getElementById('quantity');
        const decreaseBtn = document.querySelector('.quantity-btn.decrease');
        const increaseBtn = document.querySelector('.quantity-btn.increase');            
        const stockDisplay = document.getElementById('stockDisplay');
        const stockMessage = document.getElementById('stock-message');
        const addToCartBtn = document.querySelector('.add-to-cart-btn');
        const checkoutBtn = document.querySelector('.checkout-btn');
        const isLoggedIn = <?php echo json_encode($isLoggedIn); ?>;
        const cartQuantity = <?php echo json_encode($cartQuantities, JSON_HEX_TAG); ?>;
        let maxStock = 1;

        if (sizeButtons.length > 0) {
            const firstBtn = sizeButtons[0];
            firstBtn.classList.add('active');
            maxStock = parseInt(firstBtn.dataset.stock);
            quantityInput.max = maxStock;
            quantityInput.value = 1;
            stockDisplay.textContent = `In stock: ${maxStock}`;
        } else {
            stockDisplay.textContent = "No size available.";
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

        quantityInput.addEventListener('input', () => {
            let current = parseInt(quantityInput.value);
            if (!isNaN(current)) {
            if (current < 1) {
                quantityInput.value = 1; // Reset to minimum value
            } else if (current > maxStock) {
                quantityInput.value = maxStock; // Reset to maximum stock
            }
            }
        });

        quantityInput.addEventListener('blur', () => {
            if (quantityInput.value === '') {
            quantityInput.value = 1; // Reset to minimum value if input is empty
            }
        });

        // Add to Cart & Checkout
        addToCartBtn.addEventListener('click', () => {
            if (!isLoggedIn) {
                // Redirect to login page if not logged in
                window.location.href = '/Web_Application/user/login.php';
                return;
            }

            const activeSizeBtn = document.querySelector('.size-btn.active');
            if (!activeSizeBtn) {
                const message = '<i class="ri-error-warning-fill"></i> No stock available.';
                stockMessage.innerHTML = message;
                stockMessage.style.display = 'block';
                return;
            }

            const size = activeSizeBtn.dataset.size;
            const quantity = parseInt(quantityInput.value);
            const maxStock = parseInt(activeSizeBtn.dataset.stock);
            const alreadyInCart = cartQuantity[size] || 0;

            if(alreadyInCart === maxStock){
                // When the cart has exactly the remaining stock
                const message = '<i class="ri-error-warning-fill"></i> You have reached the maximum stock.';

                stockMessage.innerHTML = message;
                stockMessage.style.display = 'block';

                return;
            } else if (alreadyInCart + quantity > maxStock) {
                // When the cart has more than the remaining stock
                const remaining = maxStock - alreadyInCart;
                const message = `<i class="ri-error-warning-fill"></i> You already have ${alreadyInCart} of size ${size} in your cart. You can only add ${remaining > 0 ? remaining : 0} more.`;

                stockMessage.innerHTML = message;
                stockMessage.style.display = 'block';

                return;
            } 
                        
            console.log('Sending to backend:', {
                product_id: <?php echo $productID; ?>,
                size: size,
                quantity: quantity,
                colour: "<?php echo htmlspecialchars($productData['colour']); ?>",

            });
           
            // Send the data to add to cart.php
            fetch('../cart/add_to_cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    product_id: <?php echo $productID; ?>,
                    size: size,
                    quantity: quantity,
                    colour: "<?php echo htmlspecialchars($productData['colour']); ?>",

                }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message); // Show the available stock if the requested quantity exceeds it
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('The quantity is not available.');
            });
        });

        checkoutBtn.addEventListener('click', () => {
            if (!isLoggedIn) {
                // Redirect to login page if not logged in
                window.location.href = '/Web_Application/user/login.php';
                return;
            }

            const activeSizeBtn = document.querySelector('.size-btn.active');
            if (!activeSizeBtn) {
                const message = '<i class="ri-error-warning-fill"></i> No stock available.';
                stockMessage.innerHTML = message;
                stockMessage.style.display = 'block';
                return;
            }

            const size = activeSizeBtn.dataset.size;
            const quantity = parseInt(quantityInput.value);

            // Redirect to payment.php with product details
            const url = `/Web_Application/payment/payment.php?product_id=${<?php echo $productID; ?>}&size=${size}&quantity=${quantity}&colour=${"<?php echo htmlspecialchars($productData['colour']); ?>"}`;
            window.location.href = url;
        });
    });

    function scrollCarousel(direction) {
    const carousel = document.getElementById('carousel');
    const scrollAmount = 320; // Adjust this to match card width + margin
    carousel.scrollBy({ left: scrollAmount * direction, behavior: 'smooth' });
    }
    </script>
</body>
</html>