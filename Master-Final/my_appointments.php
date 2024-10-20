<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include the database connection file
include 'db_connect.php';

$user_id = $_SESSION['user_id'];

// Fetch the user's appointments from the database
function fetchAppointments($conn, $user_id) {
    $sql = "SELECT a.*, i.name as instructor_name 
            FROM appointments a 
            LEFT JOIN instructors i ON a.instructor_id = i.id 
            WHERE a.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    return $stmt->get_result();
}

$appointments = fetchAppointments($conn, $user_id);

include 'header.php';
include 'navbar.php';
?>

<div class="container-xxl py-6">
    <div class="container">
        <h1 class="mb-4">My Appointments</h1>

        <p class="mb-4">
            You can easily manage your appointments. Use the buttons below to either edit your appointment details (date and time) or delete them if you no longer need them. Appointments can be rescheduled or canceled at any time.
        </p>
        <div>
            <button class="btn btn-warning" onclick="window.location.href='edit_profile.php'">Edit Profile</button>
        </div>
        <br>

        <!-- Add Appointment Button -->
        <button class="btn btn-primary mb-4" id="addAppointmentBtn">Add New Appointment</button>

        <!-- Add Appointment Form (Initially Hidden) -->
        <div id="addAppointmentForm" style="display: none;">
            <form id="appointmentForm">
                <div class="mb-3">
                    <label for="training_package" class="form-label">Training Package</label>
                    <select class="form-control" id="training_package" name="training_package" required>
                        <option value="Manual Car Training">Manual Car Training</option>
                        <option value="Automatic Car Training">Automatic Car Training</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" required>
                </div>
                <div class="mb-3">
                    <label for="preferred_time" class="form-label">Preferred Time</label>
                    <select class="form-control" id="preferred_time" name="preferred_time" required>
                        <option value="08:00 AM - 09:00 AM">08:00 AM - 09:00 AM</option>
                        <option value="09:00 AM - 10:00 AM">09:00 AM - 10:00 AM</option>
                        <option value="10:00 AM - 11:00 AM">10:00 AM - 11:00 AM</option>
                        <option value="11:00 AM - 12:00 PM">11:00 AM - 12:00 PM</option>
                        <option value="01:00 PM - 02:00 PM">01:00 PM - 02:00 PM</option>
                        <option value="02:00 PM - 03:00 PM">02:00 PM - 03:00 PM</option>
                        <option value="03:00 PM - 04:00 PM">03:00 PM - 04:00 PM</option>
                        <option value="04:00 PM - 05:00 PM">04:00 PM - 05:00 PM</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Save Appointment</button>
            </form>
        </div>

        <!-- Table of Appointments -->
        <table class="table table-bordered" id="appointmentsTable">
            <thead>
                <tr>
                    <th>Appointment ID</th>
                    <th>Training Package</th>
                    <th>Start Date</th>
                    <th>Preferred Time</th>
                    <th>Driving Instructor</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $appointments->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['training_package']; ?></td>
                        <td><?php echo $row['start_date']; ?></td>
                        <td><?php echo $row['preferred_time']; ?></td>
                        <td><?php echo $row['instructor_name'] ?: 'Not Assigned'; ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td>
                            <a href="edit_appointment.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <td>
    <form action="delete_appointment.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this appointment?');">
        <input type="hidden" name="appointment_id" value="<?php echo $row['id']; ?>">
        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
    </form>
</td>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script>
// Toggle Appointment Form
document.getElementById('addAppointmentBtn').addEventListener('click', function () {
    var form = document.getElementById('addAppointmentForm');
    if (form.style.display === 'none') {
        form.style.display = 'block';
    } else {
        form.style.display = 'none';
    }
});

// Submit the Appointment Form using AJAX
document.getElementById('appointmentForm').addEventListener('submit', function (event) {
    event.preventDefault(); // Prevent form submission

    var formData = new FormData(this);

    // Use Fetch API to submit the form via AJAX
    fetch('add_appointment.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Appointment added successfully!');
            window.location.reload(); // Reload the page to show the new appointment
        } else {
            alert(data.error); // Show error message
        }
    })
    .catch(error => console.error('Error:', error));
});
</script>

<?php include 'footer.php'; ?>
