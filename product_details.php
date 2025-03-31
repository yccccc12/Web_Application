<?php
// Include database connection
include 'db_connect.php';

// Get product ID from URL
$productID = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch product details from database
$query = "SELECT * FROM products WHERE productID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $productID);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

// If product not found, redirect to products page
if (!$product) {
    header("Location: products.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Product Details</title>
    
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
        <img src="img/<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
        <img src="img/<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
        </div>

        <div class="product-info">
            <h1 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h1>
            <p class="product-price">RM<?php echo number_format($product['price'], 2); ?></p>
            <p class="size-label">Size</p>
                <div class="size-options">
                    <button class="size-btn" data-size="S">S</button>
                    <button class="size-btn" data-size="M">M</button>
                    <button class="size-btn" data-size="L">L</button>
                    <button class="size-btn" data-size="XL">XL</button>
                </div>
                <p class="colour-label">Colour</p>
                    <div class="colour-option">
                    <button class="colour-btn">
                        <?php echo htmlspecialchars($product['colour']); ?>
                    </button>
                </div>
            <div class="description">
                <p class="description-label">Description</p>
                <p class="product-description"><strong></strong> <?php echo htmlspecialchars($product['description']); ?></p>

</div>
           

            <!--<a href="checkout.php?id=<?php echo $product['productID']; ?>" class="btn">Buy Now</a>
            <a href="index.php" class="btn-secondary">Back to Shop</a>-->
        </div>
    </div>

    

    <?php include 'includes/footer.php'; ?>

</body>
</html>