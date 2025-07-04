<?php
session_start();
header('Content-Type: application/json');
include '../dbConnection/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Please log in first.']);
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'];
$size = $_POST['size'];
$quantity = $_POST['quantity'];

// Check if item already exists
$stmt = $conn->prepare("SELECT cart_id, quantity FROM cart WHERE user_id = ? AND product_id = ? AND size = ?");
$stmt->bind_param("iis", $user_id, $product_id, $size);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $new_quantity = $row['quantity'] + $quantity;
    $update_stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE cart_id = ?");
    $update_stmt->bind_param("ii", $new_quantity, $row['cart_id']);
    $update_stmt->execute();
    $update_stmt->close();
} else {
    $insert_stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, size, quantity) VALUES (?, ?, ?, ?)");
    $insert_stmt->bind_param("iisi", $user_id, $product_id, $size, $quantity);
    $insert_stmt->execute();
    $insert_stmt->close();
}

$stmt->close();
$conn->close();

echo json_encode(['status' => 'success', 'message' => 'Added to cart successfully!']);
?>
