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
        <div class='filter-container'>
            <span class='filter-btn'>Filter :</span>
            <select id="color-filter" onchange="applyFilter()"></select>
            <select id="size-filter" onchange="applyFilter()"></select>

            <div class="sort-container">
                <span class="sort-btn">Sort by :</span>
                <select id="sort-filter" onchange="applyFilter()">
                    <option value="featured">Featured</option>
                    <option value="name-asc">Name (A-Z)</option>
                    <option value="name-desc">Name (Z-A)</option>
                    <option value="price-asc">Price (Low to High)</option>
                    <option value="price-desc">Price (High to Low)</option>
                </select>
                <span id="product-count" class="product-count"></span>
            </div>
        </div>

        <div class="products-section"></div>
    </section>

    <?php include 'includes/footer.php';?>

    <script>
        // Get gender from URL (`?gender=men` or `?gender=women`)
        const urlParams = new URLSearchParams(window.location.search);
        const gender = urlParams.get("gender") || "all";

        // Fetch and load products dynamically
        fetchProducts().then(products => {
            loadProducts(products, gender);
            loadFilters(products, gender);
        });

        function fetchProducts() {
            return fetch("get_products.php")
                .then(response => response.json())
                .catch(error => {
                    console.error("Fetch error:", error);
                    return [];
                });
        }

        // Load products into container
        function loadProducts(products, category = "all") {
            let container = document.querySelector(".products-section");
            container.innerHTML = "";

            // Filter products dynamically based on category
            const filteredProducts = category === "all" 
                ? products 
                : products.filter(product => product.category.toLowerCase()[0] === category.toLowerCase()[0]);
            
            filteredProducts.forEach(product => {
                let productHTML = `
                    <div class="product" data-color="${product.colour}" data-size='${JSON.stringify(product.variants.map(variant => variant.size))}'>
                        <a href="product_details.php?id=${product.productID}">
                            <img src="img/${product.images[0]['image_url']}" alt="${product.name}">
                        </a>
                        <h3>${product.name}</h3>
                        <p>RM${product.price}</p>
                    </div>`;
                container.innerHTML += productHTML;
            });

            document.getElementById("product-count").textContent = filteredProducts.length + " products";
        }

        // Load filters into container
        function loadFilters(products, category = "all") {
            let colorFilter = document.getElementById("color-filter");
            let sizeFilter = document.getElementById("size-filter");

            let filteredProducts = category === "all" 
                ? products 
                : products.filter(product => product.category.toLowerCase()[0] === category.toLowerCase()[0]);

            colorFilter.innerHTML = '<option value="All">All Colors</option>';
            sizeFilter.innerHTML = '<option value="All">All Sizes</option>';
            
            let colors = [...new Set(filteredProducts.map(p => p.colour))];
            let sizes = [...new Set(filteredProducts.flatMap(p => p.variants.map(variant => variant.size)))];
            
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
            let selectedSort = document.getElementById("sort-filter").value;
            let products = document.querySelectorAll(".product");

            let count = 0;

            let sortedProducts = Array.from(products).map(product => {
                let productColor = product.getAttribute("data-color") || "All";
                let productSize = product.getAttribute("data-size") || "[]";

                let productName = product.querySelector("h3").textContent.toLowerCase();
                let productPrice = parseFloat(product.querySelector("p").textContent.replace("RM", "").trim());

                // Parse sizes from JSON string
                let sizes = JSON.parse(productSize);
                let colorMatch = (selectedColor === "All" || productColor === selectedColor);
                let sizeMatch = (selectedSize === "All" || sizes.includes(selectedSize));

                let isVisible = colorMatch && sizeMatch;
                product.style.display = isVisible ? "block" : "none";

                if (isVisible) count++; // Count only visible products

                return { product, name: productName, price: productPrice, isVisible };
            });


            // Sort the visible products
            sortedProducts.sort((a, b) => {
                if (selectedSort === "name-asc") return a.name.localeCompare(b.name);
                if (selectedSort === "name-desc") return b.name.localeCompare(a.name);
                if (selectedSort === "price-asc") return a.price - b.price;
                if (selectedSort === "price-desc") return b.price - a.price;
                return 0;
            });

            // Apply sorting using CSS order
            sortedProducts.forEach((item, index) => {
                if (item.isVisible) {
                    item.product.style.order = index;
                }
            });

            // Update product count
            document.getElementById("product-count").textContent = count + " products";
        }
    </script>
</body>
</html>
