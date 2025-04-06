<?php
// filepath: d:\xampp\htdocs\PHP\e-commerce\admin\log_out.php
session_start();

// Destroy the session
session_unset();
session_destroy();

// Redirect to the start.php page
header("Location: ../admin/start.php");
exit();
?>