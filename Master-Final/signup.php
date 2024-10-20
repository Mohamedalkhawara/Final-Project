<?php
// Include the database connection file
include 'db_connect.php';

$signup_success_message = '';
$signup_error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $reg_number = $_POST['reg_number'];
    $dob = $_POST['dob'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $image = '';

    // Check for duplicate email or reg_number
    $email_check_query = "SELECT * FROM users WHERE email = ?";
    $reg_number_check_query = "SELECT * FROM users WHERE reg_number = ?";
    $email_check_stmt = $conn->prepare($email_check_query);
    $reg_number_check_stmt = $conn->prepare($reg_number_check_query);

    $email_check_stmt->bind_param("s", $email);
    $email_check_stmt->execute();
    $email_check_result = $email_check_stmt->get_result();

    $reg_number_check_stmt->bind_param("s", $reg_number);
    $reg_number_check_stmt->execute();
    $reg_number_check_result = $reg_number_check_stmt->get_result();

    if ($email_check_result->num_rows > 0) {
        $signup_error_message = 'The email is already registered.';
    } elseif ($reg_number_check_result->num_rows > 0) {
        $signup_error_message = 'The registration number is already registered.';
    }
    // Validate name (4 parts: first name, middle name, last name, family name)
    elseif (count(explode(' ', trim($name))) < 4) {
        $signup_error_message = 'Please enter your full name (First, Middle, Last, Family).';
    }
    // Validate phone number (must start with 07 and have 10 digits)
    elseif (!preg_match("/^07[0-9]{8}$/", $phone)) {
        $signup_error_message = 'Phone number must start with "07" and be 10 digits long.';
    }
    // Validate registration number (must be exactly 10 digits)
    elseif (!preg_match("/^[0-9]{10}$/", $reg_number)) {
        $signup_error_message = 'Registration number must be exactly 10 digits.';
    }
    // Validate date of birth (user must be 18 or older)
    elseif ((new DateTime())->diff(new DateTime($dob))->y < 18) {
        $signup_error_message = 'You must be at least 18 years old to register.';
    }
    // Validate password match
    elseif ($password !== $confirm_password) {
        $signup_error_message = 'Passwords do not match.';
    }
    // Handle file upload for image
    elseif (!empty($_FILES["image"]["name"])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = $target_file;
        } else {
            $signup_error_message = 'There was an error uploading the image.';
        }
    }

    // If no errors, proceed with user registration
    if (empty($signup_error_message)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (name, email, phone, reg_number, dob, password, image, role_id) VALUES (?, ?, ?, ?, ?, ?, ?, 3)");
        $stmt->bind_param("sssssss", $name, $email, $phone, $reg_number, $dob, $hashed_password, $image);

        if ($stmt->execute()) {
            $signup_success_message = 'Registration successful. You can now log in.';
        } else {
            $signup_error_message = 'Registration failed. Please try again.';
        }

        $stmt->close();
    }

    // Close prepared statements
    $email_check_stmt->close();
    $reg_number_check_stmt->close();
}

$conn->close();

include 'header.php';
include 'navbar.php';
?>

<!-- Signup HTML Code -->
<div class="container-xxl py-6">
    <div class="container">
        <div class="row g-5 justify-content-center">
            <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                <div class="bg-light p-5 text-center">
                    <h1 class="mb-4">Sign Up</h1>
                    <!-- Display success or error message -->
                    <?php if (!empty($signup_success_message)) { ?>
                        <div class="alert alert-success">
                            <?php echo $signup_success_message; ?>
                        </div>
                    <?php } ?>
                    <?php if (!empty($signup_error_message)) { ?>
                        <div class="alert alert-danger">
                            <?php echo $signup_error_message; ?>
                        </div>
                    <?php } ?>
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control border-0" id="name" name="name" placeholder="Your Full Name" required>
                                    <label for="name">Full Name (First, Middle, Last, Family)</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="email" class="form-control border-0" id="email" name="email" placeholder="Your Email" required>
                                    <label for="email">Your Email</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control border-0" id="phone" name="phone" placeholder="Phone Number" required>
                                    <label for="phone">Phone Number (07XXXXXXXX)</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control border-0" id="reg_number" name="reg_number" placeholder="Registration Number" required>
                                    <label for="reg_number">Registration Number (10 digits)</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="date" class="form-control border-0" id="dob" name="dob" placeholder="Date of Birth" required>
                                    <label for="dob">Date of Birth</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="password" class="form-control border-0" id="password" name="password" placeholder="Your Password" required>
                                    <label for="password">Your Password</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="password" class="form-control border-0" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                                    <label for="confirm_password">Confirm Password</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="file" class="form-control border-0" id="image" name="image" placeholder="Upload Image" required>
                                    <label for="image">Profile Image</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary w-100 py-3" type="submit">Sign Up</button>
                            </div>
                            <div class="col-12">
                                <p class="mb-0">Already have an account? <a href="login.php">Login</a></p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
