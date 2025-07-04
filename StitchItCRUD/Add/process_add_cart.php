<?php
include '../db_connect.php';

// Validate inputs
$quantity = intval($_POST['quantity'] ?? 0);
$user_id = intval($_POST['user_id'] ?? 0);
$product_id = intval($_POST['product_id'] ?? 0);
$size = $_POST['size'] ?? '';

if (!$quantity || !$user_id || !$product_id || !$size) {
    die("Missing required fields.");
}

// Check if the same product with same size is already in cart
$checkStmt = $conn->prepare("SELECT cart_id, quantity FROM cart WHERE user_id = ? AND product_id = ? AND size = ?");
$checkStmt->bind_param("iis", $user_id, $product_id, $size);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($existing = $checkResult->fetch_assoc()) {
    // Update quantity if exists
    $newQuantity = $existing['quantity'] + $quantity;
    $updateStmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE cart_id = ?");
    $updateStmt->bind_param("ii", $newQuantity, $existing['cart_id']);
    $updateStmt->execute();
    $updateStmt->close();
} else {
    // Otherwise, insert new cart item
    $insertStmt = $conn->prepare("INSERT INTO cart (quantity, size, user_id, product_id) VALUES (?, ?, ?, ?)");
    $insertStmt->bind_param("isii", $quantity, $size, $user_id, $product_id);
    $insertStmt->execute();
    $insertStmt->close();
}

$checkStmt->close();
$conn->close();

// Redirect with success alert
echo "<script>
    alert('Cart updated successfully!');
    window.location.href = '../index.php';
</script>";
?>
