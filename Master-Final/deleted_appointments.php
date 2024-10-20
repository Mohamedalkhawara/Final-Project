<?php
session_start();
include 'db_connect.php';

// Fetch deleted appointments
$sql = "SELECT da.*, u.name as user_name, i.name as instructor_name 
        FROM deleted_appointments da 
        LEFT JOIN users u ON da.user_id = u.id 
        LEFT JOIN instructors i ON da.instructor_id = i.id";
$result = $conn->query($sql);

include 'header.php';
?>

<div class="container-xxl py-6">
    <div class="container">
        <h1 class="mb-4">Deleted Appointments</h1>
        <a href="admin_dashboard.php" class="btn btn-secondary mt-4">Back to Dashboard</a>
        <br>
        <br>
        <br>


        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Appointment ID</th>
                    <th>User Name</th>
                    <th>Instructor</th>
                    <th>Training Package</th>
                    <th>Start Date</th>
                    <th>Preferred Time</th>
                    <th>Status</th>
                    <th>Deleted At</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['appointment_id']; ?></td>
                        <td><?php echo $row['user_name']; ?></td>
                        <td><?php echo $row['instructor_name'] ?: 'Not Assigned'; ?></td>
                        <td><?php echo $row['training_package']; ?></td>
                        <td><?php echo $row['start_date']; ?></td>
                        <td><?php echo $row['preferred_time']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td><?php echo $row['deleted_at']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>
