<?php
// Start session only if it is not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plants</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <style>
        body {
            background-color: black;
            color: white;
            margin: 0;
            padding-top: 80px;
        }

        header {
            background-color: black;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
        }

        nav a {
            color: white;
            background-color: black;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        nav a:hover {
            color: white;
            background-color: #686D76;
        }

        .search-input {
            background-color: black;
            color: white;
            border: 1px solid white;
            padding: 10px;
            border-radius: 5px;
        }

        .search-input::placeholder {
            color: white;
        }

        .search-button img {
            filter: invert(1);
        }

        .logo img {
            filter: invert(1);
        }
    </style>
</head>

<body>
    <header class="bg-black shadow-md">
        <div class="container mx-auto px-4 py-4 flex flex-wrap items-center justify-between">
            <!-- Logo -->
            <div class="logo flex items-center gap-2">
                <img src="../assets/backgroung_pic/de.png" alt="logo" class="w-12 h-12">
                <span class="text-xl font-bold">Plant</span>
            </div>
            <div><a href="../include/costumer_home.php">Home</a></div>
            <div><a href="../admin/costumer_dashboard.php">profile</a></div>

            <!-- Search Bar -->
            <div class="search-bar flex items-center gap-2 mt-4 md:mt-0">
                <form action="/search" method="GET" class="flex items-center gap-2">
                    <input type="text" name="query" placeholder="Search..." required class="search-input w-48 md:w-64">
                    <button type="submit" class="search-button">
                        <img src="../assets/backgroung_pic/sear.png" alt="search" class="w-5 h-5">
                    </button>
                </form>
            </div>

            <!-- Cart Icon -->
            <a href="../include/cart.php" class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span class="ml-1">
                    <?php 
                    echo isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0;
                    ?>
                </span>
            </a>
        </div>
    </header>
</body>

</html>
