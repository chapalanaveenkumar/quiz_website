<?php
session_start();
require 'db.php';

$user_id = $_SESSION['user_id'];
$result = mysqli_query($conn, "SELECT * FROM user_answers WHERE user_id='$user_id'");
$total_questions = 0;
$correct_answers = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $question_id = $row['question_id'];
    $user_answers = explode(',', $row['answers']);
    
    // Fetch correct answers from the database
    $correct_result = mysqli_query($conn, "SELECT correct_answers FROM questions WHERE id='$question_id'");
    $correct_row = mysqli_fetch_assoc($correct_result);
    $correct_answers_array = explode(',', $correct_row['correct_answers']);
    
    // Check if user answers match correct answers
    if ($user_answers == $correct_answers_array) {
        $correct_answers++;
    }
    $total_questions++;
}

$score = ($correct_answers / $total_questions) * 100;
echo "Your score is: $score%";
?>
