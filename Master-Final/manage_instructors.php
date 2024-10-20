<?php
// Include the database connection
include 'db_connect.php';

// Define the number of instructors per page
$limit = 5;

// Get the current page number from the URL, if not present, default to page 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Calculate the offset for the SQL query
$offset = ($page - 1) * $limit;

// Fetch the total number of instructors
$total_query = "SELECT COUNT(*) as total FROM instructors";
$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_instructors = $total_row['total'];

// Fetch instructors with pagination
$query = "SELECT * FROM instructors LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);

// Calculate the total number of pages
$total_pages = ceil($total_instructors / $limit);
?>

<?php include 'header.php'; ?>

<div class="container my-5">
    <h1>Manage Instructors</h1>
    <a href="admin_dashboard.php" class="btn btn-secondary mt-4">Back to Dashboard</a>
    <br><br>
    
    <!-- Button to add new instructor -->
    <a href="add_instructor.php" class="btn btn-success mb-3">Add New Instructor</a>
    
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Experience Years</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['phone']; ?></td>
                    <td><?php echo $row['experience_years']; ?></td>
                    <td><img src="uploads/<?php echo $row['image']; ?>" width="100" height="100"></td>
                    <td>
                        <a href="edit_instructor.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">Edit</a>
                        <a href="delete_instructor.php?id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure?');">Delete</a>
                    </td>
                </tr>
            <?php }
            } else {
                echo "<tr><td colspan='7' class='text-center'>No instructors found</td></tr>";
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
