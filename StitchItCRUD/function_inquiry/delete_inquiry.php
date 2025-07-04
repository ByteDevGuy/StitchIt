<?php
include '../db_connect.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $conn->prepare("DELETE FROM inquiry WHERE inquiry_id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: ../index.php"); // Replace with actual path
        exit;
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}
?>
