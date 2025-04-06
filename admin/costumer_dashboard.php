<?php
// filepath: d:\xampp\htdocs\PHP\e-commerce\admin\costumer\costumer_dashboard.php

session_start();
 // Include the database configuration
 if (!defined('DB_HOST')) {
    define('DB_HOST', 'localhost');
}

if (!defined('DB_USERNAME')) {
    define('DB_USERNAME', 'root');
}

if (!defined('DB_PASSWORD')) {
    define('DB_PASSWORD', '');
}

if (!defined('DB_NAME')) {
    define('DB_NAME', 'e-commerce');
}

// Create a database connection
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Check if the user is logged in
// if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'customer') {
//     header("Location: ../sallerlogin.php"); // Redirect to login page
//     exit();
// }

// Get costumer details
$costumerId = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM costumer WHERE costumer_id = ?");
$stmt->bind_param("i", $costumerId);
$stmt->execute();
$costumer = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Get recent orders
$stmt = $conn->prepare("SELECT * FROM orders WHERE costumer_id = ? ORDER BY order_date DESC LIMIT 5");
$stmt->bind_param("i", $costumerId);
$stmt->execute();
$orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Costumer Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Styling for the dashboard */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background-color: rgb(56, 51, 51);
            color: white;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        header {
            width: 100%;
            background-color: #1a1a1a;
            box-shadow: 0 2px 10px rgba(255, 255, 255, 0.1);
        }
        .main-content {
            flex: 1;
            padding: 20px;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #444444;
        }
        .dashboard-header h1 {
            color: white;
        }
        .welcome-message {
            color: #cccccc;
        }
        .dashboard-grid {
            display: grid;
            grid-template-columns: 250px 1fr;
            gap: 20px;
        }
        .sidebar {
            background-color: #2c2c2c;
            border-radius: 10px;
            padding: 20px;
        }
        .sidebar-menu {
            list-style: none;
        }
        .sidebar-menu li {
            margin-bottom: 10px;
        }
        .sidebar-menu a {
            display: block;
            padding: 10px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .sidebar-menu a:hover, .sidebar-menu a.active {
            background-color: #4CAF50;
        }
        .sidebar-menu i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        .dashboard-content {
            background-color: #2c2c2c;
            border-radius: 10px;
            padding: 20px;
        }
        .section-title {
            color: white;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #444444;
        }
        .orders-table {
            width: 100%;
            border-collapse: collapse;
        }
        .orders-table th, .orders-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #444444;
        }
        .orders-table th {
            background-color: #1a1a1a;
            color: white;
        }
        .status-pending {
            color: #ffc107;
        }
        .status-completed {
            color: #28a745;
        }
        .status-cancelled {
            color: #dc3545;
        }
        .btn {
            padding: 8px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .btn-outline {
            background-color: transparent;
            border: 1px solid #4CAF50;
        }
        .btn-outline:hover {
            background-color: rgba(76, 175, 80, 0.2);
        }
        .account-info {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        .info-card {
            background-color: #1a1a1a;
            border-radius: 10px;
            padding: 20px;
        }
        .info-card h3 {
            color: #4CAF50;
            margin-bottom: 15px;
        }
        .info-card p {
            margin-bottom: 8px;
        }
        footer {
            width: 100%;
            background-color: #1a1a1a;
            padding: 20px;
            text-align: center;
            box-shadow: 0 -2px 10px rgba(255, 255, 255, 0.1);
            color: white;
        }
    </style>
</head>
<body>
<?php include'../include/costumer_head.php'; ?>
    
    
    <div class="main-content">
        <div class="dashboard-header">
            <div>
                <h1>Costumer Dashboard</h1>
                <p class="welcome-message">Welcome back, <?php echo htmlspecialchars($costumer['costumer_name']); ?>!</p>
            </div>
            <a href="log_out.php" class="btn btn-outline">Logout</a>
        </div>
        
        <div class="dashboard-grid">
            <div class="sidebar">
                <ul class="sidebar-menu">
                    <li><a href="costumer_dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="orders.php"><i class="fas fa-shopping-bag"></i> My Orders</a></li>
                    <li><a href="wishlist.php"><i class="fas fa-heart"></i> Wishlist</a></li>
                    <li><a href="addresses.php"><i class="fas fa-map-marker-alt"></i> Addresses</a></li>
                    <li><a href="account_settings.php"><i class="fas fa-user-cog"></i> Account Settings</a></li>
                </ul>
            </div>
            
            <div class="dashboard-content">
                <div class="account-info">
                    <div class="info-card">
                        <h3>Personal Information</h3>
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($costumer['first_name'] . ' ' . $costumer['last_name']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($costumer['email']); ?></p>
                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($costumer['phone'] ?? 'Not provided'); ?></p>
                        <a href="account_settings.php" class="btn">Edit Profile</a>
                    </div>
                    
                    <div class="info-card">
                        <h3>Default Address</h3>
                        <?php if (!empty($costumer['address'])): ?>
                            <p><?php echo nl2br(htmlspecialchars($costumer['address'])); ?></p>
                        <?php else: ?>
                            <p>No address saved</p>
                        <?php endif; ?>
                        <a href="addresses.php" class="btn">Manage Addresses</a>
                    </div>
                </div>
                
                <h2 class="section-title">Recent Orders</h2>
                <?php if (!empty($orders)): ?>
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Date</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                                    <td><?php echo date('M j, Y', strtotime($order['order_date'])); ?></td>
                                    <td><?php echo htmlspecialchars($order['item_count']); ?></td>
                                    <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                                    <td class="status-<?php echo strtolower($order['status']); ?>">
                                        <?php echo htmlspecialchars($order['status']); ?>
                                    </td>
                                    <td><a href="order_details.php?id=<?php echo $order['order_id']; ?>" class="btn btn-outline">View</a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div style="text-align: center; margin-top: 20px;">
                        <a href="orders.php" class="btn">View All Orders</a>
                    </div>
                <?php else: ?>
                    <p>You haven't placed any orders yet.</p>
                    <a href="../products.php" class="btn">Start Shopping</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
   <?php include'../include/footer.php'; ?>
    
    
</body>
</html>