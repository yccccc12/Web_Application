<?php
session_start();
header('Content-Type: application/json');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to add items to the cart.']);
    exit;
}

// Include necessary files
require_once 'classes/cart.php';
require_once 'classes/product.php';

$userID = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);

$productID = $data['product_id'];
$size = $data['size'];
$quantity = $data['quantity'];
$colour = $data['colour'];

$product = new Product();
$productVariant = $product->getProductVariant($productID, $size); // Fetch the specific variant

if (!$productVariant) {
    echo json_encode(['success' => false, 'message' => 'The selected product variant does not exist.']);
    exit;
}

$availableStock = (int) $productVariant['stock'];

// Debugging: Log the stock value
error_log("Available stock: $availableStock, Requested quantity: $quantity");

if ($quantity > $availableStock) {
    echo json_encode([
        'success' => false,
        'message' => "Only $availableStock items are available in stock for the selected size."
    ]);
    exit;
}

// Add the product to the cart
$cart = new Cart();
$cart->addToCart($userID, $productID, $size, $quantity, $colour);

echo json_encode(['success' => true, 'message' => 'Item added to cart successfully.']);
exit;
