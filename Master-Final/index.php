<?php
session_start(); // Start the session for login check
include('db_connect.php');

$appointment_link = 'appointment.php'; 

// Check if user is logged in by verifying if user_id is set in the session
if (isset($_SESSION['user_id'])) {
    // If logged in, fetch user information
    $user_id = $_SESSION['user_id'];
    $user_query = "SELECT name, image FROM users WHERE id = '$user_id'";
    $user_result = mysqli_query($conn, $user_query);
    $user = mysqli_fetch_assoc($user_result);
} else {
    // If not logged in, set default values or leave empty
    $user = [
        'name' => 'Guest',
        'image' => 'default.png' // Set a default image for guests
    ];
}

/// Handle Testimonial Submission
if (isset($_POST['submit_testimonial'])) {
    // Only allow testimonial submission if the user is logged in
    if (isset($_SESSION['user_id'])) {
        $testimonial = mysqli_real_escape_string($conn, $_POST['message']); 
        
        // Insert the testimonial into the database with 'is_approved' status initially set to 0 (pending)
        $sql = "INSERT INTO testimonials (user_id, testimonial, status) VALUES ('$user_id', '$testimonial', 'Pending')";
        
        if (mysqli_query($conn, $sql)) {
            $success_message = "Testimonial submitted successfully! Waiting for admin approval.";
        } else {
            $error_message = "Error submitting testimonial. Please try again later.";
        }
    } else {
        echo "<script>alert('You must be logged in to submit a testimonial.');</script>";
    }
}
// Fetch test parts from the database
$test_parts_query = "SELECT * FROM test_parts";
$test_parts_result = mysqli_query($conn, $test_parts_query);

?>

<!-- Topbar Start -->
<?php include 'header.php'; ?>
<!-- Topbar End -->
<!-- Navbar Start -->
<?php include 'navbar.php'; ?>
<!-- Navbar End -->

<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary" role="status"></div>
    </div>
    <!-- Spinner End -->

   <style1>
    
   </style>
    
    <!-- Carousel Start -->
    <div class="container-fluid p-0 wow fadeIn" data-wow-delay="0.1s">
        <div id="header-carousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img class="w-100" src="img/carousel-1.jpg" alt="Image">
                    <div class="carousel-caption">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-lg-7">
                                    <h1 class="display-2 text-light mb-5 animated slideInDown">Learn To Drive With Confidence</h1>
                                    <!-- If user is logged in, show the appointment link, else redirect to login -->
                                    <a href="<?php echo $appointment_link; ?>" class="btn btn-primary py-sm-3 px-sm-5">Appointment</a>
                                    <a href="#" class="btn btn-light py-sm-3 px-sm-5 ms-3">Our Courses</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img class="w-100" src="img/carousel-2.jpg" alt="Image">
                    <div class="carousel-caption">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-lg-7">
                                    <h1 class="display-2 text-light mb-5 animated slideInDown">Safe Driving Is Our Top Priority</h1>
                                    <!-- If user is logged in, show the appointment link, else redirect to login -->
                                    <a href="<?php echo $appointment_link; ?>" class="btn btn-primary py-sm-3 px-sm-5">Appointment</a>
                                    <a href="#" class="btn btn-light py-sm-3 px-sm-5 ms-3">Our Courses</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#header-carousel"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#header-carousel"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
    <!-- Carousel End -->

    <!-- Facts Start -->
    <div class="container-fluid facts py-5 pt-lg-0">
        <div class="container py-5 pt-lg-0">
            <div class="row gx-0">
                <div class="col-lg-4 wow fadeIn" data-wow-delay="0.1s">
                    <div class="bg-white shadow d-flex align-items-center h-100 p-4" style="min-height: 150px;">
                        <div class="d-flex">
                            <div class="flex-shrink-0 btn-lg-square bg-primary">
                                <i class="fa fa-car text-white"></i>
                            </div>
                            <div class="ps-4">
                                <h5>Easy Driving Learn</h5>
                                <span>Learn to drive easily with our simple courses.</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 wow fadeIn" data-wow-delay="0.3s">
                    <div class="bg-white shadow d-flex align-items-center h-100 p-4" style="min-height: 150px;">
                        <div class="d-flex">
                            <div class="flex-shrink-0 btn-lg-square bg-primary">
                                <i class="fa fa-users text-white"></i>
                            </div>
                            <div class="ps-4">
                                <h5>National Instructor</h5>
                                <span>Certified instructors to help you pass with confidence.</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 wow fadeIn" data-wow-delay="0.5s">
                    <div class="bg-white shadow d-flex align-items-center h-100 p-4" style="min-height: 150px;">
                        <div class="d-flex">
                            <div class="flex-shrink-0 btn-lg-square bg-primary">
                                <i class="fa fa-file-alt text-white"></i>
                            </div>
                            <div class="ps-4">
                                <h5>Get licence</h5>
                                <span>uiding you to get your license on the first try.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Facts End -->

    <!-- About Start -->
    <div class="container-xxl py-6" id= "about-us">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="position-relative overflow-hidden ps-5 pt-5 h-100" style="min-height: 400px;">
                        <img class="position-absolute w-100 h-100" src="img/about-1.jpg" alt="" style="object-fit: cover;">
                        <img class="position-absolute top-0 start-0 bg-white pe-3 pb-3" src="img/about-2.jpg" alt="" style="width: 200px; height: 200px;">
                    </div>
                </div>
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="h-100">
                        <h6 class="text-primary text-uppercase mb-2">About Us</h6>
                        <h1 class="display-6 mb-4">We Help Students To Pass Test & Get A License On The First Try</h1>
                        <p>Master the road with our expert guidance. Our proven methods and dedicated instructors ensure you pass your test with confidence, making your journey to getting a license smooth and straightforward.

