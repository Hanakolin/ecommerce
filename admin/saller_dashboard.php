<?php
session_start();
include '../include/config.php';

// Check if seller is logged in
if (!isset($_SESSION['saller_id'])) {
    header("Location: sallerlogin.php");
    exit();
}

// Get seller details
$saller_id = $_SESSION['saller_id'];
$stmt = $conn->prepare("SELECT * FROM saller WHERE saller_id = ?");
$stmt->bind_param("i", $saller_id);
$stmt->execute();
$saller = $stmt->get_result()->fetch_assoc();

// Get seller's product count
$product_stmt = $conn->prepare("SELECT COUNT(*) as product_count FROM product WHERE saller_id = ?");
$product_stmt->bind_param("i", $saller_id);
$product_stmt->execute();
$product_count = $product_stmt->get_result()->fetch_assoc()['product_count'];

// Get pending orders count
$pending_orders_stmt = $conn->prepare("SELECT COUNT(*) as pending_count FROM orders WHERE saller_id = ? AND status = 'pending'");
$pending_orders_stmt->bind_param("i", $saller_id);
$pending_orders_stmt->execute();
$pending_orders_count = $pending_orders_stmt->get_result()->fetch_assoc()['pending_count'];

// Get orders to ship count
$to_ship_stmt = $conn->prepare("SELECT COUNT(*) as to_ship_count FROM orders WHERE saller_id = ? AND status = 'processing'");
$to_ship_stmt->bind_param("i", $saller_id);
$to_ship_stmt->execute();
$to_ship_count = $to_ship_stmt->get_result()->fetch_assoc()['to_ship_count'];

