<?php
session_start();
include 'db_connect.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user data from the database
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    $reg_no = $user['reg_number'] ?? ''; // Update to reg_number
    $name = $user['name'] ?? '';  // Update to name
    $gender = $user['gender'] ?? '';
    $birthday = $user['dob'] ?? '';  // Update to dob
    $phone_number = $user['phone'] ?? '';
    $email = $user['email'] ?? '';
    $address = $user['address'] ?? '';
} else {
    // Handle case where user is not found
    $reg_no = '';
    $name = '';
    $gender = '';
    $birthday = '';
    $phone_number = '';
    $email = '';
    $address = '';
}

// Error messages initialization
$errors = array('reg_no' => '', 'name' => '', 'age' => '', 'email' => '', 'phone' => '', 'start_date' => '');

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reg_no = $_POST['reg_no'];
    $name = $_POST['name'];  // Update to name
    $gender = $_POST['gender'];
    $birthday = $_POST['birthday'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $category = $_POST['category'];
    $start_date = $_POST['start_date'];
    $preferred_time = $_POST['preferred_time'];

    // Validation logic...
    // Registration number validation (exactly 10 digits)
    if (!preg_match('/^[0-9]{10}$/', $reg_no)) {
        $errors['reg_no'] = "Registration number must be exactly 10 digits.";
    }

    // Name validation
    if (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
        $errors['name'] = "Full name must contain only letters and spaces.";
    }

    // Age calculation and validation
    $age = date_diff(date_create($birthday), date_create('today'))->y;
    if ($age < 18) {
        $errors['age'] = "You must be at least 18 years old to book a course.";
    }

    // Email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    }

    // Phone number validation (Jordan format, 10 digits)
    if (!preg_match('/^[0-9]{10}$/', $phone_number)) {
        $errors['phone'] = "Invalid phone number. Must be 10 digits.";
    }

    // Start date validation
    $today = date('Y-m-d');
    if ($start_date < $today) {
        $errors['start_date'] = "Start date cannot be before today.";
    }

    // If no errors, insert the data into the database
    if (array_filter($errors) === []) {
        $training_package = ($category == "First Category") ? $_POST['training_package'] : "Manual Car Training"; 

        // Correct SQL query for the appointments table
$sql = "INSERT INTO appointments (user_id, reg_no, gender, birthday, contact_number, email, address, training_package, start_date, preferred_time, category, status)
        VALUES ('$user_id', '$reg_no',  '$gender', '$birthday', '$contact_number', '$email', '$address', '$training_package', '$start_date', '$preferred_time', '$category', 'Pending')";

        if (mysqli_query($conn, $sql)) {
            header("Location: my_appointments.php");
            exit();
        } else {
            echo "<script>alert('Error booking appointment. Please try again later.');</script>";
        }
    }
}

include 'header.php';
include 'navbar.php';
?>

