<?php
include 'db_connect.php';

$notification_id = $_GET['id'];

// Mark the notification as read
$sql = "UPDATE notifications SET is_read = 1 WHERE id = '$notification_id'";
if (mysqli_query($conn, $sql)) {
    header("Location: notifications.php");
    exit();
} else {
    echo "Error marking as read: " . mysqli_error($conn);
}
?>
