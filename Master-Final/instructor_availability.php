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
$total_query = "SELECT COUNT(DISTINCT instructors.id) as total FROM instructors";
$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_instructors = $total_row['total'];

// Fetch instructors with the number of students assigned to them and their schedules with pagination
$query = "
    SELECT 
        instructors.id, 
        instructors.name, 
        instructors.email, 
        instructors.phone, 
        instructors.experience_years, 
        instructors.image, 
        COUNT(appointments.id) AS student_count, 
        GROUP_CONCAT(DISTINCT CONCAT(appointments.start_date, ' ', appointments.preferred_time) SEPARATOR '<br>') AS schedules
    FROM instructors
    LEFT JOIN appointments ON appointments.instructor_id = instructors.id
    GROUP BY instructors.id
";

$result = $conn->query($query);

// Calculate total number of pages
$total_pages = ceil($total_instructors / $limit);
?>

<?php include 'header.php'; ?>

<div class="container my-5">
    <h2>Instructor Availability</h2>
    <a href="admin_dashboard.php" class="btn btn-primary">Back to Dashboard</a>
    <br>
    <br>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Instructor</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Experience (Years)</th>
                <th>Number of Students</th>
                <th>Schedules</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>
                            <img src='uploads/" . $row['image'] . "' alt='Instructor Image' style='width: 50px; height: 50px; object-fit: cover;'>
                            " . $row['name'] . "
                        </td>
                        <td>" . $row['email'] . "</td>
                        <td>" . $row['phone'] . "</td>
                        <td>" . $row['experience_years'] . "</td>
                        <td>" . $row['student_count'] . "</td>
                        <td>" . (!empty($row['schedules']) ? $row['schedules'] : 'No schedules') . "</td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No instructors found</td></tr>";
            }
            ?>
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
