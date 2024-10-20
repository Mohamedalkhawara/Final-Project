<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'dsms');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'];
$status = $_GET['status'];

// Basic query to check functionality
$sql = "UPDATE appointments SET status = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('si', $status, $id);

if ($stmt->execute()) {
    echo "<script>
        alert('Status updated successfully');
        window.location.href = 'admin_dashboard.php';
    </script>";
} else {
    echo "<script>
        alert('Error updating status. Please try again.');
        window.location.href = 'admin_dashboard.php';
    </script>";
}

$stmt->close();
$conn->close();
?>
