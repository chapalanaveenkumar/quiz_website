<?php
session_start();
require 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['user_id'])) {
    die("User ID is not set in the session. Please log in again.");
}

$user_id = $_SESSION['user_id'];

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['answers'])) {
    $answers = $_POST['answers'];

    foreach ($answers as $question_id => $selected_options) {
        // Convert selected options to a comma-separated string
        $selected_answers_str = implode(',', $selected_options);
        
        // Insert user answers into the database
        $query = "INSERT INTO user_answers (user_id, question_id, answers) VALUES ('$user_id', '$question_id', '$selected_answers_str')";
        if (!mysqli_query($conn, $query)) {
            die("Error inserting answers: " . mysqli_error($conn));
        }
    }

    // Redirect to the results page or display a confirmation
    header("Location: user_results.php");
    exit();
} else {
    echo "No answers were submitted.";
}

mysqli_close($conn);
?>
