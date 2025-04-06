<?php
// filepath: d:\xampp\htdocs\PHP\e-commerce\admin\saller_product.php
session_start();
include '../include/config.php';

// Check if seller is logged in
if (!isset($_SESSION['saller_id'])) {
    header("Location: sallerlogin.php");
    exit();
}

// Get seller ID
$saller_id = $_SESSION['saller_id'];

// Fetch seller's products
$stmt = $conn->prepare("SELECT * FROM product WHERE saller_id = ?");
$stmt->bind_param("i", $saller_id);
$stmt->execute();
$products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Products</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Sidebar and Main Content Styles */
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

        .main-content {
            margin-left: 250px;
            padding: 20px;
            background-color: #f5f7fa;
            min-height: 100vh;
        }

        /* Add New Product Button */
        .add-product-button {
            display: inline-block;
            background-color: #343a40;
            color: #ffffff;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .add-product-button:hover {
            background-color: #23272b;
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
        }

        /* Product Grid */
        .grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        /* Product Card */
        .product-card {
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
        }

        /* Image Container */
        .product-image-container {
            height: 200px; /* Fixed height for the image container */
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f8f9fa; /* Light background for empty space */
        }

        .product-image-container img {
            max-height: 100%; /* Ensure the image fits within the container */
            max-width: 100%; /* Maintain aspect ratio */
            object-fit: contain; /* Prevent distortion */
        }

        /* Product Details */
        .product-details {
            padding: 15px;
        }

        .product-title {
            font-size: 1rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 5px;
        }

        .product-category {
            font-size: 0.875rem;
            color: #16a34a;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .product-description {
            font-size: 0.875rem;
            color: #64748b;
            margin-bottom: 15px;
        }

        .product-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
        }

        .product-price {
            font-size: 1.125rem;
            font-weight: bold;
            color: #2563eb;
        }

        @media (max-width: 1200px) {
            .grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 768px) {
            .grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .sidebar {
                width: 200px;
            }

            .main-content {
                margin-left: 200px;
            }
        }

        @media (max-width: 576px) {
            .grid {
                grid-template-columns: 1fr;
            }

            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main-content {
                margin-left: 0;
            }

            .add-product-button {
                width: 100%;
                text-align: center;
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
                    <li><a href="saller_product.php" class="active"><i class="fas fa-box"></i> Products</a></li>
                    <li><a href="saller_order.php"><i class="fas fa-shopping-cart"></i> Orders</a></li>
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
            <div class="container">
                <div class="flex justify-between items-center mb-8">
                    <h1 class="text-3xl font-bold">My Products</h1>
                    <a href="../include/card_form.php" class="add-product-button">
                        Add New Product
                    </a>
                </div>

                <?php if (count($products) > 0): ?>
                    <div class="grid">
                        <?php foreach ($products as $product): ?>
                            <div class="product-card">
                                <!-- Product Image -->
                                <div class="product-image-container">
                                    <img src="<?php echo htmlspecialchars($product['image_path']); ?>" alt="Product Image">
                                </div>

                                <!-- Product Details -->
                                <div class="product-details">
                                    <h3 class="product-title"><?php echo htmlspecialchars($product['product_name']); ?></h3>
                                    <p class="product-category"><?php echo htmlspecialchars($product['category']); ?></p>
                                    <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
                                </div>

                                <!-- Product Footer -->
                                <div class="product-footer">
                                    <span class="product-price">$<?php echo htmlspecialchars($product['price']); ?></span>
                                    <span class="text-gray-500 text-sm">Stock: <?php echo htmlspecialchars($product['stock']); ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-gray-600">No products found. Click "Add New Product" to start adding products.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>