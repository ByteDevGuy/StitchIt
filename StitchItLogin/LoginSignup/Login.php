<?php
session_start();
include '../dbConnection/db_connect.php';

// Get and sanitize input
$user = trim($_POST['username']);
$pass = trim($_POST['password']);

// Prepare and execute the query
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $hashed = $row['passcode'];

    // Check password
    if (password_verify($pass, $hashed)) {
        // Login success
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['username'] = $row['username'];
        header("Location: ../index.php");
        exit();
    } else {
        // Incorrect password
        header("Location: LoginSignup.php?error=Incorrect+Username+/+Password");
        exit();
    }
} else {
    // Username not found
    header("Location: LoginSignup.php?error=User+not+found");
    exit();
}

// Clean up
$stmt->close();
$conn->close();
