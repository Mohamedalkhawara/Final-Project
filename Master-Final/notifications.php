<?php
include 'db_connect.php';
include 'header.php';
include 'navbar.php';

// Get all notifications ordered by most recent
$notification_query = "SELECT notifications.*, users.name 
                        FROM notifications 
                        LEFT JOIN users ON notifications.user_id = users.id 
                        ORDER BY notifications.created_at DESC";

$notifications_result = mysqli_query($conn, $notification_query);
?>

<div class="container my-5">
    <h1>Notifications</h1>

    <!-- Back Button -->
    <a href="admin_dashboard.php" class="btn btn-warning mb-3">
        <i class="fa fa-arrow-left"></i> Back
    </a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>User</th>
                <th>Action</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($notification = mysqli_fetch_assoc($notifications_result)) { ?>
            <tr>
                <td><?php echo $notification['name']; ?></td>
                <td>
                    <?php 
                    if ($notification['notification_type'] == 'testimonial') {
                        echo $notification['action']; // Display testimonial action
                    } elseif ($notification['notification_type'] == 'appointment') {
                        echo $notification['action']; // Display appointment action
                    } elseif ($notification['notification_type'] == 'feedback') { // Check if it's feedback
                        echo $notification['action']; // Display feedback action
                    } else {
                        echo "Other notification"; // Fallback in case no type matches
                    }
                    ?>
                </td>
                <td><?php echo $notification['created_at']; ?></td>
                <td>
                    <?php if (!$notification['is_read']) { ?>
                        <a href="mark_as_read.php?id=<?php echo $notification['id']; ?>" class="btn btn-sm btn-primary">Mark as Read</a>
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
