<?php
include 'db_connect.php';

if (isset($_POST['bulk_action']) && isset($_POST['selected_enrollments'])) {
    $bulk_action = $_POST['bulk_action'];
    $selected_enrollments = $_POST['selected_enrollments'];

    if ($bulk_action == 'assign_instructor') {
        // Assign instructor
        $bulk_instructor_id = $_POST['bulk_instructor_id'];
        if (!empty($bulk_instructor_id)) {
            foreach ($selected_enrollments as $enrollment_id) {
                $update_query = "UPDATE appointments SET instructor_id = '$bulk_instructor_id' WHERE id = '$enrollment_id'";
                $conn->query($update_query);
            }
            echo "<script>alert('Instructor assigned to selected enrollments'); window.location.href='manage_enrollments.php';</script>";
        }
    } elseif ($bulk_action == 'mark_in_session') {
        // Mark as In-Session
        foreach ($selected_enrollments as $enrollment_id) {
            $update_query = "UPDATE appointments SET status = 'In-Session' WHERE id = '$enrollment_id'";
            $conn->query($update_query);
        }
        echo "<script>alert('Selected enrollments marked as In-Session'); window.location.href='manage_enrollments.php';</script>";
    } elseif ($bulk_action == 'cancel_enrollments') {
        // Cancel enrollments
        foreach ($selected_enrollments as $enrollment_id) {
            $update_query = "UPDATE appointments SET status = 'Cancelled' WHERE id = '$enrollment_id'";
            $conn->query($update_query);
        }
        echo "<script>alert('Selected enrollments cancelled'); window.location.href='manage_enrollments.php';</script>";
    }
} else {
    echo "<script>alert('No enrollments selected or action specified'); window.location.href='manage_enrollments.php';</script>";
}
?>
