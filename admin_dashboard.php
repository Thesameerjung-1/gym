<?php
session_start();

$validationError = ""; // Initialize the validation error variable

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Check if the user is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

require_once "config.php";

// Handle Create User
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {
        $username = $_POST['new_username'];
        $email = $_POST['new_email'];
        $phone = $_POST['new_phone'];
        $age = $_POST['new_age'];
        $experience = $_POST['new_experience'];
        $program = $_POST['new_program'];
        $password = password_hash($_POST['new_password'], PASSWORD_DEFAULT); // Hash the password

        // Check if the email already exists
        $checkEmailSql = "SELECT email FROM users WHERE email = ?";
        $checkEmailStmt = mysqli_prepare($conn, $checkEmailSql);
        mysqli_stmt_bind_param($checkEmailStmt, "s", $email);
        mysqli_stmt_execute($checkEmailStmt);
        mysqli_stmt_store_result($checkEmailStmt);

        if (mysqli_stmt_num_rows($checkEmailStmt) > 0) {
            $validationError = "Email already exists.";
            mysqli_stmt_close($checkEmailStmt);
        } else {
            mysqli_stmt_close($checkEmailStmt); // Close the email validation statement

            // Continue with the INSERT INTO statement
            $sql = "INSERT INTO users (username, email, phone, age, experience, program, password) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sssssss", $username, $email, $phone, $age, $experience, $program, $password);

            if (mysqli_stmt_execute($stmt)) {
                // User created successfully
            } else {
                // Error creating user
            }

            mysqli_stmt_close($stmt);
        }
    }


// Handle Update User
if (isset($_POST['update'])) {
    $user_id = $_POST['user_id'];
    $new_email = $_POST['new_email'];
    $new_phone = $_POST['new_phone'];
    $new_age = $_POST['new_age'];
    $new_experience = $_POST['new_experience'];
    $new_program = $_POST['new_program'];

    $sql = "UPDATE users SET email = ?, phone = ?, age = ?, experience = ?, program = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssssi", $new_email, $new_phone, $new_age, $new_experience, $new_program, $user_id);

    if (mysqli_stmt_execute($stmt)) {
        // User updated successfully
    } else {
        // Error updating user
    }

    mysqli_stmt_close($stmt);
}

// Handle Delete User
if (isset($_POST['delete'])) {
    $user_id = $_POST['user_id'];

    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);

    if (mysqli_stmt_execute($stmt)) {
        // User deleted successfully
    } else {
        // Error deleting user
    }

    mysqli_stmt_close($stmt);
}

}
// Retrieve and display users
$sql = "SELECT * FROM users";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<style>
    :root {
    --primary-color: #111317;
    --primary-color-light: #1f2125;
    --primary-color-extra-light: #35373b;
    --secondary-color: #f9ac54;
    --secondary-color-dark: #d79447;
    --text-light: #d1d5db;
    --text-dark: #333333;
    --white: #ffffff;
}

/* Reset and basic styling */
body {
    font-family: Arial, sans-serif;
    background-color: var(--primary-color-light);
    color: var(--text-dark);
    margin: 0;
    padding: 0;
}

.container {
    max-width: 1500px;
    margin: 0 auto;
    padding: 20px;
    background-color: var(--white);
    box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
}

/* Header styling */
header {
    background-color: var(--secondary-color);
    color: var(--white);
    padding: 10px;
    text-align: center;
}

/* Table styling */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
    border: 1px solid var(--text-light);
    padding: 12px;
    text-align: center;
}

th {
    background-color: var(--secondary-color);
    color: var(--white);
    font-weight: bold;
}

/* Form styling */
.form {
    margin-top: 20px;
    display: inline-block;
}

label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
}

input[type="text"],
input[type="password"],
input[type="email"],
input[type="tel"],
select {
    width: 100%;
    padding: 8px; /* Adjusted padding */
    margin-bottom: 10px;
    border: 1px solid var(--text-light);
    border-radius: 4px;
    max-width: 300px; /* Added max-width for smaller text boxes */
}

button[type="submit"],
.button-link {
    padding: 10px 18px;
    background-color: var(--secondary-color);
    color: var(--white);
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease-in-out;
}

button[type="submit"]:hover,
.button-link:hover {
    background-color: var(--secondary-color-dark);
}

/* Heading styling */
h2 {
    margin-top: 20px;
    font-size: 1.6rem;
    color: var(--secondary-color);
}

/* Flex container for search and button */
.flex-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

/* Search form styles */
.search-form {
    flex-grow: 1;
    margin-right: 10px;
}

/* Button styles */
.button-link {
    padding: 10px 18px;
    background-color: var(--secondary-color);
    color: var(--white);
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease-in-out;
}

.button-link:hover {
    background-color: var(--secondary-color-dark);
}


</style>
</head>
<body>
    
    <div class="container">
    <H1>ADMIN PANEL</h1>

  


            <!-- Search User Form -->
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
        <label for="search_username">Search by Username:</label>
        <input type="text" id="search_username" name="search_username">
        <button type="submit" name="search">Search</button>
    </form>
<br>
      <!-- Go to Home Page Button -->
<a href="welcome.php" class="button-link">Go to Home Page</a>
        <table>
    <thead>
        <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Age</th>
            <th>Experience</th>
            <th>Program</th>
            <th>Membership</th>
            <th>Starting Date</th>
            <th>Expiry Date</th>
            <th>Action</th>

        </tr>
    </thead>
