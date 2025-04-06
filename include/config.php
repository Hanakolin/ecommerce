<?php
// filepath: d:\xampp\htdocs\PHP\e-commerce\include\config.php

// Check if constants are already defined
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
?>