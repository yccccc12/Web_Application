<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<header>
    <nav class="nav-bar">
        <!-- Hamburger Menu Icon (visible on mobile) -->
        <div class="hamburger-menu">
            <i class="ri-menu-line hamburger-icon" aria-label="Menu" onclick="toggleHamburgerMenu()"></i>
        </div>

        <!-- Logo -->
        <div class="logo"><a href="/Web_Application">TUB</a></div>

        <!-- Transparent Overlay -->
        <div class="hamburger-overlay-bg" id="hamburgerOverlayBg" onclick="toggleHamburgerMenu()"></div>

        <!-- Sliding Hamburger Menu -->
        <div class="hamburger-overlay" id="hamburgerOverlay">
            <i class="ri-close-line close-icon" onclick="toggleHamburgerMenu()"></i>
            <ul class="hamburger-menu-list">
                <li><a href="/Web_Application/products_listing.php?gender=men">Mens</a></li>
                <li><a href="/Web_Application/products_listing.php?gender=women">Womens</a></li>
                <li><a href="#">Blog</a></li>
                <li><a href="/Web_Application/about-us/our_story.php">Our Story</a></li>
                <li><a href="/Web_Application/about-us/contact_us.php">Contact Us</a></li>
                <li><a href="/Web_Application/cart.php">Cart</a></li>
                <li>
                    <?php if (isset($_SESSION['user_email'])): ?>
                        <!-- Show Personal Info if logged in -->
                        <a href="/Web_Application/profile/personalInfo.php">My Account</a>
                    <?php else: ?>
                    
                        <!-- Show Login if not logged in -->
                        <a href="/Web_Application/user/login.php">My Account</a>
                    <?php endif; ?>
                </li>
            </ul>
        </div>

        <!-- Main Nav Menu (visible on desktop) -->
        <ul class="nav-menu">
            <li class="dropdown">
                <a href="/Web_Application/products_listing.php?gender=men">Mens <i class="ri-arrow-down-s-line"></i></a>
                <ul class="dropdown-menu">
                    <li><a href="#">Shirts</a></li>
                    <li><a href="#">Pants</a></li>
                    <li><a href="#">Shoes</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="/Web_Application/products_listing.php?gender=women">Womens <i class="ri-arrow-down-s-line"></i></a>
                <ul class="dropdown-menu">
                    <li><a href="#">Dresses</a></li>
                    <li><a href="#">Bags</a></li>
                    <li><a href="#">Accessories</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#">Blog <i class="ri-arrow-down-s-line"></i></a>
                <ul class="dropdown-menu">
                    <li><a href="#">Fashion Tips</a></li>
                    <li><a href="#">Trends</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#">About <i class="ri-arrow-down-s-line"></i></a>
                <ul class="dropdown-menu">
                    <li><a href="/Web_Application/about-us/our_story.php">Our Story</a></li>
                    <li><a href="/Web_Application/about-us/contact_us.php">Contact Us</a></li>
                </ul>
            </li>
        </ul>

        <div class="nav-icons">
            <a href="/Web_Application"><i class="ri-home-2-line"></i></a>
            
            <?php if (isset($_SESSION['user_email'])): ?>
                <!-- Show Personal Info if logged in -->
                <a href="/Web_Application/profile/personalInfo.php"><i class="ri-user-line"></i> </a>
            <?php else: ?>
            
                <!-- Show Login if not logged in -->
                <a href="/Web_Application/user/login.php"><i class="ri-user-line"></i></a>
            <?php endif; ?>

            <a href="/Web_Application/cart.php"><i class="ri-shopping-bag-line"></i></a>
        </div>

        <!-- Search Bar -->
    </nav>
    <hr class="divider">
</header>
<script>
    function toggleHamburgerMenu() {
        const overlay = document.getElementById('hamburgerOverlay');
        const overlayBg = document.getElementById('hamburgerOverlayBg');
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
</script>