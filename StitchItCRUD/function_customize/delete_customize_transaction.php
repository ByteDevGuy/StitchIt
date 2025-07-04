<?php
include '../db_connect.php';

if (isset($_GET['id'])) {
    $customize_id = intval($_GET['id']);
    
    $stmt = $conn->prepare("DELETE FROM customize_transaction WHERE customize_id = ?");
    $stmt->bind_param("i", $customize_id);

    if ($stmt->execute()) {
        header("Location: ../index.php");
        exit();
    } else {
        echo "Error deleting customize transaction: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "No customize transaction ID specified.";
}

$conn->close();
?>
