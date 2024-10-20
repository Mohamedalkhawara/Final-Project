<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ensure that the user is logged in and that the session has 'user_id'
    if (!isset($_SESSION['user_id'])) {
        echo "<script>alert('User is not logged in. Please login again.'); window.location.href='login.php';</script>";
        exit();
    }

    $appointment_id = $_POST['appointment_id'];
    $instructor_id = $_POST['instructor_id'];

    // Get the appointment time to check for conflicts
    $appointment_query = "SELECT start_date, preferred_time FROM appointments WHERE id = '$appointment_id'";
    $appointment_result = mysqli_query($conn, $appointment_query);
    $appointment = mysqli_fetch_assoc($appointment_result);

    $start_date = $appointment['start_date'];
    $preferred_time = $appointment['preferred_time'];

    // Check if the instructor is already assigned to another appointment at the same time
    $sql_check = "SELECT * FROM appointments 
                  WHERE instructor_id = '$instructor_id' 
                  AND start_date = '$start_date' 
                  AND preferred_time = '$preferred_time' 
                  AND status != 'Completed'";
    $result_check = mysqli_query($conn, $sql_check);

    if (mysqli_num_rows($result_check) > 0) {
        // Instructor is already assigned to an appointment at the same time
        echo "<script>
            alert('The selected instructor is already assigned to another appointment at the same time. Please choose a different time or instructor.');
            window.location.href='assign_instructor.php?id=$appointment_id';
        </script>";
    } else {
        // Instructor is available, assign to the appointment
        $sql = "UPDATE appointments SET instructor_id = '$instructor_id' WHERE id = '$appointment_id'";
        if (mysqli_query($conn, $sql)) {

            // Insert notification for the instructor
            $user_id = $_SESSION['user_id']; // The admin who is assigning
            $action = "You have been assigned to appointment #$appointment_id";
            $sql_notification = "INSERT INTO notifications (user_id, instructor_id, action, appointment_id) 
                                 VALUES ('$user_id', '$instructor_id', '$action', '$appointment_id')";
            mysqli_query($conn, $sql_notification);

            echo "<script>
                alert('Driving instructor has been successfully assigned.');
                window.location.href='admin_dashboard.php';
            </script>";
        } else {
            echo "<script>
                alert('Error assigning instructor. Please try again later.');
                window.location.href='assign_instructor.php?id=$appointment_id';
            </script>";
        }
    }
} else {
    $appointment_id = $_GET['id'];
    $instructors = mysqli_query($conn, "SELECT * FROM instructors");
}

include 'header.php';  
?>

<div class="container-xxl py-6">
    <div class="container">
        <h1 class="mb-4">Assign Driving Instructor</h1>
        <form action="assign_instructor.php" method="POST">
            <input type="hidden" name="appointment_id" value="<?php echo $appointment_id; ?>">
            <div class="form-group mb-4">
                <label for="instructor_id" class="form-label">Select Instructor:</label>
                <select class="form-control" id="instructor_id" name="instructor_id">
                    <?php while ($row = mysqli_fetch_assoc($instructors)) { ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Assign Instructor</button>
        </form>
    </div>
</div>

