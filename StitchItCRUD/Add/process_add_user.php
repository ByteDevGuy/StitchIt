<?php
include '../db_connect.php';

$fn = $_POST['fn'];
$ln = $_POST['ln'];
$email = $_POST['email'];
$username = $_POST['username'];
$passcode = trim($_POST['passcode'] ?? '');
$hashed_passcode = password_hash($_POST['passcode'], PASSWORD_DEFAULT); // hashed password
$number = $_POST['number'];
if (!preg_match('/^\d{11}$/', $number)) {
    die("Phone number must be exactly 11 digits.");
}

$address = $_POST['address'];

$stmt = $conn->prepare("INSERT INTO users (fn, ln, email, username, passcode, number, address) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssss", $fn, $ln, $email, $username, $hashed_passcode, $number, $address);

if ($stmt->execute()) {
    echo    "<script>
                alert('Product added to cart successfully!');
                window.location.href = '../index.php';
            </script>";
        exit();
}
else echo "Error: " . $stmt->error;

$stmt->close();
$conn->close();
?>
