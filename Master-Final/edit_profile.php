<?php
session_start();
include 'db_connect.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch the user's information from the database
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "<script>
        alert('User not found!');
        window.location.href = 'my_appointments.php';
    </script>";
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $dob = $_POST['dob']; // Date of Birth
    $reg_number = $_POST['reg_number'];

    // Validate form inputs
    if (empty($name) || empty($email) || empty($phone) || empty($address) || empty($dob) || empty($reg_number)) {
        echo "<script>
            alert('All fields are required. Please fill out the entire form.');
        </script>";
    } else {
        // Update the user information
        $sql = "UPDATE users SET name = ?, email = ?, phone = ?, address = ?, dob = ?, reg_number = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssssi', $name, $email, $phone, $address, $dob, $reg_number, $user_id);

        if ($stmt->execute()) {
            echo "<script>
                alert('Profile Updated Successfully');
                window.location.href = 'my_appointments.php';
            </script>";
        } else {
            echo "<script>
                alert('Unable to update profile. Please try again.');
            </script>";
        }
    }
}
?>

<?php include 'header.php'; ?>

<div class="container">
    <h2>Edit Profile</h2>
    <form action="edit_profile.php" method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Phone Number</label>
            <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="dob" class="form-label">Date of Birth</label>
            <input type="date" class="form-control" id="dob" name="dob" value="<?php echo htmlspecialchars($user['dob']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="reg_number" class="form-label">Registration Number</label>
            <input type="text" class="form-control" id="reg_number" name="reg_number" value="<?php echo htmlspecialchars($user['reg_number']); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
</div>

<?php include 'footer.php'; ?>