// Get this month's revenue
$revenue_stmt = $conn->prepare("
    SELECT SUM(total_price) as monthly_revenue 
    FROM orders 
    WHERE saller_id = ? 
    AND MONTH(order_date) = MONTH(CURRENT_DATE()) 
    AND YEAR(order_date) = YEAR(CURRENT_DATE())
");
$revenue_stmt->bind_param("i", $saller_id);
$revenue_stmt->execute();
$monthly_revenue = $revenue_stmt->get_result()->fetch_assoc()['monthly_revenue'] ?? 0;

// Get recent orders
$recent_orders_stmt = $conn->prepare("
    SELECT o.*, p.product_name 
    FROM orders o 
    JOIN product p ON o.product_id = p.product_id 
    WHERE o.saller_id = ? 
    ORDER BY o.order_date DESC 
    LIMIT 5
");
$recent_orders_stmt->bind_param("i", $saller_id);
$recent_orders_stmt->execute();
$recent_orders = $recent_orders_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get products low in stock
$low_stock_stmt = $conn->prepare("
    SELECT * FROM product 
    WHERE saller_id = ? AND stock_quantity < 5 
    ORDER BY stock_quantity ASC 
    LIMIT 5
");
$low_stock_stmt->bind_param("i", $saller_id);
$low_stock_stmt->execute();
$low_stock_products = $low_stock_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>saller Dashboard - <?php echo $saller['shop_name']; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: #4a6bff;
            --secondary-color: #f8f9fa;
            --dark-color: #343a40;
            --light-color: #ffffff;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
        }
        
        .dashboard {
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: 250px;
            background-color: var(--dark-color);
            color: var(--light-color);
            padding: 20px 0;
            transition: all 0.3s;
        }
        
        .sidebar-header {
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-header h3 {
            color: var(--light-color);
            font-size: 1.2rem;
        }
        
        .sidebar-header p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.8rem;
        }
        
        .sidebar-menu {
            padding: 20px 0;
        }
        
        .sidebar-menu ul {
            list-style: none;
        }
        
        .sidebar-menu li {
            margin-bottom: 5px;
        }
        
        .sidebar-menu a {
            display: block;
            padding: 10px 20px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .sidebar-menu a:hover, .sidebar-menu a.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: var(--light-color);
        }
        
        .sidebar-menu i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        .main-content {
            flex: 1;
            padding: 20px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .header h1 {
            color: var(--dark-color);
            font-size: 1.8rem;
        }
        
        .user-menu {
            display: flex;
            align-items: center;
        }
        
        .user-menu img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
        
        .stats-cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background-color: var(--light-color);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-card i {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        
        .stat-card h3 {
            font-size: 1.5rem;
            margin-bottom: 5px;
        }
        
        .stat-card p {
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .card {
            background-color: var(--light-color);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .card-header h2 {
            font-size: 1.3rem;
            color: var(--dark-color);
        }
        
        .card-header a {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 0.9rem;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        table th, table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }
        
        table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }
        
        .status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-processing {
            background-color: #cce5ff;
            color: #004085;
        }
        
        .status-shipped {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-delivered {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        
        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .btn {
            padding: 8px 15px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #3a56d4;
        }
        
        .btn-sm {
            padding: 5px 10px;
            font-size: 0.8rem;
        }
        
        @media (max-width: 1200px) {
            .stats-cards {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .dashboard {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                height: auto;
            }
            
            .stats-cards {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h3><?php echo $saller['shop_name']; ?></h3>
                <p>saller Dashboard</p>
            </div>
            <div class="sidebar-menu">
                <ul>
                    <li><a href="saller_dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="saller_product.php"><i class="fas fa-box"></i> product</a></li>
                    <li><a href="saller_order.php"><i class="fas fa-shopping-cart"></i> Orders</a></li>
                    <li><a href="sales.php"><i class="fas fa-chart-line"></i> Sales</a></li>
                    <li><a href="saller_tracking.php"><i class="fas fa-truck"></i> Tracking</a></li>
                    <li><a href="saller_profile.php"><i class="fas fa-user"></i> Profile</a></li>
                    <li><a href="saller_settings.php"><i class="fas fa-cog"></i> Settings</a></li>
                    <li><a href="log_out.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Dashboard Overview</h1>
                <div class="user-menu">
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($saller['user_name']); ?>&background=random" alt="User">
                    <span><?php echo htmlspecialchars($saller['user_name']); ?></span>
                </div>
            </div>
            
            <!-- Stats Cards -->
            <div class="stats-cards">
                <div class="stat-card">
                    <i class="fas fa-box text-primary"></i>
                    <h3><?php echo $product_count; ?></h3>
                    <p>Total Products</p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-shopping-cart text-success"></i>
                    <h3><?php echo $pending_orders_count; ?></h3>
                    <p>Pending Orders</p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-truck text-warning"></i>
                    <h3><?php echo $to_ship_count; ?></h3>
                    <p>Orders to Ship</p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-dollar-sign text-danger"></i>
                    <h3>$<?php echo number_format($monthly_revenue, 2); ?></h3>
                    <p>This Month's Revenue</p>
                </div>
            </div>
            
            <!-- Recent Orders -->
            <div class="card">
                <div class="card-header">
                    <h2>Recent Orders</h2>
                    <a href="saller_orders.php">View All</a>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_orders as $order): ?>
                        <tr>
                            <td>#<?php echo $order['order_id']; ?></td>
                            <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                            <td><?php echo $order['quantity']; ?></td>
                            <td>$<?php echo number_format($order['total_price'], 2); ?></td>
                            <td>
                                <span class="status status-<?php echo $order['status']; ?>">
                                    <?php echo ucfirst($order['status']); ?>
                                </span>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($order['order_date'])); ?></td>
                            <td>
                                <button class="btn btn-primary btn-sm">View</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($recent_orders)): ?>
                        <tr>
                            <td colspan="7" style="text-align: center;">No recent orders</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Products Low in Stock -->
            <div class="card">
                <div class="card-header">
                    <h2>Products Low in Stock</h2>
                    <a href="saller_products.php">View All</a>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($low_stock_products as $product): ?>
                        <tr>
                            <td>#<?php echo $product['product_id']; ?></td>
                            <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                            <td>$<?php echo number_format($product['price'], 2); ?></td>
                            <td><?php echo $product['stock_quantity']; ?></td>
                            <td>
                                <button class="btn btn-primary btn-sm">Restock</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($low_stock_products)): ?>
                        <tr>
                            <td colspan="5" style="text-align: center;">No products low in stock</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // JavaScript for interactive elements
        document.addEventListener('DOMContentLoaded', function() {
            // You can add JavaScript functionality here
            // For example:
            // - AJAX calls to update order status
            // - Form validation for product management
            // - Interactive charts for sales data
            
            console.log("saller dashboard loaded");
        });
    </script>
</body>
</html>