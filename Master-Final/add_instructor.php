<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $experience_years = $_POST['experience_years'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hashing the password
    $image = $_FILES['image']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($image);

    // Move uploaded file
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // Insert into the instructors table
        $sql = "INSERT INTO instructors (name, email, phone, experience_years, password, image) VALUES ('$name', '$email', '$phone', '$experience_years', '$password', '$image')";
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Instructor added successfully!'); window.location.href='manage_instructors.php';</script>";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>

<?php include 'header.php'; ?>

<div class="container my-5">
    <h1>Add New Instructor</h1>
    <form method="POST" action="add_instructor.php" enctype="multipart/form-data" onsubmit="return validatePassword();">
        <div class="form-group mb-3">
            <label>Name:</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label>Email:</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label>Phone:</label>
            <input type="text" name="phone" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label>Experience Years:</label>
            <input type="number" name="experience_years" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label>Password:</label>
            <input type="password" name="password" class="form-control" id="password" required>
        </div>
        <div class="form-group mb-3">
            <label>Confirm Password:</label>
            <input type="password" name="confirm_password" class="form-control" id="confirm_password" required>
        </div>
        <div class="form-group mb-3">
            <label>Image:</label>
            <input type="file" name="image" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Instructor</button>
    </form>
</div>

<?php include 'footer.php'; ?>

<!-- JavaScript to Validate Password Matching -->
<script>
function validatePassword() {
    var password = document.getElementById("password").value;
    var confirmPassword = document.getElementById("confirm_password").value;

    if (password !== confirmPassword) {
        alert("Passwords do not match.");
        return false;
    }
    return true;
}
</script>
