// reject_testimonial.php
<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $delete_query = "DELETE FROM testimonials WHERE id = '$id'";
    if (mysqli_query($conn, $delete_query)) {
        echo "<script>alert('Testimonial rejected successfully!'); window.location.href='manage_testimonials.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
