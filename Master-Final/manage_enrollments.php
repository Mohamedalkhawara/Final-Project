<?php 
session_start();
include 'db_connect.php';

// Handle search and filter input
$search = isset($_GET['search']) ? $_GET['search'] : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$instructor_filter = isset($_GET['instructor']) ? $_GET['instructor'] : '';

// Pagination variables
$limit = 10; // Number of records per page
$page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page
$offset = ($page - 1) * $limit; // Offset for the SQL query

// Prepare SQL query with filters
$query = "
    SELECT appointments.*, 
           CONCAT(u.name) AS user_full_name, 
           instructors.name as instructor_name, 
           u.reg_number,
           appointments.preferred_time,
           appointments.start_date  -- Fetch the start_date field
    FROM appointments 
    LEFT JOIN users u ON appointments.user_id = u.id 
    LEFT JOIN instructors ON appointments.instructor_id = instructors.id
    WHERE (u.name LIKE '%$search%' OR u.reg_number LIKE '%$search%' OR appointments.training_package LIKE '%$search%')
";

// Apply status filter if provided
if (!empty($status_filter)) {
    $query .= " AND appointments.status = '$status_filter'";
}

// Apply instructor filter if provided
if (!empty($instructor_filter)) {
    $query .= " AND instructors.id = '$instructor_filter'";
}

// Count total records for pagination
$total_query = $query;
$total_result = $conn->query($total_query);
$total_records = $total_result->num_rows;
$total_pages = ceil($total_records / $limit);

// Apply limit and offset for pagination
$query .= " ORDER BY appointments.created_at DESC LIMIT $limit OFFSET $offset";
$result = $conn->query($query);
?>

<?php include 'header.php'; ?>

<div class="container my-5">
    <h1>Manage Enrollments</h1>
    <a href="admin_dashboard.php" class="btn btn-secondary mt-4">Back to Dashboard</a>
    <br>
    <br>
    
    <!-- Search and Filter Form -->
    <form method="GET" action="manage_enrollments.php" class="mb-3">
        <div class="row">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search by reg no, name, or package" value="<?php echo $search; ?>">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-control">
                    <option value="">All Statuses</option>
                    <option value="Pending" <?php if ($status_filter == 'Pending') echo 'selected'; ?>>Pending</option>
                    <option value="Completed" <?php if ($status_filter == 'Completed') echo 'selected'; ?>>Completed</option>
                    <option value="In-Session" <?php if ($status_filter == 'In-Session') echo 'selected'; ?>>In-Session</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="instructor" class="form-control">
                    <option value="">All Instructors</option>
                    <?php
                    // Fetch instructors to populate the dropdown
                    $instructor_result = $conn->query("SELECT * FROM instructors");
                    while ($instructor = $instructor_result->fetch_assoc()) {
                        echo '<option value="' . $instructor['id'] . '"';
                        if ($instructor_filter == $instructor['id']) echo ' selected';
                        echo '>' . $instructor['name'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Date Created</th>
                <th>Start Date</th>  <!-- New Start Date Column -->
                <th>Reg. No</th>
                <th>Fullname</th>
                <th>Package</th>
                <th>Preferred Time</th>
                <th>Status</th>
                <th>Instructor</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>" . $row['id'] . "</td>
                        <td>" . $row['created_at'] . "</td>
                        <td>" . $row['start_date'] . "</td>  <!-- Fetch and display the Start Date -->
                        <td>" . $row['reg_number'] . "</td>
                        <td>" . $row['user_full_name'] . "</td>
                        <td>" . $row['training_package'] . "</td>
                        <td>" . $row['preferred_time'] . "</td>
                        <td><span class='badge bg-" . ($row['status'] == 'Pending' ? 'warning' : ($row['status'] == 'Verified' ? 'primary' : ($row['status'] == 'In-Session' ? 'info' : ($row['status'] == 'Completed' ? 'success' : 'danger')))) . "'>" . $row['status'] . "</span></td>
                        <td>" . (!empty($row['instructor_name']) ? $row['instructor_name'] : 'Not Assigned') . "</td>
                        <td>
                            <div class='btn-group'>
                                <button type='button' class='btn btn-secondary dropdown-toggle' data-bs-toggle='dropdown' aria-expanded='false'>
                                    Action
                                </button>
                                <ul class='dropdown-menu'>
                                    <li><a class='dropdown-item' href='update_status.php?id=" . $row['id'] . "&status=Verified'>Verify</a></li>
                                    <li><a class='dropdown-item' href='update_status.php?id=" . $row['id'] . "&status=In-Session'>Mark In-Session</a></li>
                                    <li><a class='dropdown-item' href='update_status.php?id=" . $row['id'] . "&status=Completed'>Complete</a></li>
                                    <li><a class='dropdown-item' href='update_status.php?id=" . $row['id'] . "&status=Canceled'>Cancel</a></li>
                                    <li><a class='dropdown-item' href='assign_instructor.php?id=" . $row['id'] . "'>Assign Instructor</a></li>
                                    <li><a class='dropdown-item' href='view_appointment.php?id=" . $row['id'] . "'>View</a></li>
                                    <li><a class='dropdown-item' href='delete_enrollee.php?id=" . $row['id'] . "' onclick=\"return confirm('Are you sure you want to delete this enrollee?');\">Delete</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='10' class='text-center'>No records found</td></tr>";
            }

            $conn->close();
            ?>
        </tbody>
    </table>

    <!-- Pagination Links -->
    <nav>
        <ul class="pagination">
            <?php if ($page > 1): ?>
                <li class="page-item"><a class="page-link" href="?page=<?php echo $page-1; ?>&search=<?php echo $search; ?>&status=<?php echo $status_filter; ?>&instructor=<?php echo $instructor_filter; ?>">Previous</a></li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo $search; ?>&status=<?php echo $status_filter; ?>&instructor=<?php echo $instructor_filter; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <li class="page-item"><a class="page-link" href="?page=<?php echo $page+1; ?>&search=<?php echo $search; ?>&status=<?php echo $status_filter; ?>&instructor=<?php echo $instructor_filter; ?>">Next</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</div>

<?php include 'footer.php'; ?>
