<?php
session_start();
session_destroy(); // Destroy the session
header("Location: instructor_login.php"); // Redirect to the instructor login page after logout
exit();
?>
