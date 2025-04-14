<?php
session_start();
require_once '../classes/Order.php'; // Include the Order class

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /Web_Application/user/login.php");
    exit;
}

$userID = $_SESSION['user_id']; // Retrieve the logged-in user's ID

// Retrieve the user's order history
$order = new Order();
$orderHistory = $order->getOrderHistory($userID);
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css">
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
                <?php else: ?>
                    <table class="order-history-container">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Date</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orderHistory as $order): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($order['orderID']); ?></td>
                                    <td><?php echo htmlspecialchars($order['date']); ?></td>
                                    <td>RM<?php echo number_format($order['totalAmount'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($order['orderStatus']); ?></td>
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