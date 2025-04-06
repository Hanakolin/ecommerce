<?php
session_start();
include '../include/config.php';
// Check if saller is logged in
if (!isset($_SESSION['saller_id'])) {
    header("Location: saller_login.php");
    exit();
}

$saller_id = $_SESSION['saller_id'];

// Get filter parameters
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';
$date_from = isset($_GET['date_from']) ? $_GET['date_from'] : '';
$date_to = isset($_GET['date_to']) ? $_GET['date_to'] : '';

// Build the base query
$query = "SELECT o.*, p.product_name as product_name, c.costumer_name as customer_name 
          FROM orders o 
          JOIN product p ON o.product_id = p.product_id
          JOIN costumer c ON o.customer_id = c.costumer_id
          WHERE o.saller_id = ?";

$params = array($saller_id);
$types = "i";

// Add status filter if not 'all'
if ($status_filter !== 'all') {
    $query .= " AND o.status = ?";
    $params[] = $status_filter;
    $types .= "s";
}

// Add date range filter
if (!empty($date_from) && !empty($date_to)) {
    $query .= " AND DATE(o.order_date) BETWEEN ? AND ?";
    $params[] = $date_from;
    $params[] = $date_to;
    $types .= "ss";
}

// Complete the query with sorting
$query .= " ORDER BY o.order_date DESC";

// Prepare and execute the query
$stmt = $conn->prepare($query);

