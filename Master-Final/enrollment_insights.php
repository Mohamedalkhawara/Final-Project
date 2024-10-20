<?php 
session_start();
include 'db_connect.php';

// Fetch total students per instructor
$instructor_insight_query = "
    SELECT instructors.name AS instructor_name, COUNT(appointments.id) AS student_count
    FROM instructors
    LEFT JOIN appointments ON instructors.id = appointments.instructor_id
    GROUP BY instructors.name
";
$instructor_insight_result = $conn->query($instructor_insight_query);

// Fetch breakdown of enrollees by package type
$package_insight_query = "
    SELECT training_package, COUNT(id) AS package_count 
    FROM appointments 
    GROUP BY training_package
";
$package_insight_result = $conn->query($package_insight_query);

?>

<?php include 'header.php'; ?>

<div class="container my-5">
    <h1>Enrollment Insights</h1>
    <a href="admin_dashboard.php" class="btn btn-secondary mt-4">Back to Dashboard</a>
<br>
<br>

    <div class="row">
        <div class="col-md-6">
            <h3>Total Students per Instructor</h3>
            
            <div class="row">
                <?php
                if ($instructor_insight_result->num_rows > 0) {
                    while ($row = $instructor_insight_result->fetch_assoc()) {
                        echo "
                        <div class='col-md-6'>
                            <div class='card mb-3'>
                                <div class='card-body'>
                                    <h5 class='card-title'>{$row['instructor_name']}</h5>
                                    <p class='card-text'>Total Students: {$row['student_count']}</p>
                                </div>
                            </div>
                        </div>
                        ";
                    }
                } else {
                    echo "<p>No instructors found.</p>";
                }
                ?>
            </div>
        </div>

        <div class="col-md-6">
            <h3>Breakdown by Package Type</h3>
            <div class="row">
                <?php
                if ($package_insight_result->num_rows > 0) {
                    while ($row = $package_insight_result->fetch_assoc()) {
                        echo "
                        <div class='col-md-6'>
                            <div class='card mb-3'>
                                <div class='card-body'>
                                    <h5 class='card-title'>{$row['training_package']}</h5>
                                    <p class='card-text'>Total Enrollees: {$row['package_count']}</p>
                                </div>
                            </div>
                        </div>
                        ";
                    }
                } else {
                    echo "<p>No packages found.</p>";
                }
                ?>
            </div>
        </div>
    </div>

</div>

<?php include 'footer.php'; ?>
