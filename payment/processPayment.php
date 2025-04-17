<?php
// filepath: c:\wamp64\www\Web_Application\payment\processPaymentProduct.php

session_start();
require_once '../classes/order.php'; // Include the Order class
require_once '../classes/product.php'; // Include the Product class

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /Web_Application/user/login.php");
    exit;
}

// Validate form data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userID = $_SESSION['user_id'];
    $state = htmlspecialchars($_POST['state'] ?? '');
    $city = htmlspecialchars($_POST['city'] ?? '');
    $postcode = htmlspecialchars($_POST['postcode'] ?? '');
    $unit = htmlspecialchars($_POST['unit'] ?? '');
    $paymentMethod = htmlspecialchars($_POST['payment_method'] ?? '');
    $productID = intval($_POST['product_id'] ?? 0);
    $size = htmlspecialchars($_POST['size'] ?? '');
    $quantity = intval($_POST['quantity'] ?? 0);
    $colour = htmlspecialchars($_POST['colour'] ?? '');
    $total = floatval($_POST['total'] ?? 0);
    
    $product = new Product();
    $variantID = $product->getProductVariantsID($productID, $size);
    
    // Check if required fields are missing
    if (!$productID || !$size || !$quantity || !$total) {
        error_log("Missing required fields in POST data.");
        header("Location: /Web_Application/payment/payment.php");
        exit;
    }

    // Use the Order class to handle database operations
    $order = new Order();
    // Create order and add the single product
    $orderID = $order->createOrder($userID, $total, $paymentMethod, $unit, $state, $postcode, $city);
    $order->addOrderItems($orderID, [
        [
            'product_id' => $productID,
            'variant_id' => $variantID,
            'size' => $size,
            'quantity' => $quantity,
        ],
    ]);

    // Reduce stock levels
    $order->reduceStock([
        [
            'product_id' => $productID,
            'size' => $size,
            'quantity' => $quantity,
        ],
    ]);

    // Redirect to the success page
    header("Location: /Web_Application/payment/success.php");
    exit;
} else {
    // Redirect back to the payment page if the request is invalid
    header("Location: /Web_Application/payment/payment.php");
    exit;
}