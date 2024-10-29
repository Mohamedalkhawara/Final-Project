<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['instructor_id'])) {
    header("Location: instructor_login.php");
    exit();
}

if (isset($_GET['id'])) {
    $notification_id = $_GET['id'];
    
    // Update the notification to mark it as read
    $sql = "UPDATE notifications SET is_read = 1 WHERE id = ? AND instructor_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $notification_id, $_SESSION['instructor_id']);
    
    if ($stmt->execute()) {
        header("Location: instructor_notifications.php");
        exit();
    } else {
        echo "Error updating notification.";
    }
}
?>
