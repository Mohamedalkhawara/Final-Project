<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['instructor_id'])) {
    header("Location: instructor_login.php");
    exit();
}

$instructor_id = $_SESSION['instructor_id'];

// Fetch instructor's current details
$sql = "SELECT * FROM instructors WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $instructor_id);
$stmt->execute();
$instructor = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $phone = $_POST['phone'];
    $password = $_POST['password'] ? password_hash($_POST['password'], PASSWORD_BCRYPT) : $instructor['password'];

    // Update the instructor's information
    $update_sql = "UPDATE instructors SET phone = ?, password = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssi", $phone, $password, $instructor_id);
    $update_stmt->execute();

    echo "<script>alert('Profile updated successfully');</script>";
}

include 'header.php';
?>

<div class="container my-5">
    <h1>Edit Profile</h1>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" value="<?php echo $instructor['name']; ?>" disabled>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" class="form-control" name="phone" value="<?php echo $instructor['phone']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">New Password (Leave blank if unchanged)</label>
            <input type="password" class="form-control" name="password">
        </div>
        <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
</div>

<?php include 'footer.php'; ?>
