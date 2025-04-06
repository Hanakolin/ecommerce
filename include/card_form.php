<?php
include '../include/config.php';
session_start();

// Check if seller is logged in
if (!isset($_SESSION['saller_id'])) {
    header("Location: ../admin/sallerlogin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $saller_id = $_SESSION['saller_id']; // Get seller ID from session
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? 0;
    $stock = $_POST['stock'] ?? 0;
    $category = $_POST['category'] ?? '';
    $image_path = '';

    // Handle file upload
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../assets/upload/';
        $file_name = basename($_FILES['file']['name']);
        $target_file = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
            $image_path = $target_file;
        }
    }

    // Insert product into the database
    $stmt = $conn->prepare("INSERT INTO product (saller_id, product_name, description, price, stock, category, image_path) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issdiss", $saller_id, $name, $description, $price, $stock, $category, $image_path);

    if ($stmt->execute()) {
        header("Location: ../admin/saller_product.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: rgb(31, 32, 39);
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
            text-align: center;
        }

        .sidebar-header h3 {
            color: var(--light-color);
            font-size: 1.2rem;
        }

        .sidebar-header p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.8rem;
        }

        .sidebar-menu ul {
            list-style: none;
            padding: 0;
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

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
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
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            position: relative;
        }

        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            background-color: var(--primary-color);
            color: var(--light-color);
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 0.9rem;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s;
        }

        .back-button:hover {
            background-color: rgb(61, 63, 74);
        }

        .form-container {
            width: 100%;
            max-width: 500px;
            background-color: var(--light-color);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .form-container h1 {
            font-size: 1.5rem;
            color: var(--primary-color);
            text-align: center;
            margin-bottom: 20px;
        }

        .form-container input,
        .form-container textarea,
        .form-container button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        .form-container input:focus,
        .form-container textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 5px rgba(45, 46, 52, 0.5);
        }

        .form-container button {
            background-color: var(--primary-color);
            color: var(--light-color);
            border: none;
            cursor: pointer;
            transition: all 0.3s;
        }

        .form-container button:hover {
            background-color: rgb(61, 63, 74);
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h3>Seller Dashboard</h3>
                <p>Add Product</p>
            </div>
            <div class="sidebar-menu">
                <ul>
                    <li><a href="../admin/saller_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="../admin/saller_product.php"><i class="fas fa-box"></i> Products</a></li>
                    <li><a href="../admin/saller_order.php"><i class="fas fa-shopping-cart"></i> Orders</a></li>
                    <li><a href="../admin/sales.php"><i class="fas fa-chart-line"></i> Sales</a></li>
                    <li><a href="../admin/saller_tracking.php"><i class="fas fa-truck"></i> Tracking</a></li>
                    <li><a href="../admin/saller_profile.php"><i class="fas fa-user"></i> Profile</a></li>
                    <li><a href="../admin/saller_settings.php"><i class="fas fa-cog"></i> Settings</a></li>
                    <li><a href="../admin/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <a href="../admin/saller_product.php" class="back-button">Back to Products</a>
            <div class="form-container">
                <h1>Add Product</h1>
                <form method="POST" enctype="multipart/form-data">
                    <input type="text" name="name" placeholder="Enter product name" required>
                    <textarea name="description" placeholder="Enter product description" rows="4" required></textarea>
                    <input type="number" name="price" placeholder="Enter product price" step="0.01" required>
                    <input type="number" name="stock" placeholder="Enter stock quantity" required>
                    <input type="text" name="category" placeholder="Enter product category" required>
                    <input type="file" name="file" accept="image/*,video/*" required>
                    <button type="submit">Add Product</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>