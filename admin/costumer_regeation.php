<?php
include '../include/config.php'; // Include the database configuration file
// Create a database connection
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $contact = trim($_POST['contact']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Validations
    if (empty($name)) {
        $errors[] = "Full name is required";
    }
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    if (empty($contact)) {
        $errors[] = "Contact number is required";
    } elseif (!is_numeric($contact)) {
        $errors[] = "Contact number must be numeric";
    }
    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters";
    }
    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match";
    }

    // Check if email already exists
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT email FROM costumer WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errors[] = "Email already registered";
        }
        $stmt->close();
    }

    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert into costumer table
        $stmt = $conn->prepare("INSERT INTO costumer (costumer_name, email, contact, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $contact, $hashedPassword);

        if ($stmt->execute()) {
            $success = "Registration successful! You can now <a href='login.php'>login</a>.";
        } else {
            $errors[] = "Registration failed. Please try again. Error: " . $conn->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Registration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
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
        
        header {
            width: 100%;
            background-color: #1a1a1a;
            box-shadow: 0 2px 10px rgba(255, 255, 255, 0.1);
        }
        
        .main-content {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            width: 100%;
        }
        
        .register-container {
            background-color: #2c2c2c;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 500px;
            padding: 40px;
            margin: 20px 0;
        }
        
        .register-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .register-header h1 {
            color: white;
            margin-bottom: 10px;
        }
        
        .register-header p {
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
            border-color: #9c27b0;
            outline: none;
        }
        
        .btn {
            width: 100%;
            padding: 12px;
            background-color: #9c27b0;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .btn:hover {
            background-color: #7b1fa2;
        }
        
        .error-message {
            color: #dc3545;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .success-message {
            color: #28a745;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .register-footer {
            text-align: center;
            margin-top: 20px;
            color: #cccccc;
        }
        
        .register-footer a {
            color: #9c27b0;
            text-decoration: none;
        }
        
        .register-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header>
        <?php include '../include/head.php'; ?>
    </header>
    
    <div class="main-content">
        <div class="register-container">
            <div class="register-header">
                <h1>Customer Registration</h1>
                <p>Create your account to start shopping</p>
            </div>
            
            <?php if (!empty($errors)): ?>
                <div class="error-message">
                    <?php foreach ($errors as $error): ?>
                        <p><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="success-message">
                    <p><i class="fas fa-check-circle"></i> <?php echo $success; ?></p>
                </div>
            <?php else: ?>
                <form action="costumer_registration.php" method="POST">
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" class="form-control" required 
                               value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control" required
                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="contact">Phone Number</label>
                        <input type="text" id="contact" name="contact" class="form-control" required
                               value="<?php echo isset($_POST['contact']) ? htmlspecialchars($_POST['contact']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password (min 8 characters)</label>
                        <input type="password" id="password" name="password" class="form-control" required minlength="8">
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" required minlength="8">
                    </div>
                    
                    <button type="submit" class="btn">Register</button>
                </form>
            <?php endif; ?>
            
            <div class="register-footer">
                Already have an account? <a href="login.php">Login here</a>
            </div>
        </div>
    </div>
</body>
</html>