<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
include 'db_connect.php';

// Get count of unread notifications for the logged-in admin
$notification_count = 0;
if (isset($_SESSION['user_id'])) {
    $notification_query = "SELECT COUNT(*) AS unread_count FROM notifications WHERE is_read = 0";
    $notification_result = mysqli_query($conn, $notification_query);
    if ($notification_result) {
        $notification_data = mysqli_fetch_assoc($notification_result);
        $notification_count = $notification_data['unread_count'];
    }
}

// Fetch user name
$user_name = '';
$user_initials = '';
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $user_query = "SELECT name FROM users WHERE id = ?";
    $stmt = $conn->prepare($user_query);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $user_name = $user['name'];
        
        // Get the first two letters of the name for the initials
        $name_parts = explode(' ', $user_name);
        $user_initials = strtoupper(substr($name_parts[0], 0, 2)); // Use only the first name and take the first two letters
    }
}
?>

<nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top p-0">
    <a href="index.php" class="navbar-brand d-flex align-items-center border-end px-4 px-lg-5">
        <h2 class="m-0"><i class="fa fa-car text-primary me-2"></i>Drivin</h2>
    </a>
    <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav ms-auto p-4 p-lg-0">
            <a href="index.php" class="nav-item nav-link">Home</a>
            <a href="#about-us" class="nav-item nav-link">About</a>
            <a href="courses.php" class="nav-item nav-link">Courses</a>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
                <div class="dropdown-menu bg-light m-0">
                    <a href="feature.php" class="dropdown-item">Features</a>
                    <a href="appointment.php" class="dropdown-item">Appointment</a>
                    <a href="team.php" class="dropdown-item">Our Team</a>
                    <a href="testimonial.php" class="dropdown-item">Testimonial</a>
                </div>
            </div>
            <a href="contact.php" class="nav-item nav-link">Contact</a>

            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="nav-item dropdown d-flex align-items-center">
                    <!-- User Icon with Initials -->
                    <div class="user-icon d-flex justify-content-center align-items-center">
                        <?php echo htmlspecialchars($user_initials); ?>
                    </div>
                    <a href="#" class="nav-link dropdown-toggle ms-2" data-bs-toggle="dropdown">
                        <?php echo htmlspecialchars($user_name); ?>
                    </a>
                    <div class="dropdown-menu bg-light m-0">
                        <a href="my_appointments.php" class="dropdown-item">My Appointments</a>
                        <a href="edit_profile.php" class="dropdown-item">Edit Profile</a>
                        <a href="logout.php" class="dropdown-item">Logout</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="login.php" class="nav-item nav-link">Login</a>
                <a href="signup.php" class="nav-item nav-link">Sign Up</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<!-- Custom CSS -->
<style>
    .user-icon {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background-color: #007bff; /* Set a background color */
        color: white; /* Set text color */
        font-weight: bold;
        font-size: 16px;
        display: inline-flex;
        justify-content: center;
        align-items: center;
    }
</style>
