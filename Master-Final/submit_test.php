<?php
$score = $_GET['score'] ?? 0;
$total = $_GET['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quiz Result</title>
    <link rel="stylesheet" href="SubmitStyle.css?v=1.0">
    </head>
<body>
    <div class="quiz-container">
        <h1>Quiz Result</h1>
        <div class="result-card">
            <p>Your score:</p>
            <p class="score"><?php echo htmlspecialchars($score); ?> / <?php echo htmlspecialchars($total); ?></p>
            <div class="score-indicator">
                <?php if ($score == $total): ?>
                    <p class="perfect-score">Perfect Score! 🎉</p>
                <?php elseif ($score >= $total / 2): ?>
                    <p class="good-score">Good Job! 👍</p>
                <?php else: ?>
                    <p class="try-again">Try Again! 💪</p>
                <?php endif; ?>
            </div>
            <div class="button-container">
                <a href="test_start.php" class="retry-button">Retry Quiz</a>
                <a href="index.php" class="home-button">Return to Home</a>
            </div>
        </div>
    </div>
</body>
</html>