Learn from the best in the business, with training tailored to help you succeed on the first attempt. Join us and drive towards a safer, more confident future.</p>
                        <div class="row g-2 mb-4 pb-2">
                            <div class="col-sm-6">
                                <i class="fa fa-check text-primary me-2"></i>Fully Licensed
                            </div>
                            <div class="col-sm-6">
                                <i class="fa fa-check text-primary me-2"></i>Online Tracking
                            </div>
                            <div class="col-sm-6">
                                <i class="fa fa-check text-primary me-2"></i>Afordable Fee 
                            </div>
                            <div class="col-sm-6">
                                <i class="fa fa-check text-primary me-2"></i>Best Trainers
                            </div>
                        </div>
                        <div class="row g-4">
                            <div class="col-sm-6">
                                <a class="btn btn-primary py-3 px-5" href="#">Read More</a>
                            </div>
                            <div class="col-sm-6">
                                <a class="d-inline-flex align-items-center btn btn-outline-primary border-2 p-2" href="tel:+0123456789">
                                    <span class="flex-shrink-0 btn-square bg-primary">
                                        <i class="fa fa-phone-alt text-white"></i>
                                    </span>
                                    <span class="px-3">+962 7855 32135</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->

  <!-- Courses Start -->
<div class="container-xxl courses my-6 py-6 pb-0">
    <div class="container">
        <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 500px;">
            <h6 class="text-primary text-uppercase mb-2">Trending Courses</h6>
            <h1 class="display-6 mb-4">Our Courses Upskill You With Driving Training</h1>
        </div>
        <div class="row g-4 justify-content-center">
            <?php while ($test_part = mysqli_fetch_assoc($test_parts_result)) { ?>
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="courses-item d-flex flex-column bg-white overflow-hidden h-100">
                        <div class="text-center p-4 pt-0">
                            <div class="d-inline-block bg-primary text-white fs-5 py-1 px-4 mb-4">$99</div>
                            <h5 class="mb-3"><?php echo $test_part['part_name']; ?></h5>
                            <p><?php echo $test_part['description']; ?></p>
                            <ol class="breadcrumb justify-content-center mb-0">
                                <li class="breadcrumb-item small"><i class="fa fa-signal text-primary me-2"></i>Beginner</li>
                                <li class="breadcrumb-item small"><i class="fa fa-calendar-alt text-primary me-2"></i>3 Week</li>
                            </ol>
                        </div>
                        <div class="position-relative mt-auto">
                            <!-- Dynamic image path based on database -->
                            <img class="img-fluid" src="uploads/test_parts/<?php echo $test_part['image_path']; ?>" alt="Test Part Image">
                            <div class="courses-overlay">
                                <a class="btn btn-outline-primary border-2" href="test_start.php?part=<?php echo $test_part['id']; ?>">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<!-- Courses End -->




    <!-- Features Start -->
    <div class="container-xxl py-6">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                    <h6 class="text-primary text-uppercase mb-2">Why Choose Us!</h6>
                    <h1 class="display-6 mb-4">Best Driving Training Agency In Your City</h1>
                    <p class="mb-5">Experience top-tier driving education. Our fully licensed program, real-time online tracking, and the best trainers ensure your success on the road. Affordable and reliable, we’re your go-to choice for driving training in the city.</p>
                    <div class="row gy-5 gx-4">
                        <div class="col-sm-6 wow fadeIn" data-wow-delay="0.1s">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0 btn-square bg-primary me-3">
                                    <i class="fa fa-check text-white"></i>
                                </div>
                                <h5 class="mb-0">Fully Licensed</h5>
                            </div>
                            <span>Trusted and fully licensed driving school.</span>
                        </div>
                        <div class="col-sm-6 wow fadeIn" data-wow-delay="0.2s">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0 btn-square bg-primary me-3">
                                    <i class="fa fa-check text-white"></i>
                                </div>
                                <h5 class="mb-0">Online Tracking</h5>
                            </div>
                            <span>Track your progress online, anytime.</span>
                        </div>
                        <div class="col-sm-6 wow fadeIn" data-wow-delay="0.3s">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0 btn-square bg-primary me-3">
                                    <i class="fa fa-check text-white"></i>
                                </div>
                                <h5 class="mb-0">Afordable Fee</h5>
                            </div>
                            <span>Quality training at an affordable price.</span>
                        </div>
                        <div class="col-sm-6 wow fadeIn" data-wow-delay="0.4s">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0 btn-square bg-primary me-3">
                                    <i class="fa fa-check text-white"></i>
                                </div>
                                <h5 class="mb-0">Best Trainers</h5>
                            </div>
                            <span>Learn from the best, certified instructors.</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="position-relative overflow-hidden pe-5 pt-5 h-100" style="min-height: 400px;">
                        <img class="position-absolute w-100 h-100" src="img/about-1.jpg" alt="" style="object-fit: cover;">
                        <img class="position-absolute top-0 end-0 bg-white ps-3 pb-3" src="img/about-2.jpg" alt="" style="width: 200px; height: 200px">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Features End -->

    <?php
// Include the database connection
include 'db_connect.php';

// Fetch instructors from the database (limit to 4)
$query = "SELECT name, image, email, phone, experience_years FROM instructors LIMIT 4";
$result = mysqli_query($conn, $query);
?>

<!-- Team Section -->
<div class="container-xxl py-6">
    <div class="container">
        <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 500px;">
            <h6 class="text-primary text-uppercase mb-2">Meet The Team</h6>
            <h1 class="display-6 mb-4">We Have Great Experience Of Driving</h1>
        </div>
        <div class="row g-4 team-items">
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                <div class="team-item position-relative d-flex flex-column" style="height: 100%;">
                    <div class="position-relative">
                        <!-- Display the instructor image -->
                        <img class="img-fluid" src="uploads/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>" style="height: 300px; width: 100%; object-fit: cover;">
                        <div class="team-social text-center">
                            <a class="btn btn-square btn-outline-primary border-2 m-1" href="#"><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-square btn-outline-primary border-2 m-1" href="#"><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-square btn-outline-primary border-2 m-1" href="#"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                    <div class="bg-light text-center p-4 flex-grow-1 d-flex flex-column justify-content-between">
                        <!-- Display the instructor name and role (trainer) -->
                        <div>
                            <h5 class="mt-2"><?php echo $row['name']; ?></h5>
                            <span>Trainer</span>
                            <p>Email: <?php echo $row['email']; ?></p>
                            <p>Phone: <?php echo $row['phone']; ?></p>
                            <p>Experience: <?php echo $row['experience_years']; ?> years</p>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
<!-- End of Team Section -->

<!-- Testimonial Display Section Start -->
<div class="container-xxl py-6">
    <div class="container">
        <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 500px;">
            <h6 class="text-primary text-uppercase mb-2">Testimonial</h6>
            <h1 class="display-6 mb-4">What Our Clients Say!</h1>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8 wow fadeInUp" data-wow-delay="0.1s">
                <div class="owl-carousel testimonial-carousel">
                    <?php
                    // Fetch approved testimonials and corresponding user information
                    $testimonial_query = "SELECT t.testimonial, u.name, u.image FROM testimonials t 
                                          JOIN users u ON t.user_id = u.id 
                                          WHERE t.status = 'Approved'";
                    $testimonial_result = mysqli_query($conn, $testimonial_query);

                    while ($testimonial = mysqli_fetch_assoc($testimonial_result)) {
                    ?>
                    <div class="testimonial-item text-center">
                        <div class="position-relative mb-5">
                            <!-- Display the user's image fetched from the 'users' table -->
                            <img class="img-fluid rounded-circle mx-auto" src="<?php echo $testimonial['image']; ?>" alt="<?php echo $testimonial['name']; ?>" style="width: 100px; height: 100px;">
                            <div class="position-absolute top-100 start-50 translate-middle d-flex align-items-center justify-content-center bg-white rounded-circle" style="width: 60px; height: 60px;">
                                <i class="fa fa-quote-left fa-2x text-primary"></i>
                            </div>
                        </div>
                        <p class="fs-4"><?php echo $testimonial['testimonial']; ?></p> <!-- Display the testimonial message -->
                        <hr class="w-25 mx-auto">
                        <h5><?php echo $testimonial['name']; ?></h5> <!-- Display the user's name -->
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Testimonial Display Section End -->

 <!-- Testimonial Submission Section Start -->
 <div class="container-xxl py-6">
        <div class="container">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 500px;">
                <h6 class="text-primary text-uppercase mb-2">Add Testimonial</h6>
                <h1 class="display-6 mb-4">Share Your Experience With Us!</h1>
            </div>

            <!-- Display Success or Error Messages -->
            <?php if (!empty($success_message)) { ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php } ?>
            <?php if (!empty($error_message)) { ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php } ?>

            <!-- Testimonial Form -->
            <form method="POST" action="">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="form-floating">
                            <textarea class="form-control" placeholder="Leave a message here" id="message" name="message" style="height: 150px;" required></textarea>
                            <label for="message">Your Testimonial</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <button type="submit" name="submit_testimonial" class="btn btn-primary py-3 px-5">Submit Testimonial</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Testimonial Submission Section End -->








   


    <!-- Footer Start -->
    <?php include 'footer.php'; ?>
    <!-- Footer End -->

    

   
