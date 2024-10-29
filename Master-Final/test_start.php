<?php
include('db_connect.php');

$part_id = $_GET['part'] ?? 1; // Default to part 1 if not provided

// Fetch the part details
$part_query = "SELECT * FROM test_parts WHERE id = '$part_id'";
$part_result = mysqli_query($conn, $part_query);
$part = mysqli_fetch_assoc($part_result);

// Fetch questions for the selected part
$questions_query = "SELECT * FROM questions WHERE part = '$part_id'";
$questions_result = mysqli_query($conn, $questions_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $part['part_name']; ?> Test</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1><?php echo $part['part_name']; ?></h1>
    <p><?php echo $part['description']; ?></p>

    <form action="submit_test.php" method="POST">
        <?php while ($question = mysqli_fetch_assoc($questions_result)) { ?>
            <div class="question">
                <p><?php echo $question['question_text']; ?></p>
                <?php if (!empty($question['image_path'])): ?>
                    <img src="uploads/<?php echo $question['image_path']; ?>" alt="Question Image" style="width:100px;">
                <?php endif; ?>
                <div class="options">
                    <label>
                        <input type="radio" name="answers[<?php echo $question['id']; ?>]" value="<?php echo $question['option1']; ?>">
                        <?php echo $question['option1']; ?>
                    </label><br>
                    <label>
                        <input type="radio" name="answers[<?php echo $question['id']; ?>]" value="<?php echo $question['option2']; ?>">
                        <?php echo $question['option2']; ?>
                    </label><br>
                    <label>
                        <input type="radio" name="answers[<?php echo $question['id']; ?>]" value="<?php echo $question['option3']; ?>">
                        <?php echo $question['option3']; ?>
                    </label>
                </div>
            </div>
        <?php } ?>
        <button type="submit">Submit Test</button>
    </form>
</body>
</html>
