<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success</title>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style/styles.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="checkout-container" style="text-align: center; padding: 50px;">
        <h1 style="font-size: 30px; color: #28a745;">Payment Successful</h1>
        <p style="font-size: 18px; margin-top: 20px;">Thank you for your purchase! Your order has been placed successfully.</p>
        <a href="/Web_Application" class="checkout-btn" style="display: inline-block; margin-top: 30px; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;">Return to Home</a>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>