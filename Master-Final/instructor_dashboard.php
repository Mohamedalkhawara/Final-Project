<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['instructor_id'])) {
    header("Location: instructor_login.php");
    exit();
}

$instructor_id = $_SESSION['instructor_id'];

// Fetch instructor information
$instructor_query = "SELECT name, image FROM instructors WHERE id = ?";
$instructor_stmt = $conn->prepare($instructor_query);
$instructor_stmt->bind_param("i", $instructor_id);
$instructor_stmt->execute();
$instructor_result = $instructor_stmt->get_result();
$instructor = $instructor_result->fetch_assoc();

// Fetch appointments assigned to the logged-in instructor
$sql = "SELECT a.*, u.name as user_name, u.phone, u.email 
        FROM appointments a 
        LEFT JOIN users u ON a.user_id = u.id 
        WHERE a.instructor_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $instructor_id);
$stmt->execute();
$appointments = $stmt->get_result();

include 'header.php';
?>

<!-- Logout Button and Instructor Profile -->
<div class="text-center mt-4">
    <a href="instructor_logout.php" class="btn btn-danger">Logout</a>
</div>

<!-- Instructor Profile Section -->
<div class="container my-5 text-center">
    <h2>Welcome, <?php echo $instructor['name']; ?></h2>
    <!-- Display Instructor's Profile Image -->
    <img src="uploads/<?php echo $instructor['image']; ?>" alt="Profile Image" class="rounded-circle" style="width: 150px; height: 150px;">
    <br><br>
    <!-- Profile Button -->
    <a href="instructor_profile.php" class="btn btn-info">Edit Profile</a>
</div>

<!-- Appointments Table -->
<div class="container my-5">
    <h1>My Appointments</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Appointment ID</th>
                <th>User Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Training Package</th>
                <th>Start Date</th>
                <th>Preferred Time</th>
                <th>Status</th>
                <th>Feedback</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $appointments->fetch_assoc()) { ?>
                <!-- Apply row class based on status -->
                <tr class="<?php 
                    if ($row['status'] == 'Deleted') echo 'table-danger'; 
                    elseif ($row['status'] == 'Completed') echo 'table-success'; 
                ?>">
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['user_name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['phone']; ?></td>
                    <td><?php echo $row['training_package']; ?></td>
                    <td><?php echo $row['start_date']; ?></td>
                    <td><?php echo $row['preferred_time']; ?></td>
                    <td>
                        <form action="update_status_instructor.php" method="POST">
                            <input type="hidden" name="appointment_id" value="<?php echo $row['id']; ?>">
                            <select name="status" class="form-control">
                                <option value="Completed" <?php echo $row['status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                            </select>
                            <input type="submit" class="btn btn-primary mt-2" value="Update Status">
                        </form>
                    </td>
                    <td>
                        <form action="submit_feedback.php" method="POST">
                            <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                            <textarea name="feedback" required></textarea>
                            <input type="submit" class="btn btn-primary mt-2" value="Submit Feedback">
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>
