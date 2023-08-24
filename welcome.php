<?php
session_start();
require_once "config.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

$userMembership = ''; // Initialize the variable
$startingDate = '';
$expiryDate = '';
$daysLeft = 0;

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // Retrieve the user's membership status from the database
    $membershipQuery = "SELECT membership FROM users WHERE username = '$username'";
    $membershipResult = mysqli_query($conn, $membershipQuery);

    if ($membershipResult && mysqli_num_rows($membershipResult) > 0) {
        $row = mysqli_fetch_assoc($membershipResult);
        $userMembership = $row['membership'];

        // Fetch user's membership information from the database
        $userInfoQuery = "SELECT membership, starting_date, expiry_date FROM users WHERE username = '$username'";
        $userInfoResult = mysqli_query($conn, $userInfoQuery);

        if ($userInfoResult && mysqli_num_rows($userInfoResult) > 0) {
            $userInfo = mysqli_fetch_assoc($userInfoResult);
            $userMembership = $userInfo['membership'];
            $startingDate = $userInfo['starting_date'];
            $expiryDate = $userInfo['expiry_date'];

            
            
            if ($userMembership === 'Basic') {
              // Fetch user's membership information from the database
              $userInfoQuery = "SELECT membership, starting_date, expiry_date FROM users WHERE username = '$username'";
              $userInfoResult = mysqli_query($conn, $userInfoQuery);
          
              if ($userInfoResult && mysqli_num_rows($userInfoResult) > 0) {
                  $userInfo = mysqli_fetch_assoc($userInfoResult);
                  $userMembership = $userInfo['membership'];
                  $startingDate = $userInfo['starting_date'];
                  $expiryDate = $userInfo['expiry_date'];
          
                  // Calculate the number of days left until the membership plan expires
                  if ($expiryDate !== '9999-12-31') {
                      $currentDate = date("Y-m-d");
                      if (!empty($expiryDate) && strtotime($expiryDate) !== false) {
                          $daysLeft = max(0, (strtotime($expiryDate) - strtotime($currentDate)) / (60 * 60 * 24));
                      } else {
                          // Handle invalid or empty expiry date
                          $daysLeft = 0;
                      }
                  }
              }
          }
        }
      }
    }          
else {
    // If not logged in, continue as a guest (no need to redirect)
    // You can set default values or guest-specific behavior here
    // For example, set $userMembership = 'Guest' and $expiryDate = '9999-12-31'
    $userMembership = 'Guest';
    $expiryDate = '9999-12-31';
}


?>



<?php
// Fetch user's membership information from the database
if (isset($_SESSION['username'])) {
    $userInfoQuery = "SELECT membership, starting_date, expiry_date FROM users WHERE username = '$username'";
    $userInfoResult = mysqli_query($conn, $userInfoQuery);

    if ($userInfoResult && mysqli_num_rows($userInfoResult) > 0) {
        $userInfo = mysqli_fetch_assoc($userInfoResult);
        $userMembership = $userInfo['membership'];
        $startingDate = $userInfo['starting_date'];
        $expiryDate = $userInfo['expiry_date'];

        if ($expiryDate !== '9999-12-31') {
          $currentDate = date("Y-m-d");
      
          // Check if the expiry date is not empty and can be converted to a valid timestamp
          if (!empty($expiryDate) && strtotime($expiryDate) !== false) {
              $daysLeft = max(0, (strtotime($expiryDate) - strtotime($currentDate)) / (60 * 60 * 24));
          } else {
              // Handle invalid or empty expiry date
              $daysLeft = 0;
          }
      }
      
      
 else {
  $daysLeft = PHP_INT_MAX; // Set to a very large value for "9999-12-31"
}
    }
}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      href="https://cdn.jsdelivr.net/npm/remixicon@3.4.0/fonts/remixicon.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="styles.css" />
    <title>Gorkha Martial Arts</title>
  </head>
  <body>
    <nav>
      <div class="nav__logo">
        <a href="#"><img src="assets/logo1.png" alt="logo" /></a>
      </div>
      <ul class="nav__links">
        <li class="link"><a href="#home">Home</a></li>
        <li class="link"><a href="#program">Program</a></li>
        <li class="link"><a href="#service">Service</a></li>
        <li class="link"><a href="#about">About</a></li>
        <li class="link"><a href="#payment">Payment</a></li>
        <li class="link"><a href="#trainer">Trainers</a></li>


      </ul>

      <div class="nav__user">
