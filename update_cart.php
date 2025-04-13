<?php
session_start();

if (!isset($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

$product_id = $_POST['product_id'] ?? null;
$size = $_POST['size'] ?? null;
$action = $_POST['action'] ?? null;

foreach ($_SESSION['cart'] as &$item) {
    if ($item['product_id'] == $product_id && $item['size'] == $size) {
        if ($action === 'increase') {
            $item['quantity'] += 1;
        } elseif ($action === 'decrease') {
            $item['quantity'] -= 1;
            if ($item['quantity'] <= 0) {
                $item = null; // Mark for removal
            }
        }
        break;
    }
}

$_SESSION['cart'] = array_values(array_filter($_SESSION['cart'])); // Remove nulls & reindex
header("Location: cart.php");
exit;
