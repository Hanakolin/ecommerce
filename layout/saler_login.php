<?php
session_start();
include '../include/config.php';

if (isset($_POST['login'])) {
    $contact = $_POST['contact'];
    $password = $_POST['password'];
    $sql = "SELECT * FROM saller WHERE contact = '$contact'";
    $res = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($res);
    if ($num > 0) {
        $row = mysqli_fetch_assoc($res);
        if (password_verify($password, $row['password'])) {
            $_SESSION['id'] = $row['id'];
            
        } else {
            echo "Password does not match";
        }
    } else {
        echo "No user found";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
</head>

<body>
    <div class="login-container">
        <form method="post" action="" class="login-form">
            <h2>Login</h2>
            <label for="contact">contact</label>
            <input type="text" id="contact" name="contact" placeholder="contact" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            <input type="checkbox" id="togglePassword"> Show Password

            <script>
                const togglePassword = document.querySelector('#togglePassword');
                const password = document.querySelector('#password');

                togglePassword.addEventListener('change', function (e) {
                    // toggle the type attribute
                    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                    password.setAttribute('type', type);
                });
            </script>

            <button type="submit" name="login">Login</button>
        </form>
    </div>
</body>

</html>