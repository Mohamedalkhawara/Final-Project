<?php
include 'db_connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $appointment_id = $_POST['appointment_id'];

    // Fetch appointment data before deleting
    $appointment_query = "SELECT * FROM appointments WHERE id = ?";
    $stmt = $conn->prepare($appointment_query);
    $stmt->bind_param('i', $appointment_id);
    $stmt->execute();
    $appointment = $stmt->get_result()->fetch_assoc();

    // Insert appointment details into deleted_appointments table (same as earlier)
$sql_insert = "INSERT INTO deleted_appointments (appointment_id, user_id, instructor_id, training_package, start_date, preferred_time, status) 
VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt_insert = $conn->prepare($sql_insert);
$stmt_insert->bind_param('iiissss', $appointment['id'], $appointment['user_id'], $appointment['instructor_id'], 
          $appointment['training_package'], $appointment['start_date'], $appointment['preferred_time'], 
          $appointment['status']);
$stmt_insert->execute();

// Send notification for deleting the appointment
$user_id = $_SESSION['user_id']; // Assuming the user's ID is stored in the session
$action = "Deleted the appointment #$appointment_id";
$sql_notification = "INSERT INTO notifications (user_id, action, appointment_id, notification_type) VALUES (?, ?, ?, 'appointment')";
$stmt_notification = $conn->prepare($sql_notification);
$stmt_notification->bind_param('isi', $user_id, $action, $appointment_id);
$stmt_notification->execute();

// Continue with deleting the appointment
$sql = "DELETE FROM appointments WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $appointment_id);

if ($stmt->execute()) {
header("Location: my_appointments.php?message=deleted");
} else {
echo "Error deleting appointment: " . $conn->error;
}

}
?>