if ($types === "i") {
    $stmt->bind_param($types, $params[0]);
} else {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Handle status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];
    $tracking_number = isset($_POST['tracking_number']) ? $_POST['tracking_number'] : null;
    
    $update_stmt = $conn->prepare("UPDATE orders SET status = ?, tracking_number = ? WHERE order_id = ? AND saller_id = ?");
    $update_stmt->bind_param("ssii", $new_status, $tracking_number, $order_id, $saller_id);
    
    if ($update_stmt->execute()) {
        $success_message = "Order status updated successfully!";
    } else {
        $error_message = "Failed to update order status.";
    }
    
    // Refresh the page to show updated status
    header("Location: saller_orders.php?status=" . urlencode($status_filter) . 
           "&date_from=" . urlencode($date_from) . 
           "&date_to=" . urlencode($date_to));
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management - <?php echo $_SESSION['shop_name']; ?></title>
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
        
        .filters {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .filter-group {
            display: flex;
            flex-direction: column;
        }
        
        .filter-group label {
            margin-bottom: 5px;
            font-size: 0.9rem;
            color: #6c757d;
        }
        
        .filter-group select, .filter-group input {
            padding: 8px 12px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            font-size: 0.9rem;
        }
        
        .btn {
            padding: 8px 15px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s;
            align-self: flex-end;
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
        
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        
        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            width: 100%;
            max-width: 500px;
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .modal-header h3 {
            font-size: 1.2rem;
        }
        
        .close-modal {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        
        .form-group select, .form-group input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ced4da;
            border-radius: 5px;
        }
        
        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }
        
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            display: none;
        }
        
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            display: none;
        }
        
        @media (max-width: 768px) {
            .dashboard {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                height: auto;
            }
            
            table {
                display: block;
                overflow-x: auto;
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
                <p>saller Dashboard</p>
            </div>
            <div class="sidebar-menu">
                <ul>
                    <li><a href="saller_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="saller_product.php"><i class="fas fa-box"></i> product</a></li>
                    <li><a href="saller_order.php" class="active"><i class="fas fa-shopping-cart"></i> Orders</a></li>
                    <li><a href="sales.php"><i class="fas fa-chart-line"></i> Sales</a></li>
                    <li><a href="saller_tracking.php"><i class="fas fa-truck"></i> Tracking</a></li>
                    <li><a href="saller_profile.php"><i class="fas fa-user"></i> Profile</a></li>
                    <li><a href="saller_settings.php"><i class="fas fa-cog"></i> Settings</a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Order Management</h1>
                <div class="user-menu">
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['saller_name']); ?>&background=random" alt="User">
                    <span><?php echo $_SESSION['saller_name']; ?></span>
                </div>
            </div>
            
            <?php if (isset($success_message)): ?>
            <div class="success-message" id="successMessage">
                <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
            </div>
            <?php endif; ?>
            
            <?php if (isset($error_message)): ?>
            <div class="error-message" id="errorMessage">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
            </div>
            <?php endif; ?>
            
            <div class="card">
                <div class="card-header">
                    <h2>Order Filters</h2>
                </div>
                <form method="GET" action="saller_orders.php">
                    <div class="filters">
                        <div class="filter-group">
                            <label for="status">Order Status</label>
                            <select id="status" name="status">
                                <option value="all" <?php echo $status_filter === 'all' ? 'selected' : ''; ?>>All Statuses</option>
                                <option value="pending" <?php echo $status_filter === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="processing" <?php echo $status_filter === 'processing' ? 'selected' : ''; ?>>Processing</option>
                                <option value="shipped" <?php echo $status_filter === 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                <option value="delivered" <?php echo $status_filter === 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                <option value="cancelled" <?php echo $status_filter === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label for="date_from">From Date</label>
                            <input type="date" id="date_from" name="date_from" value="<?php echo $date_from; ?>">
                        </div>
                        
                        <div class="filter-group">
                            <label for="date_to">To Date</label>
                            <input type="date" id="date_to" name="date_to" value="<?php echo $date_to; ?>">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                        <a href="saller_orders.php" class="btn btn-secondary">Reset</a>
                    </div>
                </form>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h2>Order List</h2>
                    <span><?php echo count($orders); ?> orders found</span>
                </div>
                
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Product</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Tracking</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($orders) > 0): ?>
                            <?php foreach ($orders as $order): ?>
                            <tr>
                                <td>#<?php echo $order['order_id']; ?></td>
                                <td><?php echo $order['customer_name']; ?></td>
                                <td><?php echo $order['product_name']; ?></td>
                                <td><?php echo date('M d, Y', strtotime($order['order_date'])); ?></td>
                                <td>$<?php echo number_format($order['total_price'], 2); ?></td>
                                <td>
                                    <span class="status status-<?php echo $order['status']; ?>">
                                        <?php echo ucfirst($order['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo $order['tracking_number'] ? $order['tracking_number'] : 'N/A'; ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-primary btn-sm update-status-btn" 
                                                data-order-id="<?php echo $order['order_id']; ?>"
                                                data-current-status="<?php echo $order['status']; ?>"
                                                data-tracking="<?php echo $order['tracking_number']; ?>">
                                            Update
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" style="text-align: center;">No orders found matching your criteria</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Update Status Modal -->
    <div class="modal" id="updateStatusModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Update Order Status</h3>
                <button class="close-modal">&times;</button>
            </div>
            <form id="updateStatusForm" method="POST">
                <input type="hidden" name="order_id" id="modalOrderId">
                <input type="hidden" name="update_status" value="1">
                
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="modalStatus" required>
                        <option value="pending">Pending</option>
                        <option value="processing">Processing</option>
                        <option value="shipped">Shipped</option>
                        <option value="delivered">Delivered</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                
                <div class="form-group" id="trackingNumberGroup">
                    <label for="tracking_number">Tracking Number</label>
                    <input type="text" name="tracking_number" id="modalTrackingNumber" placeholder="Enter tracking number">
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Show/hide success/error messages
            const successMessage = document.getElementById('successMessage');
            const errorMessage = document.getElementById('errorMessage');
            
            if (successMessage) {
                successMessage.style.display = 'block';
                setTimeout(() => {
                    successMessage.style.display = 'none';
                }, 5000);
            }
            
            if (errorMessage) {
                errorMessage.style.display = 'block';
                setTimeout(() => {
                    errorMessage.style.display = 'none';
                }, 5000);
            }
            
            // Modal functionality
            const modal = document.getElementById('updateStatusModal');
            const openModalButtons = document.querySelectorAll('.update-status-btn');
            const closeModalButtons = document.querySelectorAll('.close-modal');
            const statusSelect = document.getElementById('modalStatus');
            const trackingNumberGroup = document.getElementById('trackingNumberGroup');
            
            // Show tracking number field only when status is "shipped"
            statusSelect.addEventListener('change', function() {
                trackingNumberGroup.style.display = this.value === 'shipped' ? 'block' : 'none';
            });
            
            // Open modal
            openModalButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const orderId = this.getAttribute('data-order-id');
                    const currentStatus = this.getAttribute('data-current-status');
                    const trackingNumber = this.getAttribute('data-tracking');
                    
                    document.getElementById('modalOrderId').value = orderId;
                    document.getElementById('modalStatus').value = currentStatus;
                    document.getElementById('modalTrackingNumber').value = trackingNumber || '';
                    
                    // Show/hide tracking number based on current status
                    trackingNumberGroup.style.display = currentStatus === 'shipped' ? 'block' : 'none';
                    
                    modal.style.display = 'flex';
                });
            });
            
            // Close modal
            closeModalButtons.forEach(button => {
                button.addEventListener('click', function() {
                    modal.style.display = 'none';
                });
            });
            
            // Close modal when clicking outside
            window.addEventListener('click', function(event) {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>