<?php if (isset($_SESSION['username'])) { ?>
    <div class="user-dropdown">
        <button class="btn user-btn"><?php echo $_SESSION['username']; ?></button>
        <ul class="user-dropdown-content">
            <li><a href="profile.php">Profile</a></li>
            <li><a href="setting.php">Change Password</a></li>
            <?php if ($_SESSION['role'] === 'admin') { ?>
                <li><a href="admin_dashboard.php">Admin Dashboard</a></li>
            <?php } ?>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
<?php } else { ?>
    <div class="user-dropdown">
        <a href="login.php" class="btn user-btn">Login</a>
    </div>
<?php } ?>
</div>


    </nav>

    <header class="section__container header__container">
      <div class="header__content">
        <span class="bg__blur"></span>
        <span class="bg__blur header__blur"></span>
        <h4>BEST TRAINING IN THE TOWN</h4>
        <h1><span> Unleash </span><br>Your Power</h1>
        <p>
          Unleash your potential and embark on a journey towards a stronger,
          fitter, and more confident you. Sign up for 'Make Your Body Shape' now
          and witness the incredible transformation your body is capable of!
        </p>

        <!-- Button -->
        <?php if (!isset($_SESSION['username'])) { ?>
      <a href="registration.php" class="btn btn-register">Register Now</a>
    <?php } else { ?>

      <a href="special_class.php" class="btn btn-special-class">Special Courses</a>
    <?php } ?>

      </div>
      <div class="header__image">
        <img src="assets/header1.png" alt="header" />
      </div>
    </header>


    <section class="section__container explore__container" id="program">
      <div class="explore__header">
        <h2 class="section__header">EXPLORE OUR PROGRAM</h2>
      </div>
      <div class="explore__grid">
        <div class="explore__card">
          <span><i class="ri-boxing-fill"></i></span>
          <h4>Strength</h4>
          <p>
            Embrace the essence of strength as we delve into its various
            dimensions physical, mental, and emotional.
          </p>
          <a href="#">Join Now <i class="ri-arrow-right-line"></i></a>
        </div>
        <div class="explore__card">
          <span><i class="ri-heart-pulse-fill"></i></span>
          <h4>Physical Fitness</h4>
          <p>
            It encompasses a range of activities that improve health, strength,
            flexibility, and overall well-being.
          </p>
          <a href="#">Join Now <i class="ri-arrow-right-line"></i></a>
        </div>
        <div class="explore__card">
          <span><i class="ri-run-line"></i></span>
          <h4>Fat Lose</h4>
          <p>
            Through a combination of workout routines and expert guidance, we'll
            empower you to reach your goals.
          </p>
          <a href="#">Join Now <i class="ri-arrow-right-line"></i></a>
        </div>
        <div class="explore__card">
          <span><i class="ri-shopping-basket-fill"></i></span>
          <h4>Weight Gain</h4>
          <p>
            Designed for individuals, our program offers an effective approach
            to gaining weight in a sustainable manner.
          </p>
          <a href="#">Join Now <i class="ri-arrow-right-line"></i></a>
        </div>

        <div class="explore__card">
          <span><img src="assets\karate.png" alt="" height="46px" width="45.78px" ></i></span>
          <h4>Karate</h4>
          <p>
          A traditional Japanese martial art that focuses on striking techniques, 
          including punches, kicks, knee strikes, and elbow strikes.
          </p>
          <a href="#">Join Now <i class="ri-arrow-right-line"></i></a>
        </div>

        <div class="explore__card">
          <span><img src="assets\jui-jitsu.png" alt="" height="46px" width="45.78px" ></i></span>
          <h4>Jui-Jitsu</h4>
          <p>
          A ground-based martial art that focuses on grappling and submission techniques. 
          It's effective for self-defense and sport competition.
          </p>
          <a href="#">Join Now <i class="ri-arrow-right-line"></i></a>
        </div>

        <div class="explore__card">
          <span><img src="assets\muay-thai.png" alt="" height="46px" width="45.78px" ></span>
          <h4>Muay-Thai</h4>
          <p>
          A Thai martial art that is also known as the "Art of Eight Limbs." 
          It involves striking with fists, elbows, knees, and shins.
          </p>
          <a href="#">Join Now <i class="ri-arrow-right-line"></i></a>
        </div>

        <div class="explore__card">
          <span><img src="assets\taekwondo.png" alt="" height="46px" width="45.78px" ></i></span>
          <h4>Taekwondo</h4>
          <p>
          A Korean martial art known for its dynamic kicks and fast-paced techniques. 
          It also emphasizes forms (patterns), sparring, and self-defense.
          </p>
          <a href="#">Join Now <i class="ri-arrow-right-line"></i></a>
        </div>
      </div>
      </div>
    </section>

    <section class="section__container class__container" id="service">
      <div class="class__image">
        <span class="bg__blur"></span>
        <img src="assets\img-1.jpg" alt="class" class="class__img-1" />
        <img src="assets\img-2.jpg" alt="class" class="class__img-2" />
      </div>
      <div class="class__content" >
        <h2 class="section__header">Unleash Your Potential</h2>
        <p>
        Guided by our team of seasoned martial arts masters, "Unleash Your Potential" presents invigorating sessions that fuse
         traditional discipline with modern techniques.
         Embrace a holistic training experience, harmonizing striking, defense, and agility.
         Crafted to elevate your skills continuously, each class fuels growth, ensuring your martial journey knows no limits.
        </p>
        <button id="showTimetableButton" class="btn" onclick="toggleTimetable()">Show Timetable</button>
      </div>
    </section>
    <!-- <div id="timetableContainer" class="container">
  <h1>Martial Arts Class Timetable</h1>
  <table>
    <tr>
      <th>Time</th>
      <th>Monday</th>
      <th>Tuesday</th>
      <th>Wednesday</th>
      <th>Thursday</th>
      <th>Friday</th>
      <th>Saturday</th>
      <th>Sunday</th>
    </tr>
    <tr>
      <td>06:00–07:30</td>
      <td>Jiu-jitsu</td>
      <td>Karate</td>
      <td>Judo</td>
      <td>Jiu-jitsu</td>
      <td>Muay Thai</td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td>08:00–10:00</td>
      <td>Muay Thai</td>
      <td>Private tuition</td>
      <td>Private tuition</td>
      <td>Private tuition</td>
      <td>Jiu-jitsu</td>
      <td>Private tuition</td>
      <td>Private tuition</td>
    </tr>
    <tr>
      <td>10:30–12:00</td>
      <td>Private tuition</td>
      <td>Private tuition</td>
      <td>Private tuition</td>
      <td>Private tuition</td>
      <td>Private tuition</td>
      <td>Judo</td>
      <td>Karate</td>
    </tr>
    <tr>
      <td>13:00–14:30</td>
      <td>Open mat/ personal practice</td>
      <td>Open mat/ personal practice</td>
      <td>Open mat/ personal practice</td>
      <td>Open mat/ personal practice</td>
      <td>Open mat/ personal practice</td>
      <td>Karate</td>
      <td>Judo</td>
    </tr>
    <tr>
      <td>15:00–17:00</td>
      <td>Kids Jiu-jitsu</td>
      <td>Kids Judo</td>
      <td>Kids Karate</td>
      <td>Kids Jiu-jitsu</td>
      <td>Kids Judo</td>
      <td>Muay Thai</td>
      <td>Jiu-jitsu</td>
    </tr>
    <tr>
      <td>17:30–19:00</td>
      <td>Karate</td>
      <td>Muay Thai</td>
      <td>Judo</td>
      <td>Jiu-jitsu</td>
      <td>Muay Thai</td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td>19:00–21:00</td>
      <td>Jiu-jitsu</td>
      <td>Judo</td>
      <td>Jiu-jitsu</td>
      <td>Karate</td>
      <td>Private tuition</td>
      <td></td>
      <td></td>
    </tr>
  </table>
