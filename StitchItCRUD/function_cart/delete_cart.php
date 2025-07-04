<?php
include '../db_connect.php';

if (!isset($_GET['id'])) {
    echo "No cart ID provided.";
    exit;
}

$cart_id = intval($_GET['id']);

$stmt = $conn->prepare("DELETE FROM cart WHERE cart_id = ?");
$stmt->bind_param("i", $cart_id);

if ($stmt->execute()) {
    header("Location: ../index.php"); // change to your actual cart page
    exit;
} else {
    echo "Error deleting cart item: " . $stmt->error;
}
