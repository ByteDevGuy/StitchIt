<?php
include '../db_connect.php';

if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);
    
    // Delete user by id
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        header("Location: ../index.php"); // redirect back to main interface
        exit();
    } else {
        echo "Error deleting user: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "No user ID specified.";
}

$conn->close();
?>