</div> -->






    <section id="about" class="section__container join__container" >
      <h2 class="section__header">About Us</h2>
      <p class="section__subheader">
        Our diverse membership base creates a friendly and supportive
        atmosphere, where you can make friends and stay motivated.
      </p>
      <div class="join__image">
        <img src="assets/join.jpg" alt="Join" />
        <div class="join__grid">
          <div class="join__card">
            <span><i class="ri-user-star-fill"></i></span>
            <div class="join__card__content">
              <h4>Personal Trainer</h4>
              <p>Unlock your potential with our expert Personal Trainers.</p>
            </div>
          </div>
          <div class="join__card">
            <span><i class="ri-vidicon-fill"></i></span>
            <div class="join__card__content">
              <h4>Practice Sessions</h4>
              <p>Elevate your fitness with practice sessions.</p>
            </div>
          </div>
          <div class="join__card">
            <span><i class="ri-building-line"></i></span>
            <div class="join__card__content">
              <h4>Good Management</h4>
              <p>Supportive management, for your fitness success.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="payment" class="section__container price__container">
  <h2 class="section__header">OUR PRICING PLAN</h2>
  <p class="section__subheader">
    Our pricing plan comes with various membership tiers, each tailored to
    cater to different preferences and fitness aspirations.
  </p>

  <!-- Display subscription message if applicable -->
  <?php if (isset($subscriptionMessage)) { ?>
    <div class="subscription-message">
      <?php echo $subscriptionMessage; ?>
    </div>
  <?php } ?>

  <!-- Display subscription message if applicable -->
  <?php if (isset($subscriptionMessage)) { ?>
    <div class="subscription-message">
        <?php echo $subscriptionMessage; ?>
        <?php if (isset($daysLeft)) { ?>
            <?php if ($daysLeft > 0) { ?>
                <p><?php echo "Days Left to Expire: $daysLeft"; ?></p>
            <?php } else { ?>
                <p><?php echo "Your membership has expired."; ?></p>
            <?php } ?>
        <?php } ?>
    </div>
<?php } ?>

  <div class="price__grid">
    <!-- Basic Membership -->
    <div class="price__card">
      <div class="price__card__content">
        <h4>Basic</h4>
        <h3>$26</h3>
        <p>
          <i class="ri-checkbox-circle-line"></i>
          1 Martial Art
        </p>
        <p>
          <i class="ri-checkbox-circle-line"></i>
          2 Sessions Per Week
        </p>
      </div>
      <?php if (isset($_SESSION['username'])) { ?>
        <?php if ($userMembership === 'Basic') { ?>
          <p class="subscription-status">Already Took This Subscription</p>
        <?php } else if ($userMembership) { ?>
          <p class="subscription-status">You Have an Ongoing Plan</p>
        <?php } else { ?>
          <form method="post" action="profile.php">
            <input type="hidden" name="membership" value="Basic">
            <button type="submit" class="btn price__btn" name="subscribe">Join Now</button>
          </form>
        <?php } ?>
        <?php if ($userMembership === 'Basic') { ?>
          <p class="subscription-dates">
    <span class="green">Starting Date:</span> <?php echo $startingDate; ?><br>
    <span class="blue">Expiry Date:</span> <?php echo $expiryDate; ?>
    <?php if ($expiryDate !== '9999-12-31') { ?>
        <br>
        <?php if ($daysLeft > 0) { ?>
            <span class="expired">Days Left to Expire: <?php echo $daysLeft; ?></span>
        <?php } else { ?>
            <span class="expired">Your membership has expired.</span>
            <br>
            <a href="renew.php" class="renew-link">Renew Now</a>
        <?php } ?>
    <?php } ?>
</p>


<?php } ?>

      <?php } else { ?>
        <a href="login.php" class="btn price__btn">Login to Join</a>
      <?php } ?>
    </div>
    
    
    <!-- Intermediate -->
    <div class="price__card">
  <div class="price__card__content">
    <h4>Intermediate</h4>
    <h3>$35</h3>
    <p>
      <i class="ri-checkbox-circle-line"></i>
      1 Martial Art
    </p>
    <p>
      <i class="ri-checkbox-circle-line"></i>
      3 sessions per week
    </p>
  </div>
  <?php if (isset($_SESSION['username'])) { ?>
    <?php if ($userMembership === 'Intermediate') { ?>
      <p class="subscription-status">Already Took This Subscription</p>
    <?php } else if ($userMembership) { ?>
      <p class="subscription-status">You Have an Ongoing Plan</p>
    <?php } else { ?>
      <form method="post" action="profile.php">
        <input type="hidden" name="membership" value="Intermediate">
        <button type="submit" class="btn price__btn" name="subscribe">Join Now</button>
      </form>
    <?php } ?>
    <?php if ($userMembership === 'Intermediate') { ?>
      <p class="subscription-dates">
    <span class="green">Starting Date:</span> <?php echo $startingDate; ?><br>
    <span class="blue">Expiry Date:</span> <?php echo $expiryDate; ?>
    <?php if ($expiryDate !== '9999-12-31') { ?>
        <br>
        <?php if ($daysLeft > 0) { ?>
            <span class="expired">Days Left to Expire: <?php echo $daysLeft; ?></span>
        <?php } else { ?>
            <span class="expired">Your membership has expired.</span>
        <?php } ?>
    <?php } ?>

</p>
<?php } ?>
  <?php } else { ?>
    <a href="login.php" class="btn price__btn">Login to Join</a>
  <?php } ?>
