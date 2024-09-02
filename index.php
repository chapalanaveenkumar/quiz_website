<?php
session_start();
if (isset($_SESSION['username'])) {
    header("Location: quiz.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quiz Website</title>
</head>
<body>
    <h1>Welcome to the Quiz Website</h1>
    <a href="login.php">Login</a> | 
    <a href="signup.php">Sign Up</a> | 
    <a href="admin_login.php">Admin Login</a>
</body>
</html>
