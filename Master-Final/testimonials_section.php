<?php
// Fetch approved testimonials and the corresponding user's image and name
$testimonial_query = "SELECT t.testimonial, u.name, u.image FROM testimonials t 
                      JOIN users u ON t.user_id = u.id 
                      WHERE t.status = 'Approved'";
$testimonial_result = mysqli_query($conn, $testimonial_query);
?>

<div class="container-xxl py-6">
    <div class="container">
        <h2 class="text-center mb-4">What Our Clients Say!</h2>
        <div class="row">
            <?php while ($row = mysqli_fetch_assoc($testimonial_result)) { ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="testimonial-item">
                    <img class="img-fluid rounded-circle mb-3" src="uploads/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>" style="width: 100px; height: 100px;">
                    <h5><?php echo $row['name']; ?></h5>
                    <p><?php echo $row['testimonial']; ?></p>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
