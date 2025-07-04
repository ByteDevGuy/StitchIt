<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $message = $_POST['message'];

    $stmt = $conn->prepare("INSERT INTO review (message, user_id) VALUES (?, ?)");
    $stmt->bind_param("si", $message, $user_id);
    if ($stmt->execute()) {
        echo "Review submitted successfully.<br><a href='index.php'>Back</a>";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>
