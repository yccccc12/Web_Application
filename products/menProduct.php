<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Men Products</title>

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

    <!-- Breadcrumb Navigation
    <div class="breadcrumb"><a href="../index.php">Home</a> / <span style="color: black;">Men</span></div>
    -->

    <section class="products-container">
        <div class='filter-container'>
            <span class='filter-btn'>Filter :</span>

            <select id="color-filter" onchange="applyFilter()"></select>

            <select id="size-filter" onchange="applyFilter()"></select>        
        </div>

        <div class="products-section"></div>
    </section>
    <?php include '../includes/footer.php';?>

    <script>
    fetchProducts().then(function (products) {
        loadProducts(products, "Male");
        loadFilters(products, "Male");
    });

    function fetchProducts() {
        return fetch("../get_products.php")
            .then(function (response) {
                if (!response.ok) {
                    throw new Error("HTTP error! Status: " + response.status);
                }
                return response.json();
            })
            .catch(function (error) {
                console.error("Fetch error:", error);
                return [];
            });
    }

    // Load and display products based on gender category
    function loadProducts(products, category = "all") {
        let container = document.querySelector(".products-section");
        container.innerHTML = ""; // Clear previous content

        // Filter products based on category
        const filteredProducts = category === "all" ? 
            products : products.filter(product => product.category.toLowerCase()[0] === category.toLowerCase()[0]);

        // Render products
        filteredProducts.forEach(product => {
            let productHTML = `
                <div class="product" data-color="${product.colour}" data-size="${product.size}">
                    <img src="../img/${product.image_url}" alt="${product.name}">
                    <h3>${product.name}</h3>
                    <p>RM${product.price}</p>
                </div>`;
            container.innerHTML += productHTML;
        });
        // applyFilter(); // Apply filter after loading
    }

    function loadFilters(products, category="all"){
        let colorFilter = document.getElementById("color-filter");
        let sizeFilter = document.getElementById("size-filter");

        // Filter products based on gender category
        let filteredProducts = category === "all" 
            ? products 
            : products.filter(product => product.category.toLowerCase()[0] === category.toLowerCase()[0]);

        colorFilter.innerHTML = '<option value="All">All Colors</option>';
        sizeFilter.innerHTML = '<option value="All">All Sizes</option>';

        // Extract unique colors and sizes from filtered products
        let colors = [...new Set(filteredProducts.map(p => p.colour))];
        let sizes = [...new Set(filteredProducts.map(p => p.size))];

        colors.forEach(color => {
            colorFilter.innerHTML += `<option value="${color}">${color}</option>`;
        });

        sizes.forEach(size => {
            sizeFilter.innerHTML += `<option value="${size}">${size}</option>`;
        });
    }

    function applyFilter() {
        let selectedColor = document.getElementById("color-filter").value;
        let selectedSize = document.getElementById("size-filter").value;
        
        let products = document.querySelectorAll(".product");

        products.forEach(product => {
            let productColor = product.getAttribute("data-color") || "All";
            let productSize = product.getAttribute("data-size") || "All";

            let colorMatch = (selectedColor === "All" || productColor === selectedColor);
            let sizeMatch = (selectedSize === "All" || productSize === selectedSize);

            product.style.display = (colorMatch && sizeMatch) ? "block" : "none";
        });
    }
    </script>
</body>
</html>