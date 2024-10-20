<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM instructors WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($instructor_id, $hashed_password);

    if ($stmt->num_rows == 1) {
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            $_SESSION['instructor_id'] = $instructor_id;
            $_SESSION['email'] = $email;
            header("Location: instructor_dashboard.php");
            exit;
        } else {
            $error_message = 'Incorrect password.';
        }
    } else {
        $error_message = 'No account found with that email.';
    }

    $stmt->close();
}

$conn->close();
include 'header.php';
?>

<div class="container-xxl py-6">
    <div class="container">
        <div class="row g-5 justify-content-center">
            <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                <div class="bg-light p-5 text-center">
                    <h1 class="mb-4">Instructor Login</h1>
                    <!-- Display error message if any -->
                    <?php if (!empty($error_message)) { ?>
                        <div class="alert alert-danger">
                            <?php echo $error_message; ?>
                        </div>
                    <?php } ?>
                    <form method="POST" action="instructor_login.php">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="email" class="form-control border-0" id="email" name="email" placeholder="Your Email" required>
                                    <label for="email">Your Email</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="password" class="form-control border-0" id="password" name="password" placeholder="Your Password" required>
                                    <label for="password">Your Password</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary w-100 py-3" type="submit">Login</button>
                            </div>
                            <!-- Add the Back button -->
                            <div class="col-12">
                                <a href="login.php" class="btn btn-secondary w-100 py-3">Back</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
