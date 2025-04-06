<?php
// cart.php
session_start();
include '../include/config.php';

// Handle remove from cart action
if (isset($_GET['remove'])) {
    $product_id = $_GET['remove'];
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
    header("Location: cart.php");
    exit();
}

// Handle quantity update
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    
    if ($quantity <= 0) {
        unset($_SESSION['cart'][$product_id]);
    } else {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <style>
        body {
            background-color: #1a1a1a;
            color: white;
        }
        .cart-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .cart-item {
            display: flex;
            border-bottom: 1px solid #444;
            padding: 20px 0;
            align-items: center;
        }
        .cart-item-image {
            width: 100px;
            height: 100px;
            object-fit: contain;
            margin-right: 20px;
        }
        .quantity-input {
            width: 50px;
            text-align: center;
            background-color: #333;
            color: white;
            border: 1px solid #555;
            padding: 5px;
        }
        .checkout-btn {
            background-color: #686D76;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }
        .checkout-btn:hover {
            background-color: #5a5e66;
        }
        .remove-btn {
            color: #ff4444;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <?php include './costumer_head.php'; ?>
    
    <div class="cart-container">
        <h1 class="text-3xl font-bold mb-8">Your Shopping Cart</h1>
        
        <?php if (empty($_SESSION['cart'])): ?>
            <p>Your cart is empty.</p>
        <?php else: ?>
            <div class="cart-items">
                <?php 
                $total = 0;
                foreach ($_SESSION['cart'] as $product_id => $item): 
                    $subtotal = $item['price'] * $item['quantity']; // Calculate subtotal
                    $total += $subtotal; // Add to total
                ?>
                    <div class="cart-item">
                        <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="cart-item-image">
                        <div class="flex-grow">
                            <h3 class="text-xl"><?php echo htmlspecialchars($item['name']); ?></h3>
                            <p class="text-green-500">$<?php echo number_format($item['price'], 2); ?> per unit</p>
                        </div>
                        <form method="post" action="cart.php" class="flex items-center">
                            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                            <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" class="quantity-input">
                            <button type="submit" name="update_quantity" class="ml-2">Update</button>
                        </form>
                        <div class="ml-8 text-xl">
                            $<?php echo number_format($subtotal, 2); ?> <!-- Display updated subtotal -->
                        </div>
                        <a href="cart.php?remove=<?php echo $product_id; ?>" class="remove-btn ml-8">×</a>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="mt-8 text-right">
                <h3 class="text-2xl">Total: $<?php echo number_format($total, 2); ?></h3>
                <button class="checkout-btn mt-4">Proceed to Checkout</button>
            </div>
        <?php endif; ?>
        
        <div class="mt-8">
            <a href="start.php" class="text-blue-400">← Continue Shopping</a>
        </div>
    </div>
    
</body>
</html>