<!-- Appointment Form -->
<div class="container-xxl py-6">
    <div class="container">
        <h1 class="mb-4">Make An Appointment To Pass Test & Get A License On The First Try</h1>
        <a href="license_instructions.php" class="btn btn-primary mb-3" style="background-color: #f8b500; border-color: #f8b500;">Driving License Instructions</a>
        <p class="text-muted mb-5">Please read the Driving License Instructions before booking your appointment.</p>

        <div class="row g-5">
            <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                <div class="position-relative overflow-hidden ps-5 pt-5 h-100" style="min-height: 300px;">
                    <img class="position-absolute w-100 h-100" src="img/about-1.jpg" alt="" style="object-fit: cover;">
                    <img class="position-absolute top-0 start-0 bg-white pe-3 pb-3" src="img/about-2.jpg" alt="" style="width: 150px; height: 150px;">
                </div>
            </div>
            <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.5s">
                <form action="appointment.php" method="POST">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="form-floating">
                                <input type="text" class="form-control border-0 bg-light" id="reg_no" name="reg_no" placeholder="Reg. No" value="<?php echo htmlspecialchars($reg_no); ?>" required>
                                <label for="reg_no">Reg. No</label>
                                <small class="text-danger"><?php echo $errors['reg_no']; ?></small>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-floating">
                                <input type="text" class="form-control border-0 bg-light" id="name" name="name" placeholder="Full Name" value="<?php echo htmlspecialchars($name); ?>" required>
                                <label for="name">Full Name</label>
                                <small class="text-danger"><?php echo $errors['name']; ?></small>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-floating">
                                <select class="form-control border-0 bg-light" id="gender" name="gender">
                                    <option value="Male" <?php echo ($gender == 'Male') ? 'selected' : ''; ?>>Male</option>
                                    <option value="Female" <?php echo ($gender == 'Female') ? 'selected' : ''; ?>>Female</option>
                                </select>
                                <label for="gender">Gender</label>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-floating">
                                <input type="date" class="form-control border-0 bg-light" id="birthday" name="birthday" placeholder="Birthday" value="<?php echo htmlspecialchars($birthday); ?>" required>
                                <label for="birthday">Birthday</label>
                                <small class="text-danger"><?php echo $errors['age']; ?></small>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-floating">
                                <input type="text" class="form-control border-0 bg-light" id="phone_number" name="phone_number" placeholder="Phone Number" value="<?php echo htmlspecialchars($phone_number); ?>" required>
                                <label for="phone_number">Phone Number</label>
                                <small class="text-danger"><?php echo $errors['phone']; ?></small>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-floating">
                                <input type="email" class="form-control border-0 bg-light" id="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($email); ?>" required>
                                <label for="email">Email</label>
                                <small class="text-danger"><?php echo $errors['email']; ?></small>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <input type="text" class="form-control border-0 bg-light" id="address" name="address" placeholder="Address" value="<?php echo htmlspecialchars($address); ?>" required>
                                <label for="address">Address</label>
                            </div>
                        </div>

                        <!-- Category and Training Package -->
                        <div class="col-sm-6">
                            <div class="form-floating">
                                <select class="form-control border-0 bg-light" id="category" name="category" required onchange="toggleTrainingPackage()">
                                    <option value="First Category">First Category: For private cars with either manual or automatic transmission.</option>
                                    <option value="Second Category">Second Category: For motorcycles.</option>
                                    <option value="Third Category">Third Category: For construction and agricultural vehicles.</option>
                                    <option value="Fourth Category">Fourth Category: Includes driving minibuses and medium buses.</option>
                                    <option value="Fifth Category">Fifth Category: Involves driving trucks weighing more than 3.5 tons.</option>
                                    <option value="Sixth Category">Sixth Category: For tractors and semi-trailers.</option>
                                    <option value="Seventh Category">Seventh Category: Specifically for people with disabilities to drive specially designed vehicles.</option>
                                </select>
                                <label for="category">Category</label>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-floating" id="training-package-group">
                                <select class="form-control border-0 bg-light" id="training_package" name="training_package">
                                    <option value="Manual Car Training">Manual Car Training</option>
                                    <option value="Automatic Car Training">Automatic Car Training</option>
                                </select>
                                <label for="training_package">Training Package</label>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-floating">
                                <input type="date" class="form-control border-0 bg-light" id="start_date" name="start_date" placeholder="Start Date" value="<?php echo htmlspecialchars($start_date); ?>" required>
                                <label for="start_date">Start Date</label>
                                <small class="text-danger"><?php echo $errors['start_date']; ?></small>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-floating">
                                <select class="form-control border-0 bg-light" id="preferred_time" name="preferred_time">
                                    <option value="8:00 AM to 9:00 AM">8:00 AM to 9:00 AM</option>
                                    <option value="9:00 AM to 10:00 AM">9:00 AM to 10:00 AM</option>
                                </select>
                                <label for="preferred_time">Preferred Time</label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="col-12">
                            <button class="btn btn-primary w-100 py-3" type="submit">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Appointment End -->

<script>
function toggleTrainingPackage() {
    var category = document.getElementById("category").value;
    var trainingPackageGroup = document.getElementById("training-package-group");

    if (category === "First Category") {
        trainingPackageGroup.style.display = "block";
    } else {
        trainingPackageGroup.style.display = "none";
    }
}
</script>

<?php include 'footer.php'; ?>
