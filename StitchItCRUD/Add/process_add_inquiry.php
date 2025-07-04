<?php
include '../db_connect.php';

$fn = $_POST['fn'] ?? '';
$ln = $_POST['ln'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$message = $_POST['message'] ?? '';
$user_id = isset($_POST['user_id']) && is_numeric($_POST['user_id']) && $_POST['user_id'] !== '' ? intval($_POST['user_id']) : null;

if (!$fn || !$ln || !$email || !$phone) {
    die("All required fields must be filled.");
}

if ($user_id === null) {
    // Without user_id
    $stmt = $conn->prepare("INSERT INTO inquiry (fn, ln, email, phone, message) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $fn, $ln, $email, $phone, $message);
} else {
    // With valid user_id
    $stmt = $conn->prepare("INSERT INTO inquiry (fn, ln, email, phone, message, user_id) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssi", $fn, $ln, $email, $phone, $message, $user_id);
}

if ($stmt->execute()) {
    echo "<script>alert('Inquiry submitted!'); window.location.href = '../index.php';</script>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
