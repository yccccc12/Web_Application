<?php
// Include database connection
include 'classes/product.php';

// Get product ID from URL
$productID = isset($_GET['id']) ? intval($_GET['id']) : 0;

$product = new Product();
$productData = $product -> getAProduct($productID);
print_r($productData);
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
    <style>
        .product-details-container{
            display: flex;
        }
        .product-details{
            display:flex;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="product-details-container"> 
        <div class="product-details">
            <img src="img/<?php echo htmlspecialchars($productData["images"][0]['image_url']); ?>" alt="<?php echo htmlspecialchars($productData['name']); ?>">
            <img src="img/<?php echo htmlspecialchars($productData["images"][1]['image_url']); ?>" alt="<?php echo htmlspecialchars($productData['name']); ?>">
        </div>
        <div class="product-info">
            <h1 class="product-title"><?php echo htmlspecialchars($productData['name']); ?></h1>
            <p class="product-price">RM<?php echo number_format($productData['price'], 2); ?></p>
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
                        <?php echo htmlspecialchars($productData['colour']); ?>
                    </button>
                </div>
            <div class="description">
                <p class="description-label">Description</p>
                <p class="product-description"><strong></strong> <?php echo htmlspecialchars($productData['description']); ?></p>
            </div>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>