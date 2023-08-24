<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

require_once "config.php";

$username = $_SESSION['username'];
$user_id = $_SESSION['id'];

$password_err = "";

// Process form submission for changing password
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['change_password'])) {
    $current_password = trim($_POST['current_password']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Retrieve the current hashed password from the database
    $retrieve_password_sql = "SELECT password FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $retrieve_password_sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $hashed_password);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    // Verify the current password
    if (password_verify($current_password, $hashed_password)) {
        // Update password in the database
        $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
        $update_password_sql = "UPDATE users SET password = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $update_password_sql);
        mysqli_stmt_bind_param($stmt, "si", $hashed_new_password, $user_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Redirect to profile page or a success page
        header("Location: profile.php");
        exit;
    } else {
        $password_err = "Current password is incorrect";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Settings - Gorkha Martial Arts</title>
    <style>
        /* Reset some default styles */
        :root {
  --primary-color: #111317;
  --secondary-color: #f9ac54;
  --text-light: #d1d5db;
  --text-dark: #6b7280;
  --white: #ffffff;
  --max-width: 1200px;
}

body {
  font-family: Arial, sans-serif;
  background-color: var(--primary-color);
  color: var(--text-dark);
  margin: 0;
  padding: 0;
}

.container {
  max-width: var(--max-width);
  margin: 0 auto;
  padding: 20px;
  background-color: var(--white);
  border-radius: 10px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

h1 {
  font-size: 32px;
  margin-bottom: 20px;
  color: var(--secondary-color);
}

p {
  margin-bottom: 20px;
  font-size: 18px;
  color: var(--text-dark); /* Adjusted color for better visibility */
}

form {
  margin-bottom: 20px;
}

label {
  display: block;
  font-weight: bold;
  margin-bottom: 10px;
  color: var(--secondary-color);
}

input[type="password"] {
  width: 100%;
  padding: 12px;
  margin-bottom: 15px;
  border: none;
  border-radius: 8px;
  background-color: #f5f5f5;
  color: var(--primary-color);
}

button[type="submit"] {
  padding: 12px 24px;
  background-color: var(--secondary-color);
  color: var(--white);
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: background-color 0.3s ease-in-out;
}

button[type="submit"]:hover {
  background-color: #e3963e;
}

.error {
  color: #d9534f;
  font-size: 14px;
  display: block;
  margin-top: 5px;
}

a {
  display: inline-block;
  margin-top: 15px;
  color: var(--secondary-color);
  text-decoration: none;
  font-weight: bold;
}

a:hover {
  text-decoration: underline;
}


    </style>
</head>
<body>
<div class="container">
        <h1>Change Password</h1>
        
        <p>Logged in as: <?php echo $username; ?></p>

        <form action="" method="POST">
            <label for="current_password">Current Password:</label>
            <input type="password" id="current_password" name="current_password" required>
            <span class="error"><?php echo $password_err; ?></span>

            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" required>

            <label for="confirm_password">Confirm New Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <button type="submit" name="change_password">Change Password</button>
        </form>
        
        <a href="profile.php">Back to Profile</a>
    </div>
</body>
</html>
