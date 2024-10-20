<?php
include 'db_connect.php';

$id = $_GET['id'];

$sql = "DELETE FROM appointments WHERE id='$id'";
if ($conn->query($sql) === TRUE) {
    echo "<script>
        alert('Enrollee deleted successfully');
        window.location.href='manage_enrollments.php';
    </script>";
} else {
    echo "Error deleting record: " . $conn->error;
}

$conn->close();
?>
