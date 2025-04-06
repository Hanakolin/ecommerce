<?php
// filepath: d:\xampp\htdocs\PHP\e-commerce\admin\sales.php
session_start();
include '../include/config.php';

// Check if seller is logged in
if (!isset($_SESSION['saller_id'])) {
    header("Location: sallerlogin.php");
    exit();
}

// Get seller ID
$saller_id = $_SESSION['saller_id'];

// Fetch sales data for the seller
$sales_stmt = $conn->prepare("
    SELECT p.product_name, COUNT(o.order_id) AS sales_count 
    FROM orders o 
    JOIN product p ON o.product_id = p.product_id 
    WHERE o.saller_id = ? 
    GROUP BY p.product_name 
    ORDER BY sales_count DESC
");
$sales_stmt->bind_param("i", $saller_id);
$sales_stmt->execute();
$sales_data = $sales_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Prepare data for the bar graph
$product_names = [];
$sales_counts = [];
foreach ($sales_data as $data) {
    $product_names[] = $data['product_name'];
    $sales_counts[] = $data['sales_count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 0;
        }

        .dashboard {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background-color: #343a40;
            color: #ffffff;
            height: 100vh;
            padding: 20px 0;
            position: fixed;
            top: 0;
            left: 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .sidebar-header {
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }

        .sidebar-header h3 {
            font-size: 1.2rem;
            color: #ffffff;
        }

        .sidebar-header p {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
        }

        .sidebar-menu ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li {
            margin-bottom: 10px;
        }

        .sidebar-menu a {
            display: block;
            padding: 10px 20px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: #ffffff;
        }

        .sidebar-menu i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        /* Main Content Styles */
        .main-content {
            margin-left: 250px;
            padding: 20px;
            background-color: #ffffff;
            min-height: 100vh;
        }

        /* Table Container */
        .table-container {
            margin-bottom: 30px;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 1rem;
        }

        table th, table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #f8f9fa;
            font-weight: bold;
            font-size: 1.1rem;
        }

        table td {
            color: #343a40;
        }

        /* Chart Container */
        .chart-container {
            width: 100%;
            max-width: 1200px;
            height: 600px; /* Increased height */
            margin: 30px auto 0;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }

            .main-content {
                margin-left: 200px;
            }

            .chart-container, .table-container {
                padding: 20px;
            }

            table th, table td {
                padding: 10px;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 576px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main-content {
                margin-left: 0;
            }

            .chart-container, .table-container {
                padding: 15px;
            }

            table th, table td {
                padding: 8px;
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h3><?php echo $_SESSION['shop_name']; ?></h3>
                <p>Seller Dashboard</p>
            </div>
            <div class="sidebar-menu">
                <ul>
                    <li><a href="saller_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="saller_product.php"><i class="fas fa-box"></i> Products</a></li>
                    <li><a href="saller_order.php"><i class="fas fa-shopping-cart"></i> Orders</a></li>
                    <li><a href="sales.php" class="active"><i class="fas fa-chart-line"></i> Sales</a></li>
                    <li><a href="saller_tracking.php"><i class="fas fa-truck"></i> Tracking</a></li>
                    <li><a href="saller_profile.php"><i class="fas fa-user"></i> Profile</a></li>
                    <li><a href="saller_settings.php"><i class="fas fa-cog"></i> Settings</a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <h1>Sales Report</h1>

            <!-- Sales Data Table -->
            <div class="table-container">
                <h2>Sales Data</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Sales Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sales_data as $data): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($data['product_name']); ?></td>
                                <td><?php echo htmlspecialchars($data['sales_count']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($sales_data)): ?>
                            <tr>
                                <td colspan="2">No sales data available.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Sales Chart -->
            <div class="chart-container">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($product_names); ?>,
                datasets: [{
                    label: 'Sales Count',
                    data: <?php echo json_encode($sales_counts); ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>