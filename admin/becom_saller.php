<?php
session_start();
include '../include/config.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Corrected table name
    $stmt = $conn->prepare("SELECT * FROM saller WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $saller = $result->fetch_assoc();
        if (password_verify($password, $saller['password'])) {
            $_SESSION['saller_id'] = $saller['saller_id'];
            $_SESSION['saller_name'] = $saller['name'];
            $_SESSION['shop_name'] = $saller['shop_name'];
            header("Location: saller_dashboard.php");
            exit();
        } else {
            $error = "Invalid email or password";
        }
    } else {
        $error = "Invalid email or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Become a Seller</title>
    <link rel="icon" type="image/png" href="../assets/backgroung_pic/de.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #1a1a1a; /* Match the reference page background */
            color: white; /* Default text color to white */
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header {
            width: 100%;
            background-color: #1a1a1a; /* Match header background */
            box-shadow: 0 2px 10px rgba(255, 255, 255, 0.1);
        }

        main {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
            text-align: center;
        }

        main h1 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        main p {
            font-size: 1rem;
            color: #cccccc; /* Light gray for subtext */
            margin-bottom: 30px;
        }

        .login-container {
            background-color: #2c2c2c; /* Slightly lighter gray for contrast */
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 400px;
            padding: 40px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h1 {
            font-size: 1.5rem;
            font-weight: bold;
            color: white; /* White text for header */
            margin-bottom: 10px;
        }

        .login-header p {
            font-size: 1rem;
            color: #cccccc; /* Light gray for subtext */
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: white; /* White text for labels */
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #444444; /* Dark gray border */
            border-radius: 5px;
            font-size: 16px;
            background-color: #1a1a1a; /* Match input background color */
            color: white; /* White text */
            transition: border-color 0.3s;
        }

        .form-control:focus {
            border-color: #686D76; /* Match hover color */
            outline: none;
        }

        .btn {
            width: 100%;
            padding: 12px;
            background-color: #686D76; /* Button background color */
            color: white; /* Button text color */
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #5a5e66; /* Slightly darker hover effect */
        }

        .error-message {
            color: #dc3545; /* Red for error messages */
            margin-bottom: 20px;
            text-align: center;
        }

        .login-footer {
            text-align: center;
            margin-top: 20px;
            color: #cccccc; /* Light gray for footer text */
        }

        .login-footer a {
            color: #686D76; /* Match button color */
            text-decoration: none;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }

        footer {
            width: 100%;
            background-color: #1a1a1a; /* Match footer background */
            padding: 20px;
            text-align: center;
            box-shadow: 0 -2px 10px rgba(255, 255, 255, 0.1);
            color: white; /* Footer text color */
        }
    </style>
</head>
<body>
    <?php include '../include/head.php'; ?>

    <main>
        <h1>Become a Seller Today!</h1>
        <p>Create a seller account to begin your journey in global sales.</p>

        <!-- Login Form -->
        <div class="login-container">
            <div class="login-header">
                <h1>Seller Login</h1>
                <p>Access your seller dashboard</p>
            </div>
            
            <?php if (!empty($error)): ?>
            <div class="error-message">
                <?php echo $error; ?>
            </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                
                <button type="submit" class="btn">Login</button>
            </form>
            
            <div class="login-footer">
                Don't have an account? <a href="saller_regeatation.php">Register here</a>
            </div>
        </div>
    </main>

    <?php include '../include/footer.php'; ?>
</body>
</html>