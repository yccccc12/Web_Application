<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<header>
    <nav class="nav-bar">
        <!-- Hamburger Menu Icon -->
        <i class="ri-menu-line hamburger-icon" aria-label="Menu"></i>

        <!-- Logo -->
        <div class="logo"><a href="/Web_Application">TUB</a></div>

        <!-- Navigation Links -->
        <ul class="nav-menu">
            <li class="dropdown">
                <a href="/Web_Application/products.php?gender=men">Mens <i class="ri-arrow-down-s-line"></i></a>
                <ul class="dropdown-menu">
                    <li><a href="#">Shirts</a></li>
                    <li><a href="#">Pants</a></li>
                    <li><a href="#">Shoes</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="/Web_Application/products.php?gender=women">Womens <i class="ri-arrow-down-s-line"></i></a>
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
                    <li><a href="#">Contact</a></li>
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

            <i class="ri-shopping-bag-line"></i>
        </div>

        <!-- Search Bar -->
    </nav>
    <hr class="divider">
</header>