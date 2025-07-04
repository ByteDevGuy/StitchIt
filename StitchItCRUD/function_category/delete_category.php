<?php
include '../db_connect.php';

if (isset($_GET['id'])) {
    $category_id = intval($_GET['id']);
    
    $stmt = $conn->prepare("DELETE FROM category WHERE category_id = ?");
    $stmt->bind_param("i", $category_id);

    if ($stmt->execute()) {
        header("Location: ../index.php");
        exit();
    } else {
        echo "Error deleting category: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "No category ID specified.";
}

$conn->close();
?>
