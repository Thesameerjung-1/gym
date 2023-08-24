<?php
if (isset($_POST['enroll'])) {
    // Perform the subscription process and insert data into the database
    $user_id = 123; // Replace with actual user ID
    $membership_type = "Private Martial Arts Tuition"; // Replace with selected membership
    $query = "INSERT INTO subscriptions (user_id, membership_type) VALUES ('$user_id', '$membership_type')";
    // Execute the query
    // Redirect or show a success message
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<section id="special-courses" class="section__container price__container">
  <h2 class="section__header">SPECIAL COURSES</h2>
  <div class="price__grid">
    <div class="price__card">
      <div class="price__card__content">
        <h4>Private Martial Arts Tuition</h4>
        <h3>£15.00 / hour</h3>
        <p>Personalized one-on-one martial arts training.</p>
      </div>
      <form method="post">
          <button class="btn price__btn" name="enroll">Enroll Now</button>
      </form>
    </div>
    <div class="price__card">
      <div class="price__card__content">
        <h4>Junior Membership</h4>
        <h3>£25.00</h3>
        <p>Access to all kids' martial arts sessions.</p>
      </div>
      <form method="post">
          <button class="btn price__btn" name="enroll">Enroll Now</button>
      </form>
    </div>
    <div class="price__card">
      <div class="price__card__content">
        <h4>Beginners’ Self-Defense Course</h4>
        <h3>£180.00</h3>
        <p>Six-week course, 2 sessions per week.</p>
      </div>
      <form method="post">
          <button class="btn price__btn" name="enroll">Enroll Now</button>
      </form>
    </div>
    <div class="price__card">
      <div class="price__card__content">
        <h4>Use of Fitness Room</h4>
        <h3>£6.00 / visit</h3>
        <p>Access to the fitness room per visit.</p>
      </div>
      <form method="post">
          <button class="btn price__btn" name="enroll">Enroll Now</button>
      </form>
    </div>
    <div class="price__card">
      <div class="price__card__content">
        <h4>Personal Fitness Training</h4>
        <h3>£35.00 / hour</h3>
        <p>One-on-one fitness training with a personal trainer.</p>
      </div>
      <form method="post">
          <button class="btn price__btn" name="enroll">Enroll Now</button>
      </form>
    </div>
  </div>
</section>

</body>
</html>
