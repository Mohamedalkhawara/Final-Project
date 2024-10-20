<?php
include 'db_connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $training_package = $_POST['training_package'];
    $start_date = $_POST['start_date'];
    $preferred_time = $_POST['preferred_time'];
    $user_id = $_SESSION['user_id']; // Ensure the user ID is captured from the session

    // Check for existing appointment at the same time
    $check_sql = "SELECT * FROM appointments WHERE user_id = ? AND start_date = ? AND preferred_time = ?";
    $stmt_check = $conn->prepare($check_sql);
    $stmt_check->bind_param('iss', $user_id, $start_date, $preferred_time);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if ($result->num_rows > 0) {
        echo json_encode([
            'success' => false,
            'error' => "You already have an appointment at this time."
        ]);
        exit();
    }

    // Insert new appointment
    $sql = "INSERT INTO appointments (user_id, training_package, start_date, preferred_time, status) 
            VALUES ('$user_id', '$training_package', '$start_date', '$preferred_time', 'Pending')";
    
    if (mysqli_query($conn, $sql)) {
        $appointment_id = mysqli_insert_id($conn);

        // Insert notification for admin
        $action = "Added a new appointment #$appointment_id";
        $sql_notification = "INSERT INTO notifications (user_id, action, appointment_id) VALUES ('$user_id', '$action', '$appointment_id')";
        mysqli_query($conn, $sql_notification);

        // Return success response in JSON
        echo json_encode([
            'success' => true,
            'appointment_id' => $appointment_id
        ]);
    } else {
        // Return error response in JSON
        echo json_encode([
            'success' => false,
            'error' => mysqli_error($conn)
        ]);
    }
}
?>
