<?php
// Include the database connection
include 'db_connect.php';

$id = $_GET['id'];
$query = "SELECT * FROM instructors WHERE id = $id";
$result = mysqli_query($conn, $query);
$instructor = mysqli_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $experience_years = $_POST['experience_years'];
    $image = $instructor['image'];  // Preserve the old image if no new image is uploaded

    // Handle file upload if a new image is selected
    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if file is an image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            echo "File is not an image.";
        } else {
            // Allow certain file formats
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            } else {
                // Attempt to move the uploaded file to the uploads directory
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    $image = basename($_FILES["image"]["name"]);  // Save the new image file name to the database
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
        }
    }

    // Update data in the database
    $query = "UPDATE instructors SET name='$name', email='$email', phone='$phone', experience_years='$experience_years', image='$image' WHERE id=$id";
    if (mysqli_query($conn, $query)) {
        echo "Instructor updated successfully";
        header("Location: manage_instructors.php");  // Redirect to the list of instructors after update
        exit;
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}

?>

<?php include 'header.php'; ?>
<?php include 'navbar.php'; ?>

<div class="container my-5">
    <h1>Edit Instructor</h1>
    <form action="edit_instructor.php?id=<?php echo $instructor['id']; ?>" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($instructor['name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($instructor['email']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($instructor['phone']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="experience_years" class="form-label">Experience Years</label>
            <input type="number" name="experience_years" class="form-control" value="<?php echo htmlspecialchars($instructor['experience_years']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Image</label>
            <input type="file" name="image" class="form-control">
            <?php if (!empty($instructor['image'])): ?>
                <img src="uploads/<?php echo htmlspecialchars($instructor['image']); ?>" width="100" height="100">
            <?php else: ?>
                <p>No image uploaded</p>
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary">Update Instructor</button>
    </form>
</div>

<?php include 'footer.php'; ?>
