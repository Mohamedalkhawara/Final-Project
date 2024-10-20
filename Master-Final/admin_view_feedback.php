<?php
session_start();
include 'db_connect.php';


$search_query = '';
if (isset($_POST['search'])) {
    $search_query = $_POST['search'];
}

// Fetch all feedback with optional search query
$sql = "SELECT f.*, u.name as user_name, i.name as instructor_name 
        FROM feedback f
        JOIN users u ON f.user_id = u.id
        JOIN instructors i ON f.instructor_id = i.id
        WHERE u.name LIKE ?";

$search_param = '%' . $search_query . '%';
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $search_param);
$stmt->execute();
$feedbacks = $stmt->get_result();

include 'header.php';
?>

<div class="container my-5">
    <h1>User Feedback</h1>

    <a href="admin_dashboard.php" class="btn btn-secondary mt-4">Back to Dashboard</a>
<br>
<br>

    <form action="admin_view_feedback.php" method="POST" class="mb-3">
        <input type="text" name="search" placeholder="Search by user name" value="<?php echo $search_query; ?>">
        <input type="submit" class="btn btn-primary" value="Search">
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>User Name</th>
                <th>Instructor Name</th>
                <th>Feedback</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $feedbacks->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['user_name']; ?></td>
                    <td><?php echo $row['instructor_name']; ?></td>
                    <td><?php echo $row['feedback']; ?></td>
                    <td><?php echo $row['created_at']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>
