<?php
session_start();
header('Content-Type: application/json');
include '../dbConnection/db_connect.php';

// Sanitize and trim form values
$first_name = trim($_POST['fn'] ?? '');
$last_name = trim($_POST['ln'] ?? '');
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['passcode'] ?? '');

// Optional fields (can be updated later)
$number = null;
$address = null;

// Validate required fields (basic)
if (empty($first_name) || empty($last_name) || empty($username) || empty($email) || empty($password)) {
    echo json_encode([
        "status" => "error",
        "message" => "All fields are required."
    ]);
    exit();
}

// Hash the password securely
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Check if username already exists
$check_stmt = $conn->prepare("SELECT 1 FROM users WHERE username = ?");
$check_stmt->bind_param("s", $username);
$check_stmt->execute();
$check_stmt->store_result();

if ($check_stmt->num_rows > 0) {
    echo json_encode([
        "status" => "taken",
        "message" => "Username already taken. Please choose another."
    ]);
    $check_stmt->close();
    $conn->close();
    exit();
}
$check_stmt->close();

// Insert user into database
$insert_stmt = $conn->prepare("
    INSERT INTO users (fn, ln, username, email, passcode, number, address)
    VALUES (?, ?, ?, ?, ?, ?, ?)
");
$insert_stmt->bind_param(
    "sssssss",
    $first_name,
    $last_name,
    $username,
    $email,
    $hashed_password,
    $number,
    $address
);

if ($insert_stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "Registration successful!"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Something went wrong: " . $insert_stmt->error
    ]);
}

$insert_stmt->close();
$conn->close();