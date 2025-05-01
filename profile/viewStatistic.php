<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../user/login.php");
    exit();
}

require_once '../classes/user.php';
require_once '../classes/order.php';

$userID = $_SESSION['user_id']; // Retrieve the logged-in user's ID
$orderID = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

// Retrieve the user's order history
$order = new Order();
$user = new User();

$orderHistory = $order->getOrderHistory($userID);
$yearlyStats = [];
$monthlyAmountData = [];
$monthlyOrderCounts = [];
$availableYears = [];

foreach ($orderHistory as $orderItem) {
    $orderDate = new DateTime($orderItem['date']); // Convert to DateTime object
    $year = $orderDate->format('Y'); // Year in YYYY format
    $month = $orderDate->format('F'); // Full month name

    // Add year to available years if not occured
    if (!in_array($year, $availableYears)) {
        $availableYears[] = $year;
    }

    // Initialize stats
    if (!isset($yearlyStats[$year])) {
        $yearlyStats[$year] = [
            'totalAmount' => 0,
            'orderCount' => 0,
            'gender' => ['Men' => 0, 'Women' => 0],
            'colors' => []
        ];
    }

    if (!isset($monthlyAmountData[$year][$month])) {
        $monthlyAmountData[$year][$month] = 0;
        $monthlyOrderCounts[$year][$month] = 0;
    }

    // Update total/yearly/monthly stats
    $yearlyStats[$year]['totalAmount'] += $orderItem['totalAmount'];
    $yearlyStats[$year]['orderCount'] += 1;
    $monthlyAmountData[$year][$month] += $orderItem['totalAmount'];
    $monthlyOrderCounts[$year][$month] += 1;

    // Get items
    $orderDetails = $order->getOrderHistoryById($orderItem['orderID']);
    foreach ($orderDetails['orderItems'] as $item) {
        $gender = $item['category'] ?? 'Women';
        $color = $item['colour'] ?? 'Unknown';

        if (!isset($yearlyStats[$year]['gender'][$gender])) {
            $yearlyStats[$year]['gender'][$gender] = 0;
        }
        $yearlyStats[$year]['gender'][$gender] += $item['quantity'];

        if (!isset($yearlyStats[$year]['colors'][$color])) {
            $yearlyStats[$year]['colors'][$color] = 0;
        }
        $yearlyStats[$year]['colors'][$color] += $item['quantity'];
    }
}

// Sort years descending
rsort($availableYears);

// Set default year (latest year) for initial display
$defaultYear = $availableYears[0] ?? date('Y');
$defaultStats = $yearlyStats[$defaultYear] ?? ['totalAmount' => 0, 'orderCount' => 0, 'gender' => [], 'colors' => []];
$averageSpending = $defaultStats['orderCount'] ? $defaultStats['totalAmount'] / $defaultStats['orderCount'] : 0;

// Top color
$topColor = 'N/A';
$topColorCount = 0;
foreach ($defaultStats['colors'] as $color => $count) {
    if ($count > $topColorCount) {
        $topColor = $color;
        $topColorCount = $count;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overall Statistic</title>
    
    <!-- Google Font: Figtree -->
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- REMIXICONS -->
    <link rel="stylesheet" href="../style/profile.css">
    <link rel="stylesheet" href="../style/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(300px, 2fr));
            gap: 20px;
            margin: 40px;
        }

        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            height: 400px; /* Set a fixed height for the cards */
        }

        .card h2 {
            font-size: 1.2rem;
            font-weight: 500;
            margin-bottom: 10px;
        }

        .card canvas {
            margin: 0 auto;
            display: block;
            max-width: 100%;
            height: 90%;
        }

        .stat-card {
            grid-column: span 2;
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 600;
            color: #333;
        }

        .stat-label {
            font-size: 1rem;
            color: #777;
            
        }

        .stat-item {
            background: #f9f9f9;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .stat-item h2 {
            font-size: 1.2rem;
            font-weight: 500;
            margin-bottom: 10px;
        }

        .stat-item .stat-value {
            font-size: 2rem;
            font-weight: 600;
            color: #333;
        }

        .stat-item .stat-label {
            font-size: 1rem;
            color: #777;
        }

        #timeSeriesChart {
            /* Adjust the height of the chart to fit the card */
            height: 330px !important; 
        }

        #yearSelector{
            font-size: 16px;
            border: none;
            background: none;
            color: #5A5A5A;
            cursor: pointer;
            outline: none;
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <div class="container">
        <div class="sidebar">
            <a href="personalInfo.php" id="personal" style="font-weight: bold;">Personal Info</a>
            <a href="editProfile.php">Edit Profile</a>
            <a href="orderHistory.php">Order History</a>
            <a href="#" style="font-weight: bold;">| View Statistic</a>
            <a href="logout.php">Log out</a>
        </div>
        <div style="margin-bottom: 20px; text-align: center;">
            <p style="font-weight: bold; margin: 40px 0 5px 5px;">Filter:</p>
            <select id="yearSelector" style="margin-left: 5px;">
                <option disabled selected>Year</option>
                <!-- Options will be added dynamically -->
            </select>
        </div>
        <div class="dashboard-grid">
            <!-- Total Amount Spent -->
            <div class="card stat-card">
            <h2>Total Amount Spent across Month</h2>
            <canvas id="timeSeriesChart"></canvas>
            </div>

            <div class="card stat-card">
            <div class="stat-item">
                <h2>Total Amount Spent</h2>
                <div class="stat-value" id="totalAmount">RM <?= number_format($defaultStats['totalAmount'], 2); ?></div>
                <div class="stat-label">Across All Orders</div>
            </div>

            <div class="stat-item">
                <h2>Average Spending Per Order</h2>
                <div class="stat-value" id="averageSpending">RM <?= number_format($averageSpending, 2); ?></div>
                <div class="stat-label">Across All Orders</div>
            </div>

            <div class="stat-item">
                <h2>Top Favorite Color</h2>
                <div class="stat-value" id="topColor"><?= htmlspecialchars($topColor); ?></div>
                <div class="stat-label" id="topColorCount">Count: <?= $topColorCount; ?></div>
            </div>
            </div>

            <div class="card stat-card">
            <h2>Top Categories Purchased [Gender]</h2>
            <canvas id="genderPieChart"></canvas>
            </div>

            <div class="card stat-card">
            <h2>Total Orders per Month</h2>
            <canvas id="ordersPerMonthChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php';?>
    <script>
        const availableYears = <?= json_encode($availableYears); ?>;
        const allMonthlyData = <?= json_encode($monthlyAmountData); ?>;
        const allOrdersData = <?= json_encode($monthlyOrderCounts); ?>;
        const yearlyStats = <?= json_encode($yearlyStats); ?>;
    </script>
    <script src="../js/statistics_filter.js"></script>
</body>
</html>

