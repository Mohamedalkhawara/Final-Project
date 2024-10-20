<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['instructor_id'])) {
    header("Location: instructor_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $feedback = $_POST['feedback'];
    $instructor_id = $_SESSION['instructor_id'];

    // Insert feedback into the feedback table
    $sql = "INSERT INTO feedback (user_id, instructor_id, feedback) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $user_id, $instructor_id, $feedback);

    if ($stmt->execute()) {
        // Create a notification for the admin about new feedback
        $notification_action = "New feedback provided by Instructor for User ID: $user_id.";
        $notification_type = 'feedback';  // Set the notification type as feedback

        $notification_sql = "INSERT INTO notifications (user_id, action, notification_type, instructor_id) VALUES (?, ?, ?, ?)";
        $notification_stmt = $conn->prepare($notification_sql);
        $notification_stmt->bind_param("issi", $user_id, $notification_action, $notification_type, $instructor_id);
        $notification_stmt->execute();

        // JavaScript alert after successful submission
        echo "<script>
                alert('Feedback submitted successfully!');
                window.location.href = 'instructor_dashboard.php'; // Redirect after alert
              </script>";
    } else {
        echo "<script>
                alert('Error submitting feedback.');
                window.location.href = 'instructor_dashboard.php'; // Redirect after error alert
              </script>";
    }
}
?>
