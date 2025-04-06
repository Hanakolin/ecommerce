<?php

include '../include/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Duraz Seller Center</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        
        body {
            display: flex;
            min-height: 100vh;
            background-color: #f5f5f5;
        }
        
        .sidebar {
            width: 250px;
            background-color: white;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        
        .main-content {
            flex: 1;
            padding: 20px;
        }
        
        .logo {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 30px;
            color: #333;
        }
        
        .menu {
            list-style: none;
            margin-bottom: 30px;
        }
        
        .menu li {
            padding: 10px 0;
            color: #555;
            cursor: pointer;
        }
        
        .menu li:hover {
            color: #0066cc;
        }
        
        h2 {
            margin: 20px 0;
            color: #333;
        }
        
        .card {
            background-color: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .card h3 {
            margin-bottom: 10px;
            color: #333;
        }
        
        .card p {
            color: #666;
            margin-bottom: 10px;
        }
        
        .stats {
            color: #888;
            font-size: 14px;
            margin-top: 10px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        table td {
            padding: 8px;
            text-align: center;
            border: 1px solid #eee;
        }
        
        .footer {
            margin-top: 30px;
            color: #888;
            font-size: 12px;
            text-align: center;
        }
        
        .footer-links {
            margin-top: 10px;
        }
        
        .footer-links a {
            color: #0066cc;
            text-decoration: none;
            margin: 0 5px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo">Duraz Seller Center</div>
        <ul class="menu">
            <li>Products</li>
            <li>Assortment Growth</li>
            <li>Orders and Reviews</li>
            <li>Marketing Center</li>
            <li>Marketing Solutions</li>
            <li>Data Insight</li>
            <li>Learn and Grow</li>
            <li>Store</li>
            <li>Finance</li>
            <li>Setting and Support</li>
            <li>My Account</li>
        </ul>
    </div>
    
    <div class="main-content">
        <h2>To Do</h2>
        
        <div class="card">
            <h3>Email</h3>
            <ul>
                <li>Address</li>
                <li>ID Bank</li>
                <li>Product</li>
            </ul>
        </div>
        
        <div class="card">
            <h3>Add a pick-up address</h3>
            <p>no video</p>
        </div>
        
        <div class="card">
            <h3>Add</h3>
            <p>Adding one will be used for fulfillment of your orders</p>
        </div>
        
        <div class="card">
            <h3>Duraz University</h3>
            <p>C Load New Recommendation</p>
            <p>Recommendation based on previous learning history</p>
        </div>
        
        <div class="card">
            <h3>Setting Holiday Mode for your Shop</h3>
            <div class="stats">☉ 11,683</div>
        </div>
        
        <div class="card">
            <h3>Product Rejection Reasons</h3>
            <div class="stats">☉ 9,535</div>
        </div>
        
        <div class="card">
            <h3>Popular Toolkit</h3>
            <table>
                <tr>
                    <td>Marketing Solutions</td>
                    <td>Regular Voucher</td>
                    <td>Free Shipping</td>
                    <td>Education Livestream</td>
                </tr>
            </table>
        </div>
        
        <div class="card">
            <h3>Project Spaw Policy</h3>
            <ul>
                <li>Policies & Guidelines</li>
                <li>Price Spam Policy</li>
            </ul>
            <div class="stats">☉ 3,693</div>
        </div>
        
        <div class="card">
            <h3>BUSINESS Advisor</h3>
            <p>Introducing Business Advisor</p>
            <div class="stats">☉ 6,684</div>
        </div>
        
        <div class="footer">
            <div>Duraz 2024. All rights reserved.</div>
            <div class="footer-links">
                <a href="#">Duraz University</a>
                <a href="#">Help Center</a>
                <a href="#">Duraz Seller App</a>
            </div>
        </div>
    </div>
</body>
</html>