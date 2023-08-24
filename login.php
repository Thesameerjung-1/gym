<?php
session_start();

require_once "config.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is already logged in
if (isset($_SESSION['username'])) {
    header("Location: welcome.php");
    exit;
}

$identifier = $password = "";
$identifier_err = $password_err = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (empty(trim($_POST['identifier'])) || empty(trim($_POST['password']))) {
        $identifier_err = $password_err = "Please enter email/username and password.";
    } else {
        $identifier = trim($_POST['identifier']);
        $password = trim($_POST['password']);
    }

    if (empty($identifier_err) && empty($password_err)) {
        $sql = "SELECT id, email, username, password, role FROM users WHERE email = ? OR username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $param_identifier, $param_identifier);
        $param_identifier = $identifier;

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) == 1) {
                mysqli_stmt_bind_result($stmt, $id, $email, $username, $hashed_password, $role);

                if (mysqli_stmt_fetch($stmt)) {
                    if ($role === 'admin' && $identifier === 'admin' && password_verify($password, $hashed_password)) {
                        // Admin login
                        $_SESSION["username"] = $username;
                        $_SESSION["id"] = $id;
                        $_SESSION["role"] = $role;
                        header("Location: admin_dashboard.php");
                        exit;
                    } elseif ($role !== 'admin') {
                        // Non-admin login
                        if ($email === $identifier && password_verify($password, $hashed_password)) {
                            // Set session variables
                            $_SESSION["username"] = $username;
                            $_SESSION["id"] = $id;
                            $_SESSION["role"] = $role;
                            header("Location: welcome.php");
                            exit;
                        } elseif ($username === $identifier) {
                            $identifier_err = "Non-admin users should use their email to log in.";
                        } else {
                            $password_err = "Incorrect credentials.";
                        }
                    } else {
                        $password_err = "Incorrect credentials.";
                    }
                }
            } else {
                $identifier_err = "No account found with this email/username.";
            }
        } else {
            echo "Something went wrong";
        }

        mysqli_stmt_close($stmt);
    }
    mysqli_close($conn);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<title>Gym Website Login</title>
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
  .login-container {
    background-color: #ffffff;
    border-radius: 10px;
    padding: 20px;
    padding-right:38px;
    /* padding-left:15px; */

    box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
  }
  .login-container h2 {
    margin-bottom: 20px;
  }
  .input-group {
    margin-bottom: 15px;
  }
  label {
    display: block;
    margin-bottom: 5px;
  }
  input[type="text"], input[type="password"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
  }
  .login-button {
    background-color: #f9ac54;
    color: white;
    border: none;
    border-radius: 5px;
    padding: 10px 20px;
    cursor: pointer;
    transition: background-color 0.3s;
  }
  .login-button:hover {
    background-color:  
    #d79447;
  }
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
    width: 30px; /* Set a specific width */
    height: 30px; /* Set a specific height */
  }
  
  .social-buttons button:hover {
    color: #3498db;
  }
  .forgot-password {
    margin-top: 15px;
  }

  .error {
    color: red;
    font-size: 14px;
}

</style>
</head>
<body>

<div class="login-container">
    <h2>Login</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
    <div class="input-group">
    <label for="identifier">Email/Username:</label>
    <div class="input-error-container">
        <input type="text" id="identifier" name="identifier" required>
        <?php
        echo "<p class='error'>";
        echo $identifier_err ? $identifier_err : '';
        echo $password_err ? $password_err : '';
        echo "</p>";
        ?> <!-- Display error message -->
    </div>
</div>

        <div class="input-group">
            <label for="password">Password:</label>
            <div class="input-error-container">
                <input type="password" id="password" name="password" required>
                <?php echo "<p class='error'>" . $password_err . "</p>"; ?> <!-- Display error message -->
            </div>
        </div>

        <button class="login-button" type="submit">Login</button>
    </form>

        <div class="social-buttons">
    <button><img src="assets\facebook.png" alt="Facebook"></button>
    <button><img src="assets\google.png" alt="Google"></button>
  </div>
  <div class="forgot-password">
    <a href="forgot_password.php">Forgot Password?</a>
  </div>
  <div class="guest-login">
        <p>or</p>
        <button class="login-button" id="guest-login">Login as Guest</button>
    </div>
</div>

<script>
    // JavaScript to handle the "Login as Guest" button click
    document.getElementById("guest-login").addEventListener("click", function() {
        // Redirect the user to welcome.php as a guest
        window.location.href = "welcome.php";
    });
</script>


</div>
    </div>


</body>
</html>