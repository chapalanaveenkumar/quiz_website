<?php
session_start();
require 'db.php';

// Check if the admin is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: admin_login.php");
    exit();
}

// Handle form submissions to add new questions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_question'])) {
        $question_text = $_POST['question_text'];

        if (!empty($question_text)) {
            $query = "INSERT INTO questions (question_text) VALUES ('$question_text')";
            if (mysqli_query($conn, $query)) {
                echo "Question added successfully!";
            } else {
                echo "Error adding question: " . mysqli_error($conn);
            }
        } else {
            echo "Question text cannot be empty.";
        }
    } elseif (isset($_POST['add_option'])) {
        $question_id = $_POST['question_id'];
        $option_text = $_POST['option_text'];

        if (!empty($question_id) && !empty($option_text)) {
            $query = "INSERT INTO options (question_id, option_text) VALUES ('$question_id', '$option_text')";
            if (mysqli_query($conn, $query)) {
                echo "Option added successfully!";
            } else {
                echo "Error adding option: " . mysqli_error($conn);
            }
        } else {
            echo "Question ID and option text cannot be empty.";
        }
    }
}

// Fetch existing questions
$questions_query = "SELECT * FROM questions";
$questions_result = mysqli_query($conn, $questions_query);

if (!$questions_result) {
    die("Error fetching questions: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
</head>
<body>
    <h2>Welcome Admin, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
    <p><a href="logout.php">Logout</a></p>

    <!-- Form to add new questions -->
    <h3>Add New Question</h3>
    <form method="POST" action="">
        <label>Question Text:</label>
        <input type="text" name="question_text" required>
        <button type="submit" name="add_question">Add Question</button>
    </form>

    <!-- Form to add options to a question -->
    <h3>Add Option to a Question</h3>
    <form method="POST" action="">
        <label>Question ID:</label>
        <input type="number" name="question_id" required>
        <label>Option Text:</label>
        <input type="text" name="option_text" required>
        <button type="submit" name="add_option">Add Option</button>
    </form>

    <!-- Display existing questions -->
    <h3>Existing Questions</h3>
    <?php
    if (mysqli_num_rows($questions_result) > 0) {
        while ($question = mysqli_fetch_assoc($questions_result)) {
            echo "<div>";
            echo "<p>" . htmlspecialchars($question['question_text']) . "</p>";

            // Fetch and display options for the question
            $options_query = "SELECT * FROM options WHERE question_id=" . $question['id'];
            $options_result = mysqli_query($conn, $options_query);

            if (!$options_result) {
                die("Error fetching options: " . mysqli_error($conn));
            }

            if (mysqli_num_rows($options_result) > 0) {
                echo "<ul>";
                while ($option = mysqli_fetch_assoc($options_result)) {
                    echo "<li>" . htmlspecialchars($option['option_text']) . "</li>";
                }
                echo "</ul>";
            } else {
                echo "<p>No options available for this question.</p>";
            }

            echo "</div><br>";
        }
    } else {
        echo "<p>No questions available.</p>";
    }
    ?>

    <?php mysqli_close($conn); ?>
</body>
</html>
