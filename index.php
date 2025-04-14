<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TUB Concept Store</title>

    <!-- Google Font: Figtree -->
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- REMIXICONS -->
    <link rel="stylesheet" href="style/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css">

    <!-- AOS Effect -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <style>
        .slide {
            width: 100%;
            display: none;
        }

        .slide:first-child {
            display: block;
        }

        .nav-button {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(0,0,0,0.2);
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            z-index: 1;
        }

        .prev-button {
            left: 10px;
        }

        .next-button {
            right: 10px;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php';?>

    <section class="landing-section">
        <h1>Style Without Limits</h1>
        <p>
            Welcome to TUB – where fashion meets confidence, and style has no limits. We create bold, trendsetting pieces designed for those who dare to stand out. 
            Explore the latest drops, discover timeless essentials, and redefine your wardrobe with us. Because fashion isn’t just what you wear – it’s how you own it.
        </p>
        <div class="landing-image-container slideshow">
            <img name="landing-image" src="img/landing.jpg" alt="This is an image of models" class="slide" style="display: block;">
            <img name="landing-image" src="img/landing_5.jpg" alt="This is an image of models" class="slide" style="display: none;">
            <img name="landing-image" src="img/landing_3.jpg" alt="This is an image of models" class="slide" style="display: none;">

            <button class="nav-button prev-button" id="prevSlideButton">&#10094;</button>
            <button class="nav-button next-button" id="nextSlideButton">&#10095;</button>
        </div>
        <script src="js/slideshow.js"></script>
    </section>

    <section class="new-arrivals" data-aos="fade-up" data-aos-duration="1500">
        <h2>New Arrivals</h2>
        <section class="products-section">
          
        </section>
        <!-- 
        <button class="view-all-button">View all</button> 
        -->
    </section>

    <section class="shop-category-header" data-aos="fade-up" data-aos-duration="800">
        <h2>Shop by Category</h2>
    </section>

    <section class="shop-section">
        <div class="shop-item" data-aos="fade-right" data-aos-duration="1000">
            <a href="/Web_Application/products_listing.php?gender=men" class="shop-link">
                <img src="img/shop-men.jpg" alt="Shop Men">
                <div class="shop-overlay">
                    <span>Shop Men</span>
                </div>
            </a>
        </div>
        <div class="shop-item" data-aos="fade-left" data-aos-duration="1000">
            <a href="/Web_Application/products_listing.php?gender=women" class="shop-link">
                <img src="img/shop-women.jpg" alt="Shop Women">
                <div class="shop-overlay">
                    <span>Shop Women</span>
                </div>
            </a>
        </div>
    </section>

    <section class="why-choose-section">
        <div class="reason-description">
            <h2>Why Choose Us?</h2>
            
            <h3 data-aos="fade-up">Shop with Confidence</h3>
            <p data-aos="fade-up" data-aos-delay="100">
                Find the perfect fit for your wardrobe with our easy-to-navigate collections.
            </p>

            <h3 data-aos="fade-up" data-aos-delay="200">Affordable and Stylish</h3>
            <p data-aos="fade-up" data-aos-delay="300">
                We offer high-quality fashion at prices that won’t break the bank.
            </p>

            <h3 data-aos="fade-up" data-aos-delay="400">Fresh Styles for You</h3>
            <p data-aos="fade-up" data-aos-delay="500">
                Discover our latest collection of trendy and comfortable clothing. Perfect for any occasion.
            </p>
        </div>
        <div class="why-choose-image-container" data-aos="fade-down" data-aos-duration="300">
            <img name="why-choose-image" src="img/landing3.jpg" alt="Fashion Banner">
        </div>
    </section>

    <?php include 'includes/footer.php';?>
    
    <script>
        AOS.init({
            once: true, // Ensures the animation triggers only once
            easing: 'ease-in-out',
        });
    </script>
    <script src="js/load_products.js"></script>
</body>
</html>