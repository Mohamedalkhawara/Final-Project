<?php
session_start();
include 'db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If the user is not logged in, return an error
    http_response_code(403);
    echo 'User not logged in';
    exit();
}

$user_id = $_SESSION['user_id'];
$training_package = $_POST['training_package'];
$start_date = $_POST['start_date'];
$preferred_time = $_POST['preferred_time'];

// Insert the new appointment into the database
$sql = "INSERT INTO appointments (user_id, training_package, start_date, preferred_time, status) 
        VALUES ('$user_id', '$training_package', '$start_date', '$preferred_time', 'Pending')";

if (mysqli_query($conn, $sql)) {
    // Success
    http_response_code(200);
    echo 'Appointment saved';
} else {
    // Error
    http_response_code(500);
    echo 'Error: ' . mysqli_error($conn);
}

mysqli_close($conn);
?>
