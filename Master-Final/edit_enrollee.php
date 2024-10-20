<?php
include 'header.php';
include 'navbar.php';

$conn = new mysqli('localhost', 'root', '', 'dsms');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $training_package = $_POST['training_package'];
    $status = $_POST['status'];

    $sql = "UPDATE appointments SET first_name='$first_name', last_name='$last_name', email='$email', training_package='$training_package', status='$status' WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Enrollee updated successfully'); window.location.href='admin_dashboard.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
} else {
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM appointments WHERE id='$id'");
    $enrollee = $result->fetch_assoc();
}

?>

<div class="container my-5">
    <h1>Edit Enrollee</h1>
    <form method="POST" action="">
        <input type="hidden" name="id" value="<?php echo $enrollee['id']; ?>">
        <div class="mb-3">
            <label for="first_name" class="form-label">First Name</label>
            <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $enrollee['first_name']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="last_name" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $enrollee['last_name']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo $enrollee['email']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="training_package" class="form-label">Training Package</label>
            <input type="text" class="form-control" id="training_package" name="training_package" value="<?php echo $enrollee['training_package']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-control" id="status" name="status" required>
                <option value="Pending" <?php if($enrollee['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                <option value="Verified" <?php if($enrollee['status'] == 'Verified') echo 'selected'; ?>>Verified</option>
                <option value="In-Session" <?php if($enrollee['status'] == 'In-Session') echo 'selected'; ?>>In-Session</option>
                <option value="Completed" <?php if($enrollee['status'] == 'Completed') echo 'selected'; ?>>Completed</option>
                <option value="Canceled" <?php if($enrollee['status'] == 'Canceled') echo 'selected'; ?>>Canceled</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update Enrollee</button>
    </form>
</div>

<?php include 'footer.php'; ?>
