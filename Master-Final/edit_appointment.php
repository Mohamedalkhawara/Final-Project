<?php
session_start();
include 'db_connect.php';

// Check if the request method is GET and an ID is provided
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $appointment_id = $_GET['id'];

    // Fetch the appointment details
    $sql = "SELECT * FROM appointments WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $appointment_id);
    $stmt->execute();
    $appointment = $stmt->get_result()->fetch_assoc();

    if (!$appointment) {
        echo "<script>alert('Appointment not found.'); window.location.href = 'my_appointments.php';</script>";
        exit();
    }
}

// Handle the form submission for updating
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_appointment'])) {
    $appointment_id = $_POST['appointment_id'];
    $preferred_time = $_POST['preferred_time'];
    $start_date = $_POST['start_date'];
    $new_status = 'Pending'; // Update as per the new status being set
    
    // Fetch the current status before updating
    $sql_fetch_status = "SELECT status FROM appointments WHERE id = ?";
    $stmt_status = $conn->prepare($sql_fetch_status);
    $stmt_status->bind_param('i', $appointment_id);
    $stmt_status->execute();
    $result_status = $stmt_status->get_result();
    $current_status = $result_status->fetch_assoc()['status'];

    // Update the appointment status
    $sql_update = "UPDATE appointments SET status = ?, preferred_time = ?, start_date = ? WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param('sssi', $new_status, $preferred_time, $start_date, $appointment_id);

    if ($stmt_update->execute()) {
        // Insert into status_history
        $user_id = $_SESSION['user_id']; // Assuming user_id is stored in the session
        $sql_insert_history = "INSERT INTO status_history (appointment_id, previous_status, new_status, changed_by) VALUES (?, ?, ?, ?)";
        $stmt_history = $conn->prepare($sql_insert_history);
        $stmt_history->bind_param('issi', $appointment_id, $current_status, $new_status, $user_id);
        $stmt_history->execute();
        // Insert a notification for the admin or user
$notification_message = "The status of appointment #$appointment_id has been changed from $current_status to $new_status.";
$sql_notification = "INSERT INTO notifications (user_id, action) VALUES (?, ?)";
$stmt_notification = $conn->prepare($sql_notification);
$stmt_notification->bind_param('is', $user_id, $notification_message);
$stmt_notification->execute();


        // Redirect back to the appointments page
        header("Location: my_appointments.php?message=updated");
        exit();
    } else {
        echo "Error updating appointment: " . $conn->error;
    }
}


include 'header.php';
include 'navbar.php';
?>

<div class="container">
    <h2>Edit Appointment</h2>
    <form action="edit_appointment.php" method="POST">
        <input type="hidden" name="appointment_id" value="<?php echo $appointment['id']; ?>">
        <div class="mb-3">
            <label for="start_date" class="form-label">Start Date</label>
            <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo htmlspecialchars($appointment['start_date']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="preferred_time" class="form-label">Preferred Time</label>
            <select class="form-control" id="preferred_time" name="preferred_time" required>
                <option value="08:00 AM - 09:00 AM" <?php echo ($appointment['preferred_time'] == "08:00 AM - 09:00 AM") ? 'selected' : ''; ?>>08:00 AM - 09:00 AM</option>
                <option value="09:00 AM - 10:00 AM" <?php echo ($appointment['preferred_time'] == "09:00 AM - 10:00 AM") ? 'selected' : ''; ?>>09:00 AM - 10:00 AM</option>
                <option value="10:00 AM - 11:00 AM" <?php echo ($appointment['preferred_time'] == "10:00 AM - 11:00 AM") ? 'selected' : ''; ?>>10:00 AM - 11:00 AM</option>
                <option value="11:00 AM - 12:00 PM" <?php echo ($appointment['preferred_time'] == "11:00 AM - 12:00 PM") ? 'selected' : ''; ?>>11:00 AM - 12:00 PM</option>
                <option value="01:00 PM - 02:00 PM" <?php echo ($appointment['preferred_time'] == "01:00 PM - 02:00 PM") ? 'selected' : ''; ?>>01:00 PM - 02:00 PM</option>
                <option value="02:00 PM - 03:00 PM" <?php echo ($appointment['preferred_time'] == "02:00 PM - 03:00 PM") ? 'selected' : ''; ?>>02:00 PM - 03:00 PM</option>
                <option value="03:00 PM - 04:00 PM" <?php echo ($appointment['preferred_time'] == "03:00 PM - 04:00 PM") ? 'selected' : ''; ?>>03:00 PM - 04:00 PM</option>
                <option value="04:00 PM - 05:00 PM" <?php echo ($appointment['preferred_time'] == "04:00 PM - 05:00 PM") ? 'selected' : ''; ?>>04:00 PM - 05:00 PM</option>
                <option value="05:00 PM - 06:00 PM" <?php echo ($appointment['preferred_time'] == "05:00 PM - 06:00 PM") ? 'selected' : ''; ?>>05:00 PM - 06:00 PM</option>
                <option value="06:00 PM - 07:00 PM" <?php echo ($appointment['preferred_time'] == "06:00 PM - 07:00 PM") ? 'selected' : ''; ?>>06:00 PM - 07:00 PM</option>
                <option value="07:00 PM - 08:00 PM" <?php echo ($appointment['preferred_time'] == "07:00 PM - 08:00 PM") ? 'selected' : ''; ?>>07:00 PM - 08:00 PM</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary" name="update_appointment">Update Appointment</button>
    </form>
</div>

<?php include 'footer.php'; ?>
