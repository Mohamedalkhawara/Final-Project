<?php
session_start();
include 'db_connect.php';

$login_success_message = '';
$login_error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare the statement to avoid SQL injection
    $stmt = $conn->prepare("SELECT id, password, role_id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id, $hashed_password, $role_id);

    if ($stmt->num_rows == 1) {
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['email'] = $email;
            $_SESSION['role_id'] = $role_id;

            // Redirect based on role_id
            if ($role_id == 1) {
                header("Location: admin_dashboard.php");
            } elseif ($role_id == 2) {
                header("Location: instructor_dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit;
        } else {
            $login_error_message = 'Incorrect password.';
        }
    } else {
        $login_error_message = 'No account found with that email.';
    }

    $stmt->close();
}

$conn->close();

include 'header.php';
include 'navbar.php';
?>

<!-- Login HTML Code -->
<div class="container-xxl py-6">
    <div class="container">
        <div class="row g-5 justify-content-center">
            <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                <div class="bg-light p-5 text-center">
                    <h1 class="mb-4">Login</h1>
                    <!-- Display success or error message -->
                    <?php if (!empty($login_success_message)) { ?>
                        <div class="alert alert-success">
                            <?php echo $login_success_message; ?>
                        </div>
                    <?php } ?>
                    <?php if (!empty($login_error_message)) { ?>
                        <div class="alert alert-danger">
                            <?php echo $login_error_message; ?>
                        </div>
                    <?php } ?>
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
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
                            <div class="col-12">
                                <p class="mb-0">Don't have an account? <a href="signup.php">Sign up</a></p>
                            </div>
                        </div>
                    </form>

                    <!-- New button for instructor login -->
                    <div class="mt-4">
                        <a href="instructor_login.php" class="btn btn-secondary w-100 py-3">Instructor Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
