<?php
include '../db_connect.php';

if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    
    $stmt = $conn->prepare("DELETE FROM product WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
        header("Location: ../index.php");
        exit();
    } else {
        echo "Error deleting product: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "No product ID specified.";
}

$conn->close();
?>
