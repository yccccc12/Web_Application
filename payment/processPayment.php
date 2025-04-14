<?php
// filepath: c:\wamp64\www\Web_Application\payment\processPayment.php

session_start();
require_once '../classes/Order.php'; // Include the Order class
require_once '../classes/Product.php'; // Include the Product class

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /Web_Application/user/login.php");
    exit;
}

// Validate form data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userID = $_SESSION['user_id'];
    $state = htmlspecialchars($_POST['state']);
    $city = htmlspecialchars($_POST['city']);
    $postcode = htmlspecialchars($_POST['postcode']);
    $unit = htmlspecialchars($_POST['unit']);
    $paymentMethod = htmlspecialchars($_POST['payment_method']);

    // Retrieve cart and total price from session
    $cart = $_SESSION['cart'] ?? [];
    $total = 0;
    foreach ($cart as $item) {
        $product = new Product();
        $productData = $product->getAProduct($item['product_id']);
        $price = $productData['price'] ?? 0;
        $subtotal = $price * $item['quantity'];
        $total += $subtotal;
    }

    // Use the Order class to handle database operations
    $order = new Order();
    $orderID = $order->createOrder($userID, $total, $paymentMethod, $unit, $state, $postcode, $city);
    $order->addOrderItems($orderID, $cart);

    // Reduce stock levels
    $order->reduceStock($cart);

    // Clear the cart
    unset($_SESSION['cart']);

    // Redirect to the success page
    header("Location: /Web_Application/payment/success.php");
    exit;
} else {
    // Redirect back to the payment page if the request is invalid
    header("Location: /Web_Application/payment/payment.php");
    exit;
}