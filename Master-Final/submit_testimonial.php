<?php
// Include the database connection and start the session
include 'db_connect.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user details for the testimonial submission
$user_query = "SELECT name, image FROM users WHERE id = $user_id";
$user_result = mysqli_query($conn, $user_query);
$user = mysqli_fetch_assoc($user_result);

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $testimonial = mysqli_real_escape_string($conn, $_POST['message']);

    // Insert the testimonial into the testimonials table
    $insert_testimonial = "INSERT INTO testimonials (user_id, message, status) VALUES ('$user_id', '$testimonial', 'Pending')";
    if (mysqli_query($conn, $insert_testimonial)) {
        $testimonial_id = mysqli_insert_id($conn); // Get the ID of the inserted testimonial

        // Insert a notification into the notifications table
        $action = $user['name'] . " submitted a new testimonial.";
        $sql_notification = "INSERT INTO notifications (user_id, action, is_read, notification_type, testimonial_id, created_at) 
                             VALUES ('$user_id', '$action', 0, 'testimonial', '$testimonial_id', NOW())";

        if (mysqli_query($conn, $sql_notification)) {
            echo "<script>alert('Your testimonial has been submitted for approval!'); window.location.href='index.php';</script>";
        } else {
            echo "Error adding notification: " . mysqli_error($conn);
        }

    } else {
        echo "Error submitting testimonial: " . mysqli_error($conn);
    }
}
?>
