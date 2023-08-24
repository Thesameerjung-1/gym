<?php
session_start();

require_once "config.php";
require_once "email_functions.php"; // Create this file for sending emails

error_reporting(E_ALL);
ini_set('display_errors', 1);

$error_message = ""; // Initialize the error message variable
$success_message = ""; // Initialize the success message variable

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = trim($_POST['email']);
    
    // Escape the email address
    $email = mysqli_real_escape_string($conn, $email);

    // Check if the email exists in the database
    $checkemailSql = "SELECT id, username FROM users WHERE email = ?";
    $checkemailStmt = mysqli_prepare($conn, $checkemailSql);
    mysqli_stmt_bind_param($checkemailStmt, "s", $email);
    mysqli_stmt_execute($checkemailStmt);
    
    // Fetch the result from the statement
    
    if (mysqli_stmt_num_rows($checkemailStmt) == 1) {
        // The email address exists in the database
        
        // Generate a random code for password reset
        $reset_code = bin2hex(random_bytes(32));
        
        // Update the reset code in the database
        $updateCodeSql = "UPDATE users SET reset_code = ? WHERE id = ?";
        $updateCodeStmt = mysqli_prepare($conn, $updateCodeSql);
        mysqli_stmt_bind_param($updateCodeStmt, "si", $reset_code, $user_id);
        mysqli_stmt_execute($updateCodeStmt);
        
        // Send password reset link to the user's email
        $reset_link = "http://localhost/Gorkha_martial_arts/reset_password.php?code=$reset_code";
        $subject = "Password Reset Request";
        $message = "Hi {$user_data['username']},\n\nYou have requested a password reset. Click the link below to reset your password:\n$reset_link";
        
        sendEmail($email, $subject, $message); // Implement the sendEmail function
        
        // Set the success message
        $success_message = "Password reset link has been sent to your email.";
    } else {
        // The email address does not exist in the database
        $error_message = "Invalid email address.";
    }

    // Close the prepared statement
    mysqli_stmt_close($checkemailStmt);
}

?>





<!DOCTYPE html>
<html lang="en">
<head>
<!-- ... Head content ... -->
</head>
<body>
<div class="reset-container">
    <h2>Forgot Password</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <div class="input-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <button class="reset-button" type="submit">Send Reset Link</button>
    </form>
    <?php
    if (isset($error_message)) {
        echo "<p class='error'>$error_message</p>";
    }
    if (isset($success_message)) {
        echo "<p class='success'>$success_message</p>";
    }
    ?>
</div>
<!-- ... Other content ... -->
</body>
</html>
