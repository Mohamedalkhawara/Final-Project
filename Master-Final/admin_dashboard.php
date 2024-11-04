<?php 
session_start();

// Include the database connection
include 'db_connect.php';

// Fetch unread notifications for admin
$notification_query = "SELECT COUNT(*) AS unread_count FROM notifications WHERE is_read = 0";
$notification_result = mysqli_query($conn, $notification_query);
$notification_count = mysqli_fetch_assoc($notification_result)['unread_count'];

// Fetch total number of enrollees
$total_enrollees_query = "SELECT COUNT(*) AS total FROM appointments";
$total_enrollees_result = mysqli_query($conn, $total_enrollees_query);
$total_enrollees = mysqli_fetch_assoc($total_enrollees_result)['total'];

// Fetch upcoming sessions
$upcoming_sessions_query = "SELECT COUNT(*) AS upcoming FROM appointments WHERE status = 'Pending'";
$upcoming_sessions_result = mysqli_query($conn, $upcoming_sessions_query);
$upcoming_sessions = mysqli_fetch_assoc($upcoming_sessions_result)['upcoming'];

// Fetch students by status
$pending_query = "SELECT COUNT(*) AS pending FROM appointments WHERE status = 'Pending'";
$completed_query = "SELECT COUNT(*) AS completed FROM appointments WHERE status = 'Completed'";
$in_session_query = "SELECT COUNT(*) AS in_session FROM appointments WHERE status = 'In-Session'";

$pending_result = mysqli_query($conn, $pending_query);
$completed_result = mysqli_query($conn, $completed_query);
$in_session_result = mysqli_query($conn, $in_session_query);

$pending = mysqli_fetch_assoc($pending_result)['pending'];
$completed = mysqli_fetch_assoc($completed_result)['completed'];
$in_session = mysqli_fetch_assoc($in_session_result)['in_session'];

// Fetch daily enrollment data
$daily_enrollment_query = "
    SELECT DATE(created_at) AS day, COUNT(*) AS enrollments 
    FROM appointments 
    GROUP BY DATE(created_at)
    ORDER BY DATE(created_at) ASC";
$daily_enrollment_result = mysqli_query($conn, $daily_enrollment_query);

// Initialize arrays to hold data
$days = [];
$enrollments = [];

while ($row = mysqli_fetch_assoc($daily_enrollment_result)) {
    $days[] = $row['day'];
    $enrollments[] = $row['enrollments'];
}

// Convert PHP arrays to JSON for JavaScript
$days_json = json_encode($days);
$enrollments_json = json_encode($enrollments);
?>

<?php include 'header.php'; ?>

<!-- Sidebar and Notification -->
<style>
    .sidebar {
        background-color: #1e1e2f;
        color: white;
        padding: 20px;
        height: 100vh;
        position: fixed;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
    }
    .sidebar h1 {
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .sidebar a {
        color: white;
        text-decoration: none;
        margin: 15px 0;
        display: block;
        font-size: 18px; /* Bigger buttons */
        padding: 10px 15px;
        border-radius: 5px;
        transition: background-color 0.3s;
    }
    .sidebar a:hover {
        background-color: #2c2c3e; /* Hover effect */
    }
    .notification-wrapper {
        position: absolute;
        top: 20px;
        left: 180px; /* Adjust this to align it properly near the Admin text */
    }
    .notification-bell {
        font-size: 24px;
        color: #f39c12;
        cursor: pointer;
    }
    .badge-notification {
        position: absolute;
        top: -10px;
        right: -10px;
        background-color: red;
        color: white;
        border-radius: 50%;
        padding: 5px 10px;
        font-size: 12px;
    }
    .badge-notification span {
        font-size: 10px;
    }
    .chart-container {
        padding-left: 220px;
        padding-top: 30px;
    }
    canvas {
        height: 400px !important;
        width: auto;
    }
    .main-content {
        margin-left: 230px; /* Move main content to the right */
    }
    .logout-btn {
        margin-top: 30px;
        font-size: 18px;
        padding: 10px 20px;
        background-color: #dc3545;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s;
    }
    .logout-btn:hover {
        background-color: #c82333;
    }
</style>

<div class="sidebar">
    <h1>
    <h2 class="text-primary mb-4"><i class="fa fa-car text-white me-2"></i>Drivin</h2>

        <div class="notification-wrapper">
            <a href="notifications.php" class="notification-bell">
                <i class="fa fa-bell"></i>
                <?php if ($notification_count > 0): ?>
                    <span class="badge-notification">
                        <?php echo $notification_count; ?>
                    </span>
                <?php endif; ?>
            </a>
        </div>
    </h1>
    <a href="manage_instructors.php">Manage Instructors</a>
    <a href="instructor_availability.php">Instructor Availability</a>
    <a href="enrollment_insights.php">Enrollment Insights</a>
    <a href="manage_testimonials.php">Manage Testimonials</a>
    <a href="manage_enrollments.php">Manage Enrollments</a>
    <a href="deleted_appointments.php">View Deleted Appointments</a>
    <a href="admin_view_feedback.php" class="btn btn-primary mb-3">View User Feedback</a>


    <!-- Logout button -->
    <a href="logout.php" class="logout-btn">Logout</a>
</div>

<!-- Main Content Area -->
<div class="main-content">
    <div class="chart-container">
        <h2>Admin Dashboard</h2>

        <!-- Enrollment Statistics Section -->
        <div class="container my-5">
            <h2>Enrollment Statistics</h2>
            <div class="row">
                <div class="col-md-3">
                    <div class="card text-white bg-primary mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Total Enrollees</h5>
                            <p class="card-text"><?php echo $total_enrollees; ?></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card text-white bg-info mb-3">
                        <div class="card-body">
                            <h5 class="card-title">In Session</h5>
                            <p class="card-text"><?php echo $in_session; ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card text-white bg-warning mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Pending</h5>
                            <p class="card-text"><?php echo $pending; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-success mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Completed</h5>
                            <p class="card-text"><?php echo $completed; ?></p>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>

        <!-- Bar Chart Section -->
        <div class="container">
            <h3>Enrollment Trends Over Time (By Day)</h3>
            <canvas id="enrollmentChart"></canvas>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('enrollmentChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo $days_json; ?>,
            datasets: [{
                label: 'Enrollments Per Day',
                backgroundColor: 'rgba(54, 162, 235, 0.8)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                data: <?php echo $enrollments_json; ?>
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Enrollments'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Date'
                    }
                }
            }
        }
    });
</script>