</div>


    <!-- Advanced -->
    <div class="price__card">
  <div class="price__card__content">
    <h4>Advanced</h4>
    <h3>$45</h3>
    <p>
      <i class="ri-checkbox-circle-line"></i>
      Any 2 martial arts
    </p>
    <p>
      <i class="ri-checkbox-circle-line"></i>
      5 sessions per week
    </p>
  </div>
  <?php if (isset($_SESSION['username'])) { ?>
    <?php if ($userMembership === 'Advanced') { ?>
      <p class="subscription-status">Already Took This Subscription</p>
    <?php } else if ($userMembership) { ?>
      <p class="subscription-status">You Have an Ongoing Plan</p>
    <?php } else { ?>
      <form method="post" action="profile.php">
        <input type="hidden" name="membership" value="Advanced">
        <button type="submit" class="btn price__btn" name="subscribe">Join Now</button>
      </form>
    <?php } ?>
    <?php if ($userMembership === 'Advanced') { ?>
      <p class="subscription-dates">
    <span class="green">Starting Date:</span> <?php echo $startingDate; ?><br>
    <span class="blue">Expiry Date:</span> <?php echo $expiryDate; ?>
    <?php if ($expiryDate !== '9999-12-31') { ?>
        <br>
        <?php if ($daysLeft > 0) { ?>
            <span class="expired">Days Left to Expire: <?php echo $daysLeft; ?></span>
        <?php } else { ?>
            <span class="expired">Your membership has expired.</span>
        <?php } ?>
    <?php } ?>
</p>
<?php } ?>
  <?php } else { ?>
    <a href="login.php" class="btn price__btn">Login to Join</a>
  <?php } ?>
