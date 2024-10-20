// approve_testimonial.php
<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $update_query = "UPDATE testimonials SET status = 'Approved' WHERE id = '$id'";
    if (mysqli_query($conn, $update_query)) {
        echo "<script>alert('Testimonial approved successfully!'); window.location.href='manage_testimonials.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
