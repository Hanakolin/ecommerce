<?php
session_start(); // Ensure session is started
include '../include/config.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Check if the user exists in the costumer table
    $stmt = $conn->prepare("SELECT 'customer' as user_type, costumer_id as id, costumer_name as first_name, email, password 
                            FROM costumer WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Set session variables for customer
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['user_type'] = $user['user_type'];
            $_SESSION['user_name'] = $user['first_name'];
            header("Location: costumer_dashboard.php");
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        // Check if the user exists in the saller table
        $stmt = $conn->prepare("SELECT 'seller' as user_type, saller_id as id, user_name as first_name, email, password, shop_name 
                                FROM saller WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Set session variables for seller
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['user_type'] = $user['user_type'];
                $_SESSION['user_name'] = $user['first_name'];
                $_SESSION['shop_name'] = $user['shop_name'];
                header("Location: saller_dashboard.php");
                exit();
            } else {
                $error = "Invalid email or password.";
            }
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Combined styling with purple accent for unified login */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: rgb(56, 51, 51);
            color: white;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        .main-content {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            width: 100%;
        }
        
        .login-container {
            background-color: #2c2c2c;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 400px;
            padding: 40px;
            margin: 20px 0;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-header h1 {
            color: white;
            margin-bottom: 10px;
        }
        
        .login-header p {
            color: #cccccc;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: white;
            font-weight: 500;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #444444;
            border-radius: 5px;
            font-size: 16px;
            background-color: #1a1a1a;
            color: white;
            transition: border-color 0.3s;
        }
        
        .form-control:focus {
            border-color: #9c27b0; /* Purple accent for unified login */
            outline: none;
        }
        
        .btn {
            width: 100%;
            padding: 12px;
            background-color: #9c27b0; /* Purple for unified login */
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .btn:hover {
            background-color: #7b1fa2; /* Darker purple on hover */
        }
        
        .error-message {
            color: #dc3545;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .login-footer {
            text-align: center;
            margin-top: 20px;
            color: #cccccc;
        }
        
        .login-footer a {
            color: #9c27b0; /* Purple accent for links */
            text-decoration: none;
        }
        
        .login-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<?php include '../include/head.php'; ?>
    <div class="main-content">
        <div class="login-container">
            <div class="login-header">
                <h1>Welcome Back</h1>
                <p>Login to access your account</p>
            </div>
            
            <?php if (!empty($error)): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
            <?php endif; ?>
            
            <form action="sallerlogin.php" method="POST">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                
                <!-- Password Field with Toggle Visibility -->
                <div class="form-group">
                    <label for="password">Password</label>
                    <div style="position: relative;">
                        <input type="password" id="password" name="password" class="form-control" required>
                        <button type="button" id="togglePassword" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: white; cursor: pointer;">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <button type="submit" class="btn">Login</button>
            </form>
            
            <div class="login-footer">
                New user? <a href="saller_regeatation.php">Create an account</a><br>
                <a href="forgot_password.php">Forgot password?</a>
            </div>
        </div>
    </div>
    <?php include '../include/footer.php'; ?>
    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordField = document.getElementById('password');

        togglePassword.addEventListener('click', () => {
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            togglePassword.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
        });
    </script>
</body>
</html>

