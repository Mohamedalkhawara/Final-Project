<?php
session_start();
include 'db_connect.php';

$id = $_GET['id'];

// Fetch the appointment details
// Updated SQL query to join with users table and take the gender, birthday, and contact number from the appointments table
$sql = "SELECT appointments.*, 
               instructors.name as instructor_name, 
               users.name as full_name 
        FROM appointments 
        LEFT JOIN instructors ON appointments.instructor_id = instructors.id 
        LEFT JOIN users ON appointments.user_id = users.id 
        WHERE appointments.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$appointment = $result->fetch_assoc();

// Handle the case where no appointment is found
if (!$appointment) {
    echo "<script>
        alert('Appointment not found!');
        window.location.href = 'admin_dashboard.php';
    </script>";
    exit();
}

include 'header.php';
include 'navbar.php';
?>

<div class="container-xxl py-6">
    <div class="container">
        <h1 class="mb-4">Appointment Details</h1>
        <table class="table table-bordered">
            <tr>
                <th>Fullname</th>
                <td><?php echo !empty($appointment['full_name']) ? $appointment['full_name'] : 'N/A'; ?></td>
            </tr>
            <tr>
                <th>Gender</th>
                <td><?php echo !empty($appointment['gender']) ? $appointment['gender'] : 'N/A'; ?></td> <!-- Assuming gender is in appointments table -->
            </tr>
            <tr>
                <th>Birthday</th>
                <td><?php echo !empty($appointment['birthday']) ? $appointment['birthday'] : 'N/A'; ?></td> <!-- Assuming birthday is in appointments table -->
            </tr>
            <tr>
                <th>Contact Number</th>
                <td><?php echo !empty($appointment['contact_number']) ? $appointment['contact_number'] : 'N/A'; ?></td> <!-- Assuming contact number is in appointments table -->
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo !empty($appointment['email']) ? $appointment['email'] : 'N/A'; ?></td> <!-- Assuming email is in appointments table -->
            </tr>
            <tr>
                <th>Address</th>
                <td><?php echo !empty($appointment['address']) ? $appointment['address'] : 'N/A'; ?></td> <!-- Assuming address is in appointments table -->
            </tr>
            <tr>
                <th>Category</th>
                <td><?php echo !empty($appointment['category']) ? $appointment['category'] : 'N/A'; ?></td>
            </tr>
            <tr>
                <th>Training Package</th>
                <td><?php echo $appointment['training_package']; ?></td>
            </tr>
            <tr>
                <th>Start Date</th>
                <td><?php echo $appointment['start_date']; ?></td>
            </tr>
            <tr>
                <th>Preferred Time</th>
                <td><?php echo $appointment['preferred_time']; ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td><?php echo $appointment['status']; ?></td>
            </tr>
            <tr>
                <th>Instructor</th>
                <td><?php echo !empty($appointment['instructor_name']) ? $appointment['instructor_name'] : 'Not Assigned'; ?></td>
            </tr>
        </table>
        <button class="btn btn-primary" onclick="window.print()">Print</button>
    </div>
</div>

<?php include 'footer.php'; ?>

<script>
function generatePDF() {
    window.print();
}
</script>
