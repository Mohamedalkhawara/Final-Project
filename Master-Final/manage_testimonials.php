<?php
// Include the database connection
include 'db_connect.php';
session_start();

// Ensure the user is an admin
if ($_SESSION['role_id'] != 1) { // Assuming role_id 1 is for admin
    header("Location: index.php");
    exit;
}

// Define the number of testimonials per page
$limit = 5;

// Get the current page number from the URL, if not present, default to page 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Calculate the offset for the SQL query
$offset = ($page - 1) * $limit;

// Fetch the total number of pending testimonials
$total_query = "SELECT COUNT(*) as total FROM testimonials WHERE status = 'Pending'";
$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_testimonials = $total_row['total'];

// Fetch pending testimonials with pagination
$pending_query = "
    SELECT t.id, t.testimonial, u.name, u.image 
    FROM testimonials t 
    JOIN users u ON t.user_id = u.id 
    WHERE t.status = 'Pending' 
    LIMIT $limit OFFSET $offset";
$pending_result = mysqli_query($conn, $pending_query);

// Calculate the total number of pages
$total_pages = ceil($total_testimonials / $limit);
?>

<?php include 'header.php'; ?>

<div class="container my-5">
    <h2>Pending Testimonials</h2>
    <a href="admin_dashboard.php" class="btn btn-secondary mt-4">Back to Dashboard</a>
    <br><br><br>

    <!-- Table to display pending testimonials -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>User</th>
                <th>Testimonial</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            // Check if there are testimonials to display
            if (mysqli_num_rows($pending_result) > 0) {
                while ($row = mysqli_fetch_assoc($pending_result)) { ?>
                <tr>
                    <td>
                        <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>" style="width: 50px; height: 50px;">
                        <?php echo $row['name']; ?>
                    </td>
                    <td><?php echo $row['testimonial']; ?></td>
                    <td>
                        <a href="approve_testimonial.php?id=<?php echo $row['id']; ?>" class="btn btn-success" 
                           onclick="return confirm('Are you sure you want to approve this testimonial?');">Approve</a>
                        <a href="reject_testimonial.php?id=<?php echo $row['id']; ?>" class="btn btn-danger" 
                           onclick="return confirm('Are you sure you want to reject this testimonial?');">Reject</a>
                    </td>
                </tr>
                <?php }
            } else {
                echo "<tr><td colspan='3' class='text-center'>No pending testimonials at the moment.</td></tr>";
            } ?>
        </tbody>
    </table>

    <!-- Pagination Links -->
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <!-- Previous Page Link -->
            <?php if ($page > 1): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <?php endif; ?>

            <!-- Page Number Links -->
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
            <?php endfor; ?>

            <!-- Next Page Link -->
            <?php if ($page < $total_pages): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
            <?php endif; ?>
        </ul>
    </nav>

</div>

<?php include 'footer.php'; ?>
