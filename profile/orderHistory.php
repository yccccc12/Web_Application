<?php
session_start();
require_once '../classes/order.php';
require_once '../classes/user.php';
require_once '../classes/database.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /Web_Application/user/login.php");
    exit;
}

$userID = $_SESSION['user_id'];  // Retrieve the logged-in user's ID
$orderID = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

$order = new Order();
$user = new User();
$orderHistory = $order->getOrderHistory($userID); // Retrieve the user's order history
$orderDetails = $order->getOrderHistoryById($orderID); // Retrieve order details by order ID

$rating = 0;
$comment = "";
$commentError = $ratingError = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productID = intval($_POST['product_id']);
    $orderID = intval($_POST['order_id']);
    $rating = intval($_POST['rating']) ?? 0;
    $comment = trim($_POST['comment']) ?? "";

    $valid = true;

    // Validate rating
    if ($rating == 0) {
        $ratingError = '<i class="ri-error-warning-fill"></i> Rating must be between 1 and 5.';
        $valid = false;
    }

    // Validate comment
    if (empty($comment)) {
        $commentError = '<i class="ri-error-warning-fill"></i> Comment is required.';
        $valid = false;
    }

    // If comment valid, save the comment to the database
    if($valid){
        $user->saveUserReview($userID, $productID, $orderID, $rating, $comment);
        
        $successMessage = "Thank you for your comment!";
        header("Location: /Web_Application/profile/orderHistory.php?order_id=$orderID");
    }else{
        $successMessage = "Failed to save your comment.";
    }
}
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

        .cancel-btn{
            margin-left: 20px;
            background: none;
            border: none;
            color: black;
            text-decoration: underline;
            font-size: 14px;
            cursor: pointer;
            padding: 0;
        }

        .form-field input,
        .form-field textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        .form-field textarea {
            resize: none;
            height: 120px;
        }

        h3{

            font-weight: normal;
        }

        h4{
            margin-bottom: 5px;
        }

        label{
            margin-bottom: 5px;
            font-weight: normal;
        }

        .star-rating i {
            cursor: pointer;
            font-size: 24px;
        }

        .star-rating{
            margin-bottom: 10px;
        }

        .error {
            color: red; 
            font-size: 0.9em; 
            margin-top: 2px; 
            font-weight: normal;
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
            <a href="viewStatistic.php">View Statistic</a>
            <a href="logout.php">Log out</a>
        </div>

        <div class="content">
            <div class="card">
            <h2>
                <?php
                    // Change header based on URL parameters
                    if (isset($_GET['order_id']) && isset($_GET['product_id'])) {
                        echo "Review Product";  // If both order_id and product_id are set
                    } else {
                        echo "Order History";  // If only order_id is set
                    }
                ?>
            </h2>
                <br>
                <hr>
                <br>
                <?php if (empty($orderHistory)): ?>
                    <p style="text-align: center;">You have not made any orders yet.</p>
                <?php elseif (isset($_GET['order_id'])): ?>
                    <!-- Order Details Table -->
                    <h2>Order #<?php echo htmlspecialchars($_GET['order_id']); ?></h2>
                    <table class="order-details-table">
                        <?php if (!isset($_GET['product_id'])): ?>
                            <thead>
                                <tr id="order-details-header">
                                    <th style="text-align: left;">Product</th>
                                    <th>Quantity</th>
                                    <th>Total Price</th>
                                </tr>
                            </thead>
                        <?php endif; ?>
                        <tbody>
                        <?php $total = 0?>
                        <?php foreach ($orderDetails['orderItems'] as $item): ?>
                            <?php 
                                $subtotal = $item['price'] * $item['quantity'];
                                $total += $subtotal;    
                                $productID = $item['productID'];
                                $orderID = $_GET['order_id'];
                            ?>
                            <tr>
                            <!-- Display product details if not in review mode -->
                            <?php if (!isset($_GET['product_id'])): ?>
                                <!-- Show Product Listing Section -->
                                <td>
                                    <div class="product-display">
                                        <img src="<?php echo htmlspecialchars($item['image']['url']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="product-img" width="60">
                                        
                                        <div class="product-info" style="margin-top: 10px;">
                                            <p style="margin: 10px 0; font-weight: bold;"><?php echo htmlspecialchars($item['name']); ?></p>
                                            <p style="margin: 10px 0;">Unit Price: RM<?php echo number_format($item['price'], 2); ?></p>
                                            <p style="margin: 10px 0;">Size: <?php echo htmlspecialchars($item['size'] ?? 'N/A'); ?></p>
                                            <p style="margin: 10px 0;">Colour: <?php echo htmlspecialchars($item['colour'] ?? 'N/A'); ?></p>
                                    
                                            <!-- Show the "Rate this product" link if the user hasn't reviewed it yet -->
                                            <?php if (!$user->hasReviewedProductInOrder($userID, $productID, $orderID)) {
                                                echo '<p style="margin: 10px 0; text-decoration: underline; color: black; cursor: pointer;" 
                                                    onclick="window.location.href=\'?order_id=' . $orderDetails['orderID'] . '&product_id=' . $item['productID'] . '\'">
                                                    Rate this product
                                                    </p>';
                                            } else {
                                                // If already reviewed, show a reviewed message
                                                echo '<p style="color: green;">You have already reviewed this product.</p>';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </td>
                                <td style="text-align: center;">
                                    <!-- Display the quantity -->
                                    x <?php echo htmlspecialchars($item['quantity']); ?>
                                </td>
                                
                                <td style="text-align: center;">
                                    <!-- Display the total price a product -->
                                    RM<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                                </td>
                            <?php endif; ?>

                            <!-- Show the rating form only if product_id matches this product -->
                            <?php if (isset($_GET['product_id']) && $_GET['product_id'] == $item['productID']): ?>
                                <td colspan="100%">
                                    <!-- Rating Form Container-->
                                    <div id="rating-form-<?php echo $item['productID']; ?>" style="display: block;">
                                        <h3>You're reviewing:</h3>
                                        <br>
                                        <h4>Product: <?php echo htmlspecialchars($item['name']); ?></p>
                                        <h4>Unit Price: RM<?php echo number_format($item['price'], 2); ?></p>
                                        <h4>Size: <?php echo htmlspecialchars($item['size'] ?? 'N/A'); ?></p>
                                        <h4>Colour: <?php echo htmlspecialchars($item['colour'] ?? 'N/A'); ?></p>
                                        <br>

                                        <!-- Rating Form -->
                                        <form method="post" id="rating-form">
                                            <input type="hidden" name="order_id" value="<?php echo $orderID; ?>">
                                            <input type="hidden" name="product_id" value="<?php echo $item['productID']; ?>">
                                            <input type="hidden" name="rating" id="rating-value">

                                            <!-- Rating -->
                                            <label>Your Rating <span style="color:red">*</span></label>
                                            <div class="star-rating">
                                                <i class="ri-star-line" data-value="1"></i>
                                                <i class="ri-star-line" data-value="2"></i>
                                                <i class="ri-star-line" data-value="3"></i>
                                                <i class="ri-star-line" data-value="4"></i>
                                                <i class="ri-star-line" data-value="5"></i>
                                                <input type="hidden" name="rating" id="rating-value" class="rating-value" value="<?php echo $rating; ?>">
                                            </div>
                                            <div id="ratingError" class="error"><?php echo $ratingError; ?></div>

                                            <!-- Comment -->
                                            <div class="form-field">
                                                <label for="comment">Review <span style="color:red">*</span></label>
                                                <textarea id="comment" rows="5" cols="98" name="comment" placeholder="Enter your comment" ></textarea>
                                                <div id="commentError" class="error"><?php echo $commentError; ?></div>
                                            </div>
                                            <br>

                                            <button class="back-btn" type="submit">Submit</button>
                                            <button type="button" class="cancel-btn" onclick="window.location.href='?order_id=<?php echo $orderID; ?>'">Cancel</button>
                                        </form>
                                    </div>
                                </td>
                            <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php if (!isset($_GET['product_id'])): ?>
                        <p style="text-align:right;"><strong>Total: RM<?php echo number_format($total, 2); ?></strong></p>
                        <button class="back-btn"><a href="<?php echo basename($_SERVER['PHP_SELF']); ?>">Back</a></button>
                    <?php endif; ?>
                <!-- Display order history if no product_id is set in the url -->
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
    <script>
        // Show the rating form if product_id is set in the URL
        function showRatingForm(orderId, productId) {
            // Hide all other rating forms first
            document.querySelectorAll('[id^="rating-form-"]').forEach(form => {
                form.style.display = 'none';
            });

            // Show the form for the selected product
            const form = document.getElementById('rating-form-' + productId);

            if (form) {
                form.style.display = 'block';
            }
    
            // Update the URL in the address bar
            const newUrl = `?order_id=${orderId}&product_id=${productId}`;
            history.pushState(null, '', newUrl);
            window.location.reload();
        }

        // Handle the star rating functionality
        document.querySelectorAll('.star-rating').forEach(starBlock => {
        const stars = starBlock.querySelectorAll('i');
        const ratingInput = starBlock.querySelector('.rating-value');

        const setStars = (rating) => {
            stars.forEach(star => {
                const val = parseInt(star.dataset.value);
                star.classList.toggle('ri-star-fill', val <= rating);
                star.classList.toggle('ri-star-line', val > rating);
            });
        };

        stars.forEach(star => {
            star.addEventListener('click', () => {
                const rating = parseInt(star.dataset.value);
                ratingInput.value = rating;
                setStars(rating);
            });

            star.addEventListener('mouseover', () => {
                setStars(parseInt(star.dataset.value));
            });

            star.addEventListener('mouseout', () => {
                setStars(parseInt(ratingInput.value));
            });
        });
        setStars(parseInt(ratingInput.value));
    });
    </script>
</body>
</html>