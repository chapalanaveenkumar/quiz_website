<?php
$servername = "localhost"; // Usually localhost
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "quiz_website"; // Your database name

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
