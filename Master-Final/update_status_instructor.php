<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['instructor_id'])) {
    header("Location: instructor_login.php");
    exit();
}

if (isset($_POST['appointment_id']) && isset($_POST['status'])) {
    $appointment_id = $_POST['appointment_id'];
    $status = $_POST['status'];

    // Update the status of the appointment
    $sql = "UPDATE appointments SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $appointment_id);

    if ($stmt->execute()) {
        header("Location: instructor_dashboard.php"); // Redirect back to the dashboard
        exit();
    } else {
        echo "Error updating status.";
    }
}
?>
