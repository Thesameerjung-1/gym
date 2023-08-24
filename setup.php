<?php
require_once "config.php";

// Generate a hashed password for the admin user
$hashed_password = password_hash('admin', PASSWORD_DEFAULT);

// Insert the admin user into the database
$sql = "INSERT INTO users (username, password, email, phone, age, experience, program, role) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ssssisss", $username, $hashed_password, $email, $phone, $age, $experience, $program, $role);

// Set the values for other user fields
$username = 'admin';
$email = 'admin@example.com';
$phone = '1234567890';
$age = 30;
$experience = 'advanced';
$program = 'karate';
$role = 'admin';

// Execute the statement
if (mysqli_stmt_execute($stmt)) {
    echo "Admin user created successfully.";
} else {
    echo "Error creating admin user.";
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>