</div>


    <!-- Elite -->
    <div class="price__card">
  <div class="price__card__content">
    <h4>Elite</h4>
    <h3>$60</h3>
    <p>
      <i class="ri-checkbox-circle-line"></i>
      ELITE Gyms & Classes
    </p>
    <p>
      <i class="ri-checkbox-circle-line"></i>
      Unlimited Classes
    </p>
  </div>
  <?php if (isset($_SESSION['username'])) { ?>
    <?php if ($userMembership === 'Elite') { ?>
      <p class="subscription-status">Already Took This Subscription</p>
    <?php } else if ($userMembership) { ?>
      <p class="subscription-status">You Have an Ongoing Plan</p>
    <?php } else { ?>
      <form method="post" action="profile.php">
        <input type="hidden" name="membership" value="Elite">
        <button type="submit" class="btn price__btn" name="subscribe">Join Now</button>
      </form>
    <?php } ?>
    <?php if ($userMembership === 'Elite') { ?>
      <p class="subscription-dates">
    <span class="green">Starting Date:</span> <?php echo $startingDate; ?><br>
    <span class="blue">Expiry Date:</span> <?php echo $expiryDate; ?>
    <?php if ($expiryDate !== '9999-12-31') { ?>
        <br>
        <?php if ($daysLeft > 0) { ?>
            <span class="expired">Days Left to Expire: <?php echo $daysLeft; ?></span>
        <?php } else { ?>
            <span class="expired">Your membership has expired.</span>
        <?php } ?>
    <?php } ?>
</p>
<?php } ?>
  <?php } else { ?>
    <a href="login.php" class="btn price__btn">Login to Join</a>
  <?php } ?>
</div>
  </div>
</section>


    <section id="trainer" class="section__container instructor__container">
  <h2 class="section__header">Instructor Details</h2>
  <div class="instructor__grid">
    <!-- Instructor 1 -->
    <div class="instructor__card">
      <div class="instructor__card__image">
        <img src="assets/instructor.jpg" alt="Instructor 1">
        <div class="instructor__popup">
          <h1>Thomas Cook</h1>
         <h4> <p>Gym Owner/Head Martial Arts Coach</p> </h4>
          <p>
          <i class="ri-checkbox-circle-line"></i>
          Coaches in all martial arts
        </p>
          <p><i class="ri-checkbox-circle-line"></i>4th Dan Blackbelt judo</p>
          <p><i class="ri-checkbox-circle-line"></i>3rd Dan Blackbelt jiu-jitsu</p>
          <p><i class="ri-checkbox-circle-line"></i>1st Dan Blackbelt karate</p>
          <p><i class="ri-checkbox-circle-line"></i>Accredited Muay Thai coach</p>
        </div>
      </div>
    </div>

    <!-- Instructor 2 -->
    <div class="instructor__card">
      <div class="instructor__card__image">
        <img src="assets/instructor2.jpg" alt="Instructor 2" height="210">
        <div class="instructor__popup">
          <h1>Andrew Smith</h1>
         <h5> <p>Assistant Martial Arts Coach</p> </h5>
          <p><i class="ri-checkbox-circle-line"></i>5th Dan karate</p>
        </div>
      </div>
    </div>

    
    <!-- Instructor 3 -->
    <div class="instructor__card">
      <div class="instructor__card__image">
        <img src="assets/instructor3.jpg" alt="Instructor 3">
        <div class="instructor__popup">
          <h1>Powel Johnson</h1>
         <h5> <p>Assistant Martial Arts Coach</p> </h5>
          <p><i class="ri-checkbox-circle-line"></i>2nd Dan Blackbelt jiu-jitsu 1st Dan
