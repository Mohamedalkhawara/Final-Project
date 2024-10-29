<?php
include('db_connect.php');

$answers = $_POST['answers'] ?? [];
$score = 0;

foreach ($answers as $question_id => $selected_answer) {
    // Fetch the correct answer for the question
    $query = "SELECT correct_answer FROM questions WHERE id = '$question_id'";
    $result = mysqli_query($conn, $query);
    $question = mysqli_fetch_assoc($result);

    // Check if the selected answer matches the correct answer
    if ($selected_answer == $question['correct_answer']) {
        $score++;
    }
}

// Redirect or display the score
echo "<h1>Your score is: $score</h1>";
?>
