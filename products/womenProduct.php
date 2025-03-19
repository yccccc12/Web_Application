<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Womens Products</title>

    <!-- Google Font: Figtree -->
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- REMIXICONS -->
    <link rel="stylesheet" href="../style/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css">
    <link rel="stylesheet" href="../style/product.css">

</head>
<body>
    <?php include '../includes/header.php'; ?>
    <!-- Breadcrumb Navigation -->
    <div class="breadcrumb"><a href="../index.php">Home</a> / <span>Women</span></div>

    <div class='filter-container'>
        <span class='filter-btn'>Filter :</span>

        <select id="color-filter" onchange="applyFilter()">
            <option value="All">All Colors</option>
            <option value="Black">Black</option>
            <option value="Blue">Blue</option>
            <option value="Green">Green</option>
        </select>
            <select id="size-filter" onchange="applyFilter()">
            <option value="All">All size</option>
            <option value="S">S</option>
            <option value="M">M</option>
            <option value="L">L</option>
            <option value="XL">XL</option>
        </select>        
    </div>


    <section class="products-section">
    <div class="product" data-color="Black" data-size="M">
                <img src="../img/item5.jpg" alt="Women Long-Sleeve Sweatshirt">
                <h3>Women Long-Sleeve Sweatshirt</h3>
                <p>RM119.90</p>
            </div>
            <div class="product"data-color="Blue" data-size="S">
                <img src="../img/item6.jpg" alt="Women Oversized Denim Jacket">
                <h3>Women Oversized Denim Jacket</h3>
                <p>RM169.90</p>
            </div>
            <div class="product"data-color="Black" data-size="L">
                <img src="../img/item7.jpg" alt="Women Crop Polo Tee">
                <h3>Women Crop Polo Tee</h3>
                <p>RM109.90</p>
            </div>
            <div class="product"data-color="Black" data-size="XL">
                <img src="../img/item8.jpg" alt="Women Polo Tee">
                <h3>Women Polo Tee</h3>
                <p>RM89.90</p>
            </div>

    </section>
    <?php include '../includes/footer.php';?>
    
    <script>
    function applyFilter() {
        console.log("applyFilter is running"); // Debugging step

        let selectedColor = document.getElementById("color-filter").value;
        let selectedSize = document.getElementById("size-filter").value;

        let products = document.querySelectorAll(".product");

        products.forEach(product => {
            let productColor = product.getAttribute("data-color") || "All";
            let productSize = product.getAttribute("data-size") || "All";

            let colorMatch = (selectedColor === "All" || productColor === selectedColor);
            let sizeMatch = (selectedSize === "All" || productSize === selectedSize);

            if (colorMatch && sizeMatch) {
                product.style.display = "block";
            } else {
                product.style.display = "none";
            }
        });
    }

    document.addEventListener("DOMContentLoaded", function () {
        console.log("DOM Loaded"); // Debugging step

        document.getElementById("color-filter").onchange = applyFilter;
        document.getElementById("size-filter").onchange = applyFilter;
    });
</script>

</body>
</html>