B         lackbelt judo</p>
        </div>
      </div>
    </div>

    
    <!-- Instructor 4 -->
    <div class="instructor__card">
      <div class="instructor__card__image">
        <img src="assets/instructor4.jpg" alt="Instructor 4">
        <div class="instructor__popup">
          <h1>Harris William</h1>
          <h5><p>Assistant Martial Arts Coach</p></h5>
          <p><i class="ri-checkbox-circle-line"></i>Accredited Muay Thai coach</p>
          <p><i class="ri-checkbox-circle-line"></i>3rd Dan Blackbelt karate</p>
        </div>
      </div>
    </div>

    
  <!-- Instructor 5 -->
  <div class="instructor__card">
    <div class="instructor__card__image">
      <img src="assets/instructor5.jpg" alt="Instructor 5">
      <div class="instructor__popup">
        <h1>Joseph Anderson</h1>
        <h5><p>Fitness Coach</p></h5>
        <p><i class="ri-checkbox-circle-line"></i>BSc in Sports Science Qualified in health and nutrition</p>
        <p><i class="ri-checkbox-circle-line"></i>Specialises in 
          devising strength and conditioning
          programs for combat athletes</p>
      </div>
    </div>
  </div>

  <!-- Instructor 6 -->
  <div class="instructor__card">
    <div class="instructor__card__image">
      <img src="assets/instructor6.jpg" alt="Instructor 6">
      <div class="instructor__popup">
        <h1>Allen Murphy</h1>
        <h5><p>Fitness Coach</p></h5>
        <p><i class="ri-checkbox-circle-line"></i>BSc in Physiotherapy</p>
        <p><i class="ri-checkbox-circle-line"></i>MSc in Sports Science</p>
      </div>
    </div>
  </div>
  </div>
</section>


    <div class="top">
    <a href="#" class="top-link"><i class="ri-arrow-up-line"></i></a>
  </div>

    <footer class="section__container footer__container">
      <span class="bg__blur"></span>
      <span class="bg__blur footer__blur"></span>
      <div class="footer__col">
        <div class="footer__logo"><img src="assets/logo1.png" alt="logo" /></div>
        <p>
          Take the first step towards a healthier, stronger you with our
          unbeatable pricing plans. Let's sweat, achieve, and conquer together!
        </p>
        <div class="footer__socials">
          <a href="#"><i class="ri-facebook-fill"></i></a>
          <a href="#"><i class="ri-instagram-line"></i></a>
          <a href="#"><i class="ri-twitter-fill"></i></a>
        </div>
      </div>
      <div class="footer__col">
        <h4>Company</h4>
        <a href="#">Business</a>
        <a href="#">Franchise</a>
        <a href="#">Partnership</a>
        <a href="#">Network</a>
      </div>
      <div class="footer__col">
        <h4>About Us</h4>
        <a href="#">Blogs</a>
        <a href="#">Security</a>
        <a href="#">Careers</a>
      </div>
      <div class="footer__col">
        <h4>Contact</h4>
        <a href="#">Contact Us</a>
        <a href="#">Privacy Policy</a>
        <a href="#">Terms & Conditions</a>
        <a href="#">BMI Calculator</a>
      </div>
    </footer>
    <div class="footer__bar">
      Copyright © 2023 Gorkha Martial Arts. All rights reserved.
    </div>

    <!-- <script>
  function toggleTimetable() {
    var timetableContainer = document.getElementById("timetableContainer");
    timetableContainer.style.display = (timetableContainer.style.display === "block") ? "none" : "block";
  }
</script> -->


  </body>
</html>
