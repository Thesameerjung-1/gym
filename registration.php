<?php
require_once "config.php";

ini_set('display_errors', 1);
error_reporting(E_ALL);

$username = $password = $confirm_password = $email = $phone = $age = $experience = $program = "";
$username_err = $password_err = $confirm_password_err = $email_err = ""; // Initialize email_err here

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Check if username is empty
    if (empty(trim($_POST["username"]))) {
        $username_err = "Username cannot be blank";
    } else {
        $username = trim($_POST['username']);
    }

    // Check if email is empty
    if (empty(trim($_POST["email"]))) {
        $email_err = "Email cannot be blank";
    } else {
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $param_email);

            // Set the value of param email
            $param_email = trim($_POST['email']);

            // Try to execute the statement
            if (mysqli_stmt_execute($stmt)) {
                /* Store result */
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $email_err = "This email is already registered.";
                } else {
                    $email = trim($_POST['email']);
                }
            } else {
                echo "Something went wrong";
            }
            mysqli_stmt_close($stmt);
        }
    }

    // Check for password
    if (empty(trim($_POST['password']))) {
        $password_err = 'Please enter a password.';
    } elseif (strlen(trim($_POST['password'])) < 5) {
        $password_err = 'Password cannot be less than 5 characters';
    } else {
        $password = trim($_POST['password']);
    }

    // Check for confirm password field
    if (trim($_POST['password']) != trim($_POST['confirm_password'])) {
        $confirm_password_err = 'Passwords should match';
    }

    // Process other fields
    $phone = trim($_POST['phone']);
    $age = trim($_POST['age']);
    $experience = $_POST['experience'];
    $program = $_POST['program'];

    // If there were no errors, go ahead and insert into the database
    if (empty($password_err) && empty($confirm_password_err) && empty($email_err)) {
        $sql = "INSERT INTO users (username, password, email, phone, age, experience, program) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssssiss", $param_username, $param_password, $param_email, $param_phone, $param_age, $param_experience, $param_program);

            // Set these parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT);
            $param_email = $email;
            $param_phone = $phone;
            $param_age = $age;
            $param_experience = $experience;
            $param_program = $program;

            // Try to execute the query
            if (mysqli_stmt_execute($stmt)) {
                header("Location: login.php");
            } else {
                echo "Something went wrong, cannot redirect!";
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($conn);
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>MGorkha Martial Arts</title>
</head>
<style>
    body {
    font-family: Arial, sans-serif;
    background-color: #f2f2f2;
    margin: 0;
    padding: 0;
}
select{
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: url('dropdown-arrow.png'); /* Replace with your own arrow icon */
    background-position: right center;
    background-repeat: no-repeat;
}

.container {
    max-width: 400px;
    margin: 50px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
    margin-bottom: 20px;
    color: #333;
}

form {
    display: flex;
    flex-direction: column;
}

label {
    margin-bottom: 5px;
    font-weight: bold;
}

input,
textarea,
select {
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

button {
        background-color: #f9ac54;
        color: #fff;
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
        width: 100%; /* Make the buttons full width */
        box-sizing: border-box; /* Include padding and border in the width */
        margin-top: 10px; /* Add spacing between buttons */
    }

    button:hover {
        background-color: #d79447;
    }

    .guest-login {
        text-align: center;
    }

.error {
    color: red;
    font-size: 12px;
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
    margin: 0 65px;
    transition: color 0.3s;
  }

  .social-buttons button img {
    width: 30px; /* Set a specific width */
    height: 30px; /* Set a specific height */
  }
  
  .social-buttons button:hover {
    color: #3498db;
  }


    </style>
<body>
    <div class="container">
        <h1>Registration Form</h1>
        <form action="" method="POST">
        <label for="name">Username:</label>
        <input type="text" id="name" name="username" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Password" required>
        <span class="error"><?php echo $password_err; ?></span>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
        <span class="error"><?php echo $confirm_password_err; ?></span>

                <label for="email">Email:</label>
        <input type="email" id="email" name="email">
        <span class="error"><?php echo $email_err; ?></span>
            
            <label for="phone">Phone:</label>
            <input type="tel" id="phone" name="phone" required>
            
            <label for="age">Age:</label>
            <input type="number" id="age" name="age" required>
            
            <label for="experience">Martial Arts Experience:</label>
                <select id="experience" name="experience">
                    <option value="beginner">Beginner</option>
                    <option value="intermediate">Intermediate</option>
                    <option value="advanced">Advanced</option>
                </select>
            
            <label for="program">Preferred Training Program:</label>
            <select id="program" name="program">
                <option value="karate">Karate</option>
                <option value="taekwondo">Taekwondo</option>
                <option value="jiu_jitsu">Jiu-Jitsu</option>
                <option value="muay_thai">Muay Thai</option>
                <option value="other">Other</option>
            </select>
            
            <button type="submit">Register</button>
            <div class="guest-login">
        <p>or</p>
        <button class="login-button" id="guest-login">Login as Guest</button>
        <p>Also register with</p>
    </div>
    <div class="social-buttons">
    <button><img src="assets\facebook.png" alt="Facebook"></button>
    <button><img src="assets\google.png" alt="Google"></button>
  </div>
</div>

<script>
    // JavaScript to handle the "Login as Guest" button click
    document.getElementById("guest-login").addEventListener("click", function() {
        // Redirect the user to welcome.php as a guest
        window.location.href = "welcome.php";
    });
</script>




        </form>
    </div>
</body>
</html>
