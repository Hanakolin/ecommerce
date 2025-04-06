<?php
// filepath: d:\xampp\htdocs\PHP\e-commerce\include\costumer_home.php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plants</title>
    <style>
        .message {
            background-color: #16a34a;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            margin: 20px auto;
            text-align: center;
            width: 50%;
            font-size: 1rem;
            font-weight: bold;
        }
        .message.hidden {
            display: none;
        }
    </style>
</head>

<body>
    <?php include './costumer_head.php'; ?>

    <!-- Display the success message -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="message" id="successMessage">
            <?php 
                echo $_SESSION['message']; 
                unset($_SESSION['message']); // Remove the message after displaying it
            ?>
        </div>
    <?php endif; ?>

    <div style="display: flex; flex-direction: column;">
        <?php include '../include/card.php'; ?>
    </div>

    <script>
        // Automatically hide the success message after 1 minute (60000 ms)
        setTimeout(() => {
            const message = document.getElementById('successMessage');
            if (message) {
                message.classList.add('hidden');
            }
        }, 60000);
    </script>
</body>

</html>
