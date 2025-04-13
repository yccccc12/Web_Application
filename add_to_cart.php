<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['product_id'], $data['size'], $data['quantity'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid data']);
        exit;
    }

    $productID = intval($data['product_id']);
    $size = htmlspecialchars($data['size']);
    $quantity = intval($data['quantity']);
    $colour = htmlspecialchars($data['colour']);

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] === $productID && $item['size'] === $size) {
            $item['quantity'] += $quantity;
            $item['colour'] = $colour;
            $found = true;
            break;
        }
    }
        
    if (!$found) {
        $_SESSION['cart'][] = [
            'product_id' => $productID,
            'size' => $size,
            'colour' => $colour,
            'quantity' => $quantity,
        ];
    }

    echo json_encode([
        'success' => true,
        'message' => 'Product added to cart',
        'added_product' => [
            'product_id' => $productID,
            'size' => $size,
            'quantity' => $quantity,
            'colour' => $colour, 
        ],
        'cart' => $_SESSION['cart'],
    ]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid request method']);
exit;
