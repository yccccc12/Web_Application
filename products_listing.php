<?php
// Detect gender from query parameter (`?gender=men` or `?gender=women`).
$gender = isset($_GET['gender']) ? ucfirst(strtolower($_GET['gender'])) : 'All';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $gender; ?> Products</title>

    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="style/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css">
    <link rel="stylesheet" href="style/product.css">
</head>
<body>

    <?php include 'includes/header.php'; ?>

    <section class="products-container">
        <!-- Filter Menu Icon (visible on mobile) -->
        <div class="filter-menu">
            <i class="ri-filter-line filter-icon" aria-label="Menu" onclick="toggleFilterMenu()"> <span>Filter</span></i>
            <span class="product-count" class="product-count"></span>
        </div>

        <!-- Transparent Overlay -->
        <div class="filter-overlay-bg" id="filterOverlayBg" onclick="toggleFilterMenu()"></div>

        <!-- Sliding Filter Menu -->
        <div class="filter-overlay" id="filterOverlay">
            <div class="filter-header">
                <h2>Filter and sort</h2>
                <i class="ri-close-line close-icon" onclick="toggleFilterMenu()"></i>
            </div>

            <div class='filter-section'>
                <div class="filter">
                    <span class='label'>Color:</span>
                    <select class="color-filter" onchange="applyFilter()"></select>
                </div>
                <div class="filter">
                    <span class='label'>Size:</span>
                    <select class="size-filter" onchange="applyFilter()"></select>
                </div>
                <div class="filter">
                <span class="label">Sort by:</span>
                <select class="sort-filter" onchange="applyFilter()">
                    <option value="featured">Featured</option>
                    <option value="name-asc">Name (A-Z)</option>
                    <option value="name-desc">Name (Z-A)</option>
                    <option value="price-asc">Price (Low to High)</option>
                    <option value="price-desc">Price (High to Low)</option>
                </select>
                </div>
            </div>


            <div class="filter-footer">
                <button class="clear-btn" onclick="clearFilters()">Remove all</button>
                <button class="apply-btn" onclick="toggleFilterMenu()">Apply</button>
            </div>
        </div>

        <!-- Filter Menu for desktop view -->
        <div class='filter-container'>
            <span class='filter-btn'>Filter :</span>
            <select class="color-filter" onchange="applyFilter()"></select>
            <select class="size-filter" onchange="applyFilter()"></select>

            <div class="sort-container">
                <span class="sort-btn">Sort by :</span>
                <select class="sort-filter" onchange="applyFilter()">
                    <option value="featured">Featured</option>
                    <option value="name-asc">Name (A-Z)</option>
                    <option value="name-desc">Name (Z-A)</option>
                    <option value="price-asc">Price (Low to High)</option>
                    <option value="price-desc">Price (High to Low)</option>
                </select>
                <span class="product-count" class="product-count"></span>
            </div>
        </div>

        <div class="products-section"></div>
    </section>

    <?php include 'includes/footer.php';?>

    <script src="js/load_products.js"></script>
</body>
</html>
