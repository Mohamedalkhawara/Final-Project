<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['instructor_id'])) {
    header("Location: instructor_login.php");
    exit();
}

$instructor_id = $_SESSION['instructor_id'];

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

<!-- Logout Button -->
<div class="text-center mt-4">
    <a href="instructor_logout.php" class="btn btn-danger">Logout</a>
</div>

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
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['user_name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['phone']; ?></td>
                    <td><?php echo $row['training_package']; ?></td>
                    <td><?php echo $row['start_date']; ?></td>
                    <td><?php echo $row['preferred_time']; ?></td>
                    <td><?php echo $row['status']; ?></td>
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
