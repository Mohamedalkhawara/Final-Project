<?php
// Include the database connection
include 'db_connect.php';

$id = $_GET['id'];

// Delete instructor record
$query = "DELETE FROM instructors WHERE id = $id";
if (mysqli_query($conn, $query)) {
    echo "Instructor deleted successfully";
} else {
    echo "Error: " . mysqli_error($conn);
}
header("Location: manage_instructors.php");
?>
