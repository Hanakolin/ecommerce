<?php
// filepath: d:\xampp\htdocs\PHP\e-commerce\include\card.php

// // Start session only if it is not already started
// if (session_status() === PHP_SESSION_NONE) {
//     session_start();
// }

include '../include/config.php';

// Initialize a variable to store the success message
$success_message = "";

// Handle add to cart action
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    
    // Initialize cart if not exists
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    // Add product to cart or increment quantity
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += 1;
    } else {
        // Fetch product details from database
        $sql = "SELECT * FROM product WHERE product_id = $product_id";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $product = $result->fetch_assoc();
            $_SESSION['cart'][$product_id] = [
                'name' => $product['product_name'],
                'price' => $product['price'],
                'image' => $product['image_path'],
                'quantity' => 1
            ];
        }
    }
    
    // Set the success message
    $success_message = "Product added to cart successfully!";
}

// Fetch all products from the 'product' table
$sql = "SELECT * FROM product";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <style>
        body {
            background-color: #1a1a1a;
            color: white;
        }

        .product-card {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            border: 1px solid white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(96, 93, 93, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 10px rgba(78, 75, 75, 0.15);
        }

        .product-image-container {
            height: 200px;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f8f9fa;
        }

        .product-image-container img {
            max-height: 100%;
            max-width: 100%;
            object-fit: contain;
        }

        .product-details {
            padding: 15px;
        }

        .product-title {
            font-size: 1rem;
            font-weight: 600;
            color: white;
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
            color: white;
            margin-bottom: 15px;
        }

        .product-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .product-price {
            font-size: 1.125rem;
            font-weight: bold;
            color: #2563eb;
        }

        .add-to-cart-btn {
            background-color: #686D76;
            color: white;
            padding: 8px 16px;
            border-radius: 5px;
            font-size: 0.875rem;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        .add-to-cart-btn:hover {
            background-color: #5a5e66;
        }
    </style>
</head>
<body>
    <header>
        <!-- Your header content -->
    </header>
    <div class="container mx-auto py-10 px-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="block">
                    <div class="product-card">
                        <!-- Product Image -->
                        <div class="product-image-container">
                            <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="Product Image">
                        </div>

                        <!-- Product Details -->
                        <div class="product-details">
                            <h3 class="product-title"><?php echo htmlspecialchars($row['product_name']); ?></h3>
                            <p class="product-category"><?php echo htmlspecialchars($row['category']); ?></p>
                            <p class="product-description"><?php echo htmlspecialchars($row['description']); ?></p>
                        </div>

                        <!-- Product Footer -->
                        <div class="product-footer px-4 pb-4">
                            <span class="product-price">$<?php echo htmlspecialchars($row['price']); ?></span>
                            <form method="post" action="">
                                <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                                <button type="submit" name="add_to_cart" class="add-to-cart-btn">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>