<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

require_once "config.php";

$user_id = $_SESSION['id'];

// Fetch user information from the database
$sql = "SELECT username, email, phone, age, experience, program, membership, starting_date, expiry_date FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $username, $email, $phone, $age, $experience, $program, $selectedMembership, $startingDate, $expiryDate);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

if (isset($_POST['subscribe'])) {
  $selectedMembership = $_POST['membership'];

  // Calculate starting and expiry dates based on the selected membership
  $currentDate = date("Y-m-d");
  $startingDate = $currentDate;
  $expiryDate = '';

  if ($selectedMembership === 'Basic') {
      $expiryDate = date("Y-m-d", strtotime($currentDate . "+2 weeks"));
  } elseif ($selectedMembership === 'Intermediate') {
      $expiryDate = date("Y-m-d", strtotime($currentDate . "+3 weeks"));
  } elseif ($selectedMembership === 'Advanced') {
      $expiryDate = date("Y-m-d", strtotime($currentDate . "+5 weeks"));
  } elseif ($selectedMembership === 'Elite') {
      $expiryDate = "9999-12-31"; // Set an arbitrary far future date for unlimited membership
  }

  // Update the user's selected membership and dates in the database
  $updateMembershipQuery = "UPDATE users SET membership = ?, starting_date = ?, expiry_date = ? WHERE id = ?";
  $updateStmt = mysqli_prepare($conn, $updateMembershipQuery);
  mysqli_stmt_bind_param($updateStmt, "sssi", $selectedMembership, $startingDate, $expiryDate, $user_id);
  mysqli_stmt_execute($updateStmt);
  mysqli_stmt_close($updateStmt);

  $subscriptionMessage = "Subscribed to $selectedMembership membership!";
}



// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

// Retrieve user's membership information from the database
$userInfoQuery = "SELECT membership, starting_date, expiry_date FROM users WHERE username = '$username'";
$userInfoResult = mysqli_query($conn, $userInfoQuery);

if ($userInfoResult && mysqli_num_rows($userInfoResult) > 0) {
    $userInfo = mysqli_fetch_assoc($userInfoResult);
    $userMembership = $userInfo['membership'];
    $startingDate = $userInfo['starting_date'];
    $expiryDate = $userInfo['expiry_date'];
}
// Calculate the number of days left until the membership plan expires
if ($expiryDate !== '9999-12-31') {
  $currentDate = date("Y-m-d");
  $daysLeft = max(0, (strtotime($expiryDate) - strtotime($currentDate)) / (60 * 60 * 24));
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Profile - Gorkha Martial Arts</title>
    <style>
        body {
  font-family: Arial, sans-serif;
  background-color: #f0f0f0;
  margin: 0;
  padding: 0;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
}

.container {
  background-color: #ffffff;
  border-radius: 10px;
  padding: 20px;
  padding-right: 38px;
  box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
  text-align: center;
  width: 80%;
  max-width: 500px;
}

h1 {
  margin-bottom: 20px;
  color: #333;
}

p {
  margin-bottom: 15px;
  color: #666;
}

a {
  display: inline-block;
  margin-top: 10px;
  background-color: #f9ac54;
  color: white;
  border: none;
  border-radius: 5px;
  padding: 10px 20px;
  text-decoration: none;
  transition: background-color 0.3s;
}

a:hover {
  background-color: #d79447;
}

/* Add your social buttons styles here */
.social-buttons {
  display: flex;
  justify-content: center;
  margin-top: 20px;
}

.social-buttons button {
  background-color: transparent;
  border: none;
  cursor: pointer;
  margin: 0 10px;
  transition: color 0.3s;
}

.social-buttons button img {
  width: 30px;
  height: 30px;
}

.social-buttons button:hover {
  color: #3498db;
}

/* Add your forgot password styles here */
.forgot-password {
  margin-top: 15px;
}

/* Add your guest login styles here */
.guest-login {
  margin-top: 20px;
}

    </style>
</head>
<body>
<div class="container">
    <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>

    <p>Email: <?php echo $email; ?></p>
    <p>Phone: <?php echo $phone; ?></p>
    <p>Age: <?php echo $age; ?></p>
    <p>Experience: <?php echo $experience; ?></p>
    <p>Program: <?php echo $program; ?></p>
    
    <?php if (isset($subscriptionMessage)) { ?>
        <p><?php echo $subscriptionMessage; ?></p>
    <?php } ?>
    
    <?php // Display the selected membership from the database ?>
    <p>Selected Membership: <?php echo $selectedMembership; ?></p>
    <p>Starting Date: <?php echo $startingDate; ?></p>
    <p>Expiry Date: <?php echo $expiryDate; ?></p>
    <?php if (isset($daysLeft)) { ?>
        <?php if ($daysLeft > 0) { ?>
            <p><?php echo "Days Left to Expire: $daysLeft"; ?></p>
        <?php } else { ?>
            <p><?php echo "Your membership has expired."; ?></p>
        <?php } ?>
    <?php } ?>
    
    <a href="edit_profile.php">Edit Profile</a>
    <a href="welcome.php">Back to Home Page</a>
    <a href="logout.php">Logout</a>
</div>

</body>
</html>
