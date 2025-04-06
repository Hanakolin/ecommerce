<?php
include '../include/config.php';

if (isset($_POST['submit'])) {
    $user_name = $_POST['user_name'];
    $contact = $_POST['contact'];
    $password = $_POST['password'];
   
    $hp= password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO saller (user_name, contact, password) 
            VALUES ('$user_name', '$contact', '$hp')";

    if (mysqli_query($conn, $sql)) {
        echo "Your data is submitted successfully";
    } else {
       echo"unable to submit your data";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>plant</title>
    <link rel="icon" type="image/png" href="../assets/backgroung_pic/de.png">

    <img src="../assets/backgroung_pic/de.png" alt="logo" width="100px" height="100px">

</head>
<body>
    <form action="" method="post">
        <input type="text" name="user_name" placeholder="User Name" required><br>
        <input type="text" name="contact" placeholder="Contact" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit" name="submit">Submit</button>
</body>
</html>