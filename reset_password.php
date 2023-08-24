<?php
session_start();

require_once "config.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $code = trim($_POST['code']);
    $new_password = $_POST['new_password'];

    $sql = "SELECT id FROM users WHERE reset_code = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $code);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) == 1) {
        $user_data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        $user_id = $user_data['id'];
        
        // Update the password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $updatePasswordSql = "UPDATE users SET password = ?, reset_code = NULL WHERE id = ?";
        $updatePasswordStmt = mysqli_prepare($conn, $updatePasswordSql);
        mysqli_stmt_bind_param($updatePasswordStmt, "si", $hashed_password, $user_id);
        mysqli_stmt_execute($updatePasswordStmt);
        
        // Display success message to the user
        $success_message = "Password has been successfully reset.";
    } else {
        // Display error message to the user
        $error_message = "Invalid reset code.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<!-- ... Head content ... -->
</head>
<body>
<div class="reset-container">
    <h2>Reset Password</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <div class="input-group">
            <label for="code">Reset Code:</label>
            <input type="text" id="code" name="code" required>
        </div>
        <div class="input-group">
            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" required>
        </div>
        <button class="reset-button" type="submit">Reset Password</button>
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
