<?php
session_start(); // Start the session

include('db_connect.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Set a session message
    $_SESSION['message'] = "You must log in before accessing the test";
    // Redirect to the login page
    header("Location: login.php");
    exit();
}

$part_id = $_GET['part'] ?? 1;

// Fetch part details
$part_query = "SELECT * FROM test_parts WHERE id = '$part_id'";
$part_result = mysqli_query($conn, $part_query);
$part = mysqli_fetch_assoc($part_result);

// Fetch questions
$questions_query = "SELECT * FROM questions WHERE part = '$part_id'";
$questions_result = mysqli_query($conn, $questions_query);
$questions = [];
while ($row = mysqli_fetch_assoc($questions_result)) {
    $questions[] = $row;
}
$total_questions = count($questions);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($part['part_name']); ?> Test</title>
    <link rel="stylesheet" href="style.css?v=1">
</head>
<body>
    <div class="quiz-container">
        <div class="timer-container">
            <canvas id="timerCanvas" width="100" height="100"></canvas>
            <div id="timeLeft" class="time-left">60</div>
        </div>
        
        <h1><?php echo htmlspecialchars($part['part_name']); ?></h1>
        <p><?php echo htmlspecialchars($part['description']); ?></p>

        <div id="questionContainer"></div>

        <div class="button-container">
            <button id="prevButton" class="navigation-button">Previous</button>
            <button id="nextButton" class="navigation-button">Next</button>
            <button id="submitButton" class="navigation-button" style="display: none;">Submit</button>
        </div>
    </div>

    <script>
        const questions = <?php echo json_encode($questions); ?>;
        const totalQuestions = <?php echo $total_questions; ?>;
    </script>
    <script src="quiz.js?v=1"></script>
</body>
</html>
