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

// Initialize gender counts
$genderCount = ['Men' => 0, 'Women' => 0];

// Initialize total amount spent
$totalAmountSpent = 0;

// Loop through each order in the user's order history
foreach ($orderHistory as $orderItem) {
    $totalAmountSpent += $orderItem['totalAmount']; 
    $orderDetails = $order->getOrderHistoryById($orderItem['orderID']);
 
    foreach ($orderDetails['orderItems'] as $item) {
        // Try to get gender info from either 'gender' or 'men' field
        $gender = $item['category'] ?? '';

        // Count items based on gender
        if ($gender == 'Men') {
            $genderCount['Men'] += $item['quantity'];
        } else{
            $genderCount['Women'] += $item['quantity'];
        }
    }
}

// Process order history to extract months and total amounts
$monthlyData = [];
foreach ($orderHistory as $orderItem) {
    $month = date('Y-m', strtotime($orderItem['date'])); // Format the date as "YYYY-MM"
    $totalAmount = (float)$orderItem['totalAmount'];

    // Aggregate totalAmount by month
    if (isset($monthlyData[$month])) {
        $monthlyData[$month] += $totalAmount;
    } else {
        $monthlyData[$month] = $totalAmount;
    }
}
ksort($monthlyData);

// Convert the associative array to a format suitable for JavaScript
$monthlyLabels = json_encode(array_keys($monthlyData)); // Months
$monthlyValues = json_encode(array_values($monthlyData)); // Total amounts

// Calculate average spending per order
$averageSpendingPerOrder = 0;
if(count($orderHistory) != 0) {
    $totalAmountSpent = array_sum(array_column($orderHistory, 'totalAmount'));
    $averageSpendingPerOrder = $totalAmountSpent / count($orderHistory);
}

// Calculate total orders across months
$totalOrdersPerMonth = [];
foreach ($orderHistory as $orderItem) {
    $month = date('Y-m', strtotime($orderItem['date'])); // Format the date as "YYYY-MM"

    // Count orders by month
    if (isset($totalOrdersPerMonth[$month])) {
        $totalOrdersPerMonth[$month]++;
    } else {
        $totalOrdersPerMonth[$month] = 1;
    }
}
ksort($totalOrdersPerMonth);

// Convert the associative array to a format suitable for JavaScript
$totalOrdersLabels = json_encode(array_keys($totalOrdersPerMonth)); // Months
$totalOrdersValues = json_encode(array_values($totalOrdersPerMonth)); // Total orders

// Initialize color counts
$colorCount = [];

// Loop through each order in the user's order history
foreach ($orderHistory as $orderItem) {
    $orderDetails = $order->getOrderHistoryById($orderItem['orderID']);

    foreach ($orderDetails['orderItems'] as $item) {
        $color = $item['colour'] ?? 'Unknown'; // Assume 'color' field exists in order items

        // Count items based on color
        if (isset($colorCount[$color])) {
            $colorCount[$color] += $item['quantity'];
        } else {
            $colorCount[$color] = $item['quantity'];
        }
    }
}
if (empty($colorCount)) {
    $topFavoriteColor = 'No color data available';
    $topFavoriteColorCount = 0;
} else {
    // Sort colors by count in descending order
    arsort($colorCount);

    // Get the top favorite color
    $topFavoriteColor = array_key_first($colorCount);
    $topFavoriteColorCount = $colorCount[$topFavoriteColor];
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
            margin-bottom: 20px;
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
        @media (max-width: 768px) {
            .dashboard-grid {
                grid-template-columns: repeat(2, minmax(200px, 1fr));
                gap: 15px;
                margin: 20px;
            }

            .card {
                height: auto; /* Adjust height for smaller screens */
                padding: 15px;
                height: 400px;
            }

            .stat-card {
                grid-column: span 2;
            }
        }

        @media (max-width: 480px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
                gap: 10px;
                margin: 10px;
            }

            .card {
                padding: 10px;
                height: 400px;
            }
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
        <div class="dashboard-grid">
            <!-- Total Amount Spent -->
            <div class="card stat-card">
                <h2>Total Amount Spent across Month</h2>
                <canvas id="timeSeriesChart"></canvas>
            </div>

            <div class="card stat-card">
                <div class="stat-item">
                        <h2>Total Amount Spent</h2>
                        <div class="stat-value">RM <?php echo number_format($totalAmountSpent, 2); ?></div>
                        <div class="stat-label">Across All Orders</div>
                </div>

                <div class="stat-item">
                    <h2>Average Spending Per Order</h2>
                    <div class="stat-value">RM <?php echo number_format($averageSpendingPerOrder, 2); ?></div>
                    <div class="stat-label">Across All Orders</div>
                </div>
                <div class="stat-item">
                    <h2>Top Favorite Color</h2>
                    <div class="stat-value"><?php echo htmlspecialchars($topFavoriteColor); ?></div>
                    <div class="stat-label">Count: <?php echo $topFavoriteColorCount; ?></div>
                </div>
            </div>

            <!-- Gender Distribution Chart -->
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

        // Gender Pie Chart
        const genderLabels = <?php echo json_encode(array_keys($genderCount)); ?>;
        const genderData = <?php echo json_encode(array_values($genderCount)); ?>;

        const genderCtx = document.getElementById('genderPieChart').getContext('2d');
        new Chart(genderCtx, {
            type: 'pie',
            data: {
                labels: genderLabels,
                datasets: [{
                    data: genderData,
                    backgroundColor: ['#36A2EB', '#FF6384'],
                    hoverOffset: 10
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    title: {
                        display: true,
                        text: 'Purchase Distribution by Gender'
                    }
                }
            }
        });

         // Monthly Data from PHP
        const monthlyLabels = <?php echo $monthlyLabels; ?>; // Months
        const monthlyValues = <?php echo $monthlyValues; ?>; // Total amounts

        // Monthly Line Chart
        const monthlyChartCtx = document.getElementById('timeSeriesChart').getContext('2d');
        new Chart(monthlyChartCtx, {
            type: 'line',
            data: {
                labels: monthlyLabels,
                datasets: [{
                    label: 'Total Amount Spent (RM)',
                    data: monthlyValues,
                    borderColor: '#36A2EB',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    title: {
                        display: true,
                        text: 'Monthly Total Amount Spent'
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Month'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Total Amount (RM)'
                        },
                        beginAtZero: true,
                    
                    }
                },
                responsive: true,
                maintainAspectRatio: false
            }
        });

        const labels = <?php echo $totalOrdersLabels; ?>;
        const values = <?php echo $totalOrdersValues; ?>;

        // Set up the bar chart
        const ctx = document.getElementById('ordersPerMonthChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar', // Type of chart - bar chart
            data: {
                labels: labels, // X-axis labels (months)
                datasets: [{
                    label: 'Total Orders per Month',
                    data: values, // Data for each bar (total orders)
                    backgroundColor: '#36A2EB', // Bar color
                    borderColor: '#36A2EB', // Border color of the bars
                    borderWidth: 1, // Border width
                    barThickness: 60
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Total Orders per Month' // Title of the chart
                    },
                    legend: {
                        display: false // Disable legend
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Month' // Label for the X-axis
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Total Orders' // Label for the Y-axis
                        },
                        beginAtZero: true // Start Y-axis from 0
                    }
                }
            }
        });
    </script>
</body>
</html>

