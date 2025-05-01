const urlParams = new URLSearchParams(window.location.search);
// Get the category from the URL, default to "all" if not present
const gender = urlParams.get("gender") || "all"; 

fetchProducts().then(products => {
    const productSection = document.querySelector(".products-section");
    if (productSection) {
        loadProducts(products, gender);

        // Only call filter loading on products_listing.php
        if (window.location.pathname.includes("products_listing.php")) {
            loadFilters(products, gender);
        }
    }
});

// Fetch products
function fetchProducts() {
    const endpoint = window.location.pathname.includes("products_listing.php") 
        ? "get_products" 
        : "product/get_products.php";

    return fetch(endpoint)
        .then(response => response.json())
        .catch(error => {
            console.error("Fetch error:", error);
            return [];
        });
}

// Load products
function loadProducts(products, category = "all") {
    let container = document.querySelector(".products-section");
    container.innerHTML = "";

    // Filter products dynamically based on category by checking the first letter of the category and the first letter of the product category
    let filteredProducts = category === "all" 
        ? products 
        : products.filter(product => product.category.toLowerCase()[0] === category.toLowerCase()[0]);
    
    if(category === "all"){
        // Limit to first 8 products to be displayed in index.php
        filteredProducts = filteredProducts.slice(0, 8);
    }

    // Load products into the container
    filteredProducts.forEach(product => {
        /* If the current page is 'products_listing.php', it links to 'product_details.php' in the same directory. 
           Otherwise, it links to 'product/product_details.php'.
        */
        let productHTML = `
            <div class="product" data-color="${product.colour}" data-size='${JSON.stringify(product.variants.map(variant => variant.size))}'>
            <a href="${window.location.pathname.includes('products_listing.php') ? 'product_details.php?id=' + product.productID : 'product/product_details.php?id=' + product.productID}">
                <img src="/Web_Application/img/${product.images[0]['image_url'].replace('../img/', '')}" alt="${product.name}">
            </a>
            <h3>${product.name}</h3>
            <p>RM${product.price}</p>
            </div>`;
        container.innerHTML += productHTML;
    });
    
    // To ensure it will executed on products-listing.php
    const productCount = document.getElementsByClassName("product-count");
    if(productCount){
        productCount[0].textContent = filteredProducts.length + " products";
        productCount[1].textContent = filteredProducts.length + " products";
    }
}

// Load filters into container
function loadFilters(products, category = "all") {
    // The two variables below are array of HTML, index 0 for mobile, index 1 for desktop
    let colorFilter = document.getElementsByClassName("color-filter");
    let sizeFilter = document.getElementsByClassName("size-filter");

    let filteredProducts = category === "all" 
        ? products 
        : products.filter(product => product.category.toLowerCase()[0] === category.toLowerCase()[0]);

    colorFilter.innerHTML = '<option value="All">All Colors</option>';
    sizeFilter.innerHTML = '<option value="All">All Sizes</option>';
    
    let colors = [...new Set(filteredProducts.map(p => p.colour))];
    let sizes = [...new Set(filteredProducts.flatMap(p => p.variants.map(variant => variant.size)))];
    
    // Make sure both mobile and desktop have all color filter
    Array.from(colorFilter).forEach(el => {
        // Start by adding the default "All Colors" option
        el.innerHTML = '<option value="All">All Colors</option>';
        
        // Add options for each color
        colors.forEach(color => {
            el.innerHTML += `<option value="${color}">${color}</option>`;
        });
    });
    
    // Loop through each size filter element and populate it
    Array.from(sizeFilter).forEach(el => {
        // Clear previous options and add a default "All Sizes" option
        el.innerHTML = '<option value="All">All Sizes</option>';

        // Add each size option
        sizes.forEach(size => {
            el.innerHTML += `<option value="${size}">${size}</option>`;
        });
    });

    }

// Event listeners for filters
function applyFilter() {
    let screenWidth = window.innerWidth;
    let selectedColor;

    if (screenWidth <= 768) { // For small screen
        selectedColor = document.getElementsByClassName("color-filter")[0].value; // First element for smaller screens
    } else { // For larger screens
        selectedColor = document.getElementsByClassName("color-filter")[1].value; // Second element for larger screens
    }

    // For selectedSize
    let selectedSize;
    if (screenWidth <= 768) { // Small screen
        selectedSize = document.getElementsByClassName("size-filter")[0].value;
    } else { // Large screen
        selectedSize = document.getElementsByClassName("size-filter")[1].value;
    }

    // For selectedSort
    let selectedSort;
    if (screenWidth <= 768) { // Small screen
        selectedSort = document.getElementsByClassName("sort-filter")[0].value;
    } else { // Large screen
        selectedSort = document.getElementsByClassName("sort-filter")[1].value;
    }

    let products = document.querySelectorAll(".product");
    let count = 0;

    // Filter products based on selected color and size
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
    if (screenWidth <= 768) { // Small screen
        document.getElementsByClassName("product-count")[0].textContent = count + " products";
    } else { // Large screen
        document.getElementsByClassName("product-count")[1].textContent = count + " products";
    }
}

// Only for mobile 
function toggleFilterMenu() {
    const overlay = document.getElementById('filterOverlay');
    const overlayBg = document.getElementById('filterOverlayBg');
    const html = document.documentElement;
    const body = document.body;

    overlay.classList.toggle('active');
    overlayBg.classList.toggle('active');

    // Prevent scrolling on the main content
    if (overlay.classList.contains('active')) {
        html.style.overflow = 'hidden';
        body.style.overflow = 'hidden';
    } else {
        html.style.overflow = 'auto';
        body.style.overflow = 'auto';
    }

}

// Only for mobile
function clearFilters() {
    // Reset color filter for index 0 since this function only for mobile device
    const colorFilter = document.getElementsByClassName("color-filter")[0];
    colorFilter.selectedIndex = 0;

    // Reset size filter
    const sizeFilter = document.getElementsByClassName("size-filter")[0];
    sizeFilter.selectedIndex = 0;

    // Reset sort filter (assume same element ID across screens)
    const sortSelect = document.getElementsByClassName("sort-filter")[0];
    sortSelect.selectedIndex = 0;

    // Re-apply filters with cleared values
    applyFilter();
}