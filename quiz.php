<?php
session_start();
require 'db.php';

// Check if the user is logged in and has the correct role
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

// Fetch questions from the database
$query = "SELECT * FROM questions";
$result = mysqli_query($conn, $query);

// Check for query errors
if (!$result) {
    die("Error fetching questions: " . mysqli_error($conn));
}

// Check if there are any questions
if (mysqli_num_rows($result) > 0) {
    echo "<h2>Welcome to the Quiz, " . htmlspecialchars($_SESSION['username']) . "!</h2>";
    echo "<p><a href='logout.php'>Logout</a></p>";
    echo "<form method='POST' action='submit_quiz.php'>";

    while ($question = mysqli_fetch_assoc($result)) {
        // Ensure 'question_text' key exists in the array
        if (isset($question['question_text'])) {
            echo "<div>";
            echo "<h3>" . htmlspecialchars($question['question_text']) . "</h3>";

            $question_id = $question['id'];

            // Fetch options for the current question
            $options_query = "SELECT * FROM options WHERE question_id='$question_id'";
            $options_result = mysqli_query($conn, $options_query);

            // Check for query errors
            if (!$options_result) {
                die("Error fetching options: " . mysqli_error($conn));
            }

            if (mysqli_num_rows($options_result) > 0) {
                while ($option = mysqli_fetch_assoc($options_result)) {
                    echo "<label>";
                    echo "<input type='radio' name='question_" . $question_id . "' value='" . $option['id'] . "'> ";
                    echo htmlspecialchars($option['option_text']);
                    echo "</label><br>";
                }
            } else {
                echo "No options available for this question.";
            }

            echo "</div><br>";
        } else {
            echo "<p>Question text not found.</p>";
        }
    }

    echo "<button type='submit'>Submit Quiz</button>";
    echo "</form>";
} else {
    echo "<p>No questions available at the moment.</p>";
}

mysqli_close($conn);
?>
