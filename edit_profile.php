<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

require_once "config.php";

$username = $_SESSION['username'];

// Update user profile
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $age = $_POST["age"];
    $experience = $_POST["experience"];
    $program = $_POST["program"];

    $updateSql = "UPDATE users SET email = ?, phone = ?, age = ?, experience = ?, program = ? WHERE username = ?";
    $updateStmt = mysqli_prepare($conn, $updateSql);
    mysqli_stmt_bind_param($updateStmt, "ssisss", $email, $phone, $age, $experience, $program, $username);

    if (mysqli_stmt_execute($updateStmt)) {
        mysqli_stmt_close($updateStmt); // Close the update statement

        // Fetch updated user details from the database
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);

        // Close the statement
        mysqli_stmt_close($stmt);

        // Close the database connection
        mysqli_close($conn);

        header("Location: profile.php");
        exit();
    } else {
        $updateError = "Error updating profile. Please try again.";
    }

    // Close the statement
    mysqli_stmt_close($updateStmt);
}

// Retrieve user details from the database
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

// Close the statement
mysqli_stmt_close($stmt);

// Close the database connection
mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Add your sample CSS code here */
        body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}

.container {
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    width: 400px;
}

.container h1 {
    font-size: 28px;
    margin-bottom: 20px;
    text-align: center;
}

.container label {
    display: block;
    font-size: 16px;
    margin-bottom: 5px;
}

.container input,
.container select {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
}

.container button {
    background-color: #f9ac54;
    color: #fff;
    border: none;
    border-radius: 5px;
    padding: 12px 20px;
    cursor: pointer;
    font-size: 16px;
    width: 100%;
}

.container button:hover {
    background-color: #d79447;
}

.container a {
    display: block;
    margin-top: 20px;
    font-size: 16px;
    text-align: center;
    color: #f9ac54;
    text-decoration: none;
}

.container a:hover {
    text-decoration: underline;
    color:  #d79447;
}
</style>
    <title>Edit Profile - Gorkha Martial Arts</title>
</head>
<body>
    <div class="container">
        <h1>Edit Profile</h1>
        <?php if (isset($updateError)) { ?>
            <p class="error"><?php echo $updateError; ?></p>
        <?php } ?>
        <form action="" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo $row['username']; ?>" readonly>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $row['email']; ?>" required>

            <label for="phone">Phone:</label>
            <input type="tel" id="phone" name="phone" value="<?php echo $row['phone']; ?>" required>

            <label for="age">Age:</label>
            <input type="number" id="age" name="age" value="<?php echo $row['age']; ?>" required>

            <label for="experience">Martial Arts Experience:</label>
            <select id="experience" name="experience">
                <option value="beginner" <?php if ($row['experience'] === 'beginner') echo 'selected'; ?>>Beginner</option>
                <option value="intermediate" <?php if ($row['experience'] === 'intermediate') echo 'selected'; ?>>Intermediate</option>
                <option value="advanced" <?php if ($row['experience'] === 'advanced') echo 'selected'; ?>>Advanced</option>
            </select>

            <label for="program">Preferred Training Program:</label>
            <select id="program" name="program">
                <option value="karate" <?php if ($row['program'] === 'karate') echo 'selected'; ?>>Karate</option>
                <option value="taekwondo" <?php if ($row['program'] === 'taekwondo') echo 'selected'; ?>>Taekwondo</option>
                <option value="jiu_jitsu" <?php if ($row['program'] === 'jiu_jitsu') echo 'selected'; ?>>Jiu-Jitsu</option>
                <option value="muay_thai" <?php if ($row['program'] === 'muay_thai') echo 'selected'; ?>>Muay Thai</option>
                <option value="other" <?php if ($row['program'] === 'other') echo 'selected'; ?>>Other</option>
            </select>

            <button type="submit">Update Profile</button>
        </form>
        <a href="profile.php">Back to Profile</a>
    </div>
</body>
</html>
