<?php
session_start();
require_once '../classes/order.php'; // Include the Order class
require_once '../classes/database.php';
// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /Web_Application/user/login.php");
    exit;
}

$userID = $_SESSION['user_id']; // Retrieve the logged-in user's ID
$orderID = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

// Retrieve the user's order history
$order = new Order();
$orderHistory = $order->getOrderHistory($userID);

$orderDetails = $order->getOrderHistoryById($orderID);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    
    <!-- Google Font: Figtree -->
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- REMIXICONS -->
    <link rel="stylesheet" href="../style/profile.css">
    <link rel="stylesheet" href="../style/styles.css">
    <link rel="stylesheet" href="../style/product.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css">
    <style>
        .order-details-table{
            margin-bottom: 10px;
            border-spacing: 0 20px;
        }

        .back-btn {
            margin-top: 10px;
            width: 15%;
            height: 45px;
            background-color: black;
            color: white;
            border: none;
            font-size: 14px;
            cursor: pointer;
            transition: 0.3s ease;
        }

        .back-btn a {
            color: white;
            text-decoration: none;
            display: block;
            width: 100%;
            height: 100%;
            line-height: 45px;
        }
        
        .back-btn a:visited {
            color: white;
        }



    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container">
        <div class="sidebar">
            <a href="personalInfo.php" id="personal">Personal Info</a>
            <a href="editProfile.php">Edit Profile</a>
            <a href="orderHistory.php" style="font-weight: bold;">| Order History</a>
            <a href="logout.php">Log out</a>
        </div>

        <div class="content">
            <div class="card">
                <h2>Order History</h2>
                <br>
                <hr>
                <br>
                <?php if (empty($orderHistory)): ?>
                    <p style="text-align: center;">You have not made any orders yet.</p>
                <?php elseif (isset($_GET['order_id'])): ?>
                    <!-- Order Details View -->
                    <h2>Order #<?php echo htmlspecialchars($_GET['order_id']); ?></h2>
                    <table class="order-details-table">
                        <thead>
                            <tr>
                                <th style="text-align: left;">Product</th>
                                <th>Quantity</th>
                                <th>Total Price</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $total = 0?>
                        <?php foreach ($orderDetails['orderItems'] as $item): ?>
                            <?php 
                                $subtotal = $item['price'] * $item['quantity'];
                                $total += $subtotal;    
                            ?>
                            <tr>
                                <td>
                                    <div class="product-display">
                                        <!-- Display product image -->
                                        <img src="<?php echo htmlspecialchars($item['image']['url']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="product-img" width="60">
                                        
                                        <div class="product-info" style="margin-top: 10px;">
                                            <p style="margin: 10px 0; font-weight: bold;"><?php echo htmlspecialchars($item['name']); ?></p>
                                            <p style="margin: 10px 0;">Unit Price: RM<?php echo number_format($item['price'], 2); ?></p>
                                            <p style="margin: 10px 0;">Size: <?php echo htmlspecialchars($item['size'] ?? 'N/A'); ?></p>
                                            <p style="margin: 10px 0;">Colour: <?php echo htmlspecialchars($item['colour'] ?? 'N/A'); ?></p>
                                        </div>
                                    </div>
                                </td>
                                
                                <td style="text-align: center;">
                                    <!-- Display the quantity as text -->
                                    x <?php echo htmlspecialchars($item['quantity']); ?>
                                </td>
                                
                                <td style="text-align: center;">
                                    RM<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <p style="text-align:right;"><strong>Total: RM<?php echo number_format($total, 2); ?></strong></p>
                    <button class="back-btn"><a href="<?php echo basename($_SERVER['PHP_SELF']); ?>">Back</a></button>

                <?php else: ?>
                    <table class="order-history-container">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Date</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orderHistory as $order): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($order['orderID']); ?></td>
                                    <td><?php echo htmlspecialchars($order['date']); ?></td>
                                    <td>RM <?php echo number_format($order['totalAmount'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($order['orderStatus']); ?></td>
                                    <td>
                                        <a href="?order_id=<?php echo urlencode($order['orderID']); ?>">Order Details</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>