<?php
include 'header.php';

$conn = new mysqli('localhost', 'root', '', 'dsms');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$instructors = mysqli_query($conn, "SELECT * FROM instructors");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reg_no = $_POST['reg_no'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $gender = $_POST['gender'];
    $birthday = $_POST['birthday'];
    $contact_number = $_POST['contact_number'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $category = $_POST['category'];
    $training_package = $_POST['training_package'];
    $start_date = $_POST['start_date'];
    $preferred_time = $_POST['preferred_time'];
    $status = $_POST['status'];
    $instructor_id = $_POST['instructor_id'];

    $sql = "INSERT INTO appointments (reg_no, first_name, last_name, gender, birthday, contact_number, email, address, category, training_package, start_date, preferred_time, status, instructor_id) 
            VALUES ('$reg_no', '$first_name', '$last_name', '$gender', '$birthday', '$contact_number', '$email', '$address', '$category', '$training_package', '$start_date', '$preferred_time', '$status', '$instructor_id')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('New enrollee added successfully'); window.location.href='admin_dashboard.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}

?>

<div class="container my-5">
    <h1>Add New Enrollee</h1>
    <form method="POST" action="">
        <div class="row">
            <!-- Reg. No -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="reg_no" class="form-label">Reg. No</label>
                    <input type="text" class="form-control" id="reg_no" name="reg_no" required>
                </div>
            </div>
            <!-- First Name -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" required>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Last Name -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" required>
                </div>
            </div>
            <!-- Gender -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="gender" class="form-label">Gender</label>
                    <select class="form-control" id="gender" name="gender" required>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Birthday -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="birthday" class="form-label">Birthday</label>
                    <input type="date" class="form-control" id="birthday" name="birthday" required>
                </div>
            </div>
            <!-- Contact Number -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="contact_number" class="form-label">Contact Number</label>
                    <input type="text" class="form-control" id="contact_number" name="contact_number" required>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Email -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
            </div>
            <!-- Address -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" class="form-control" id="address" name="address" required>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Category -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="category" class="form-label">Category</label>
                    <select class="form-control" id="category" name="category" required>
                        <option value="First Category">First Category: For private cars with either manual or automatic transmission.</option>
                        <option value="Second Category">Second Category: For motorcycles.</option>
                        <option value="Third Category">Third Category: For construction and agricultural vehicles.</option>
                        <option value="Fourth Category">Fourth Category: Includes driving minibuses and medium buses.</option>
                        <option value="Fifth Category">Fifth Category: Involves driving trucks weighing more than 3.5 tons.</option>
                        <option value="Sixth Category">Sixth Category: For tractors and semi-trailers.</option>
                        <option value="Seventh Category">Seventh Category: Specifically for people with disabilities to drive specially designed vehicles.</option>
                    </select>
                </div>
            </div>
            <!-- Training Package -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="training_package" class="form-label">Training Package</label>
                    <select class="form-control" id="training_package" name="training_package" required>
                        <option value="Manual Car Training">Manual Car Training</option>
                        <option value="Automatic Car Training">Automatic Car Training</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Start Date -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" required>
                </div>
            </div>
            <!-- Preferred Time -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="preferred_time" class="form-label">Preferred Time</label>
                    <select class="form-control" id="preferred_time" name="preferred_time" required>
                        <option value="8:00 AM to 9:00 AM">8:00 AM to 9:00 AM</option>
                        <option value="9:00 AM to 10:00 AM">9:00 AM to 10:00 AM</option>
                        <!-- Add more time slots if necessary -->
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Status -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="Pending">Pending</option>
                        <option value="Verified">Verified</option>
                        <option value="In-Session">In-Session</option>
                        <option value="Completed">Completed</option>
                        <option value="Canceled">Canceled</option>
                    </select>
                </div>
            </div>
            <!-- Assign Instructor -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="instructor_id" class="form-label">Assign Instructor</label>
                    <select class="form-control" id="instructor_id" name="instructor_id">
                        <?php while ($row = mysqli_fetch_assoc($instructors)) { ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Add Enrollee</button>
    </form>
</div>

<?php include 'footer.php'; ?>
