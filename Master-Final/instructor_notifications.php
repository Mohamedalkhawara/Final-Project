<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['instructor_id'])) {
    header("Location: instructor_login.php");
    exit();
}

// Fetch all notifications for the logged-in instructor
$instructor_id = $_SESSION['instructor_id'];
$notification_query = "SELECT * FROM notifications WHERE instructor_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($notification_query);
$stmt->bind_param("i", $instructor_id);
$stmt->execute();
$notifications = $stmt->get_result();

include 'header.php';
?>

<div class="container my-5">
    <h1>Notifications</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Action</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($notification = $notifications->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $notification['action']; ?></td>
                <td><?php echo $notification['created_at']; ?></td>
                <td>
                    <?php if (!$notification['is_read']) { ?>
                        <a href="mark_as_read_instructor.php?id=<?php echo $notification['id']; ?>" class="btn btn-sm btn-primary">Mark as Read</a>
                    <?php } else { ?>
                        <span class="badge bg-success">Read</span>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>