<!-- ... -->

<tbody>
    <?php 

$search_username = isset($_GET['search_username']) ? $_GET['search_username'] : '';

if (!empty($search_username)) {
    // Search query with username filter
    $sql = "SELECT * FROM users WHERE username LIKE ?";
    $stmt = mysqli_prepare($conn, $sql);
    $search_pattern = "%$search_username%";
    mysqli_stmt_bind_param($stmt, "s", $search_pattern);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
}
    
    
    
    while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $row['username']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['phone']; ?></td>
            <td><?php echo $row['age']; ?></td>
            <td><?php echo $row['experience']; ?></td>
            <td><?php echo $row['program']; ?></td>
            <td><?php echo $row['membership']; ?></td>
            <td><?php echo $row['starting_date']; ?></td>
            <!-- Display membership expiry date (assuming it's stored in the database as 'expiry_date') -->
            <td><?php echo $row['expiry_date']; ?></td>
            <td>
                <?php if (isset($_POST['edit']) && $_POST['user_id'] === $row['id']) { ?>
                    <?php if ($_SESSION['username'] !== $row['username']) { ?>

            <!-- Display edit form for the selected user -->
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                    Email: <input type="text" name="new_email" value="<?php echo $row['email']; ?>">
                    Phone: <input type="text" name="new_phone" value="<?php echo $row['phone']; ?>">
                  <br>  Age: <input type="number" name="new_age" value="<?php echo $row['age']; ?>">
                    
                    <!-- Dropdown menu for Experience -->
                    <label for="new_experience">Experience:</label>
                    <select id="new_experience" name="new_experience">
                        <option value="beginner" <?php if ($row['experience'] === 'beginner') echo 'selected'; ?>>Beginner</option>
                        <option value="intermediate" <?php if ($row['experience'] === 'intermediate') echo 'selected'; ?>>Intermediate</option>
                        <option value="advanced" <?php if ($row['experience'] === 'advanced') echo 'selected'; ?>>Advanced</option>
                    </select>
                    
                    <!-- Dropdown menu for Program -->
                    <label for="new_program">Program:</label>
                    <select id="new_program" name="new_program">
                        <option value="karate" <?php if ($row['program'] === 'karate') echo 'selected'; ?>>Karate</option>
                        <option value="taekwondo" <?php if ($row['program'] === 'taekwondo') echo 'selected'; ?>>Taekwondo</option>
                        <option value="jiu_jitsu" <?php if ($row['program'] === 'jiu_jitsu') echo 'selected'; ?>>Jiu-Jitsu</option>
                        <option value="muay_thai" <?php if ($row['program'] === 'muay_thai') echo 'selected'; ?>>Muay Thai</option>
                        <option value="other" <?php if ($row['program'] === 'other') echo 'selected'; ?>>Other</option>
                    </select>
                    
                    <br>  
                    <br>
                    
                    <button type="submit" name="update">Save</button>
                    <?php if ($_SESSION['username'] !== $row['username']) { ?>
                        <!-- Display delete button only if the user is not an admin and not the current row's user -->
                        <button type="submit" name="delete">Delete</button>
                    <?php } ?>
                </form>

                    <?php } ?>
                <?php } else { ?>
                    <!-- Display edit and delete buttons for other users -->
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                        <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="edit">Edit</button>
                        <?php if ($_SESSION['username'] !== $row['username']) { ?>
                            <!-- Display delete button only if the user is not an admin and not the current row's user -->
                            <button type="submit" name="delete">Delete</button>
                        <?php } ?>
                    </form>
                <?php } ?>
            </td>
        </tr>
    <?php } ?>
</tbody>
<!-- ... -->


</table>

<!-- Create User Form -->
<h2>Create New User</h2>
<?php if (isset($validationError)) { ?>
    <div style="color: red;"><?php echo $validationError; ?></div>
<?php } ?>
<form action="" method="post">
    <label for="new_username">Username:</label>
    <input type="text" id="new_username" name="new_username" required autocomplete="off">

    <label for="new_password">Password:</label>
    <input type="password" id="new_password" name="new_password" required>

    <label for="new_email">Email:</label>
    <input type="email" id="new_email" name="new_email" required>
    <?php if (isset($email_err)) { ?>
        <span class="error"><?php echo $email_err; ?></span>
    <?php } ?>

    <label for="new_phone">Phone:</label>
    <input type="tel" id="new_phone" name="new_phone" required>

    <label for="new_age">Age:</label>
    <input type="number" id="new_age" name="new_age" required>

    <label for="new_experience">Martial Arts Experience:</label>
    <select id="new_experience" name="new_experience" required>
        <option value="beginner">Beginner</option>
        <option value="intermediate">Intermediate</option>
        <option value="advanced">Advanced</option>
    </select>

    <label for="new_program">Preferred Training Program:</label>
    <select id="new_program" name="new_program" required>
        <option value="karate">Karate</option>
        <option value="taekwondo">Taekwondo</option>
        <option value="jiu_jitsu">Jiu-Jitsu</option>
        <option value="muay_thai">Muay Thai</option>
        <option value="other">Other</option>
    </select>

    <!-- Special Key Field -->
    <!-- <label for="special_key">Special Key:</label>
    <input type="password" id="special_key" name="special_key" required autocomplete="off"> -->

    <!-- Add more fields here if needed -->

    <button type="submit" name="create">Create User</button>
</form>

    </div>
</body>
</html>