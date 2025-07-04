<?php
include '../db_connect.php';

if (!isset($_GET['id'])) {
    die("Missing cart ID.");
}

$cart_id = intval($_GET['id']);

// Fetch cart and product data
$sql = "
    SELECT c.user_id, c.quantity, c.size, p.price 
    FROM cart c 
    JOIN product p ON c.product_id = p.product_id 
    WHERE c.cart_id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cart_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $user_id = $row['user_id'];
    $quantity = $row['quantity'];
    $size = $row['size'];
    $price_per_unit = $row['price'];
    $total_price = $quantity * $price_per_unit;
    $type = 'normal';
    $status = 'PENDING';

    // Insert into history
    $insert = $conn->prepare("INSERT INTO history 
        (user_id, quantity, size, type, status, date_of_purchase, price_per_unit, total_price)
        VALUES (?, ?, ?, ?, ?, CURDATE(), ?, ?)");
    $insert->bind_param("issssdd", $user_id, $quantity, $size, $type, $status, $price_per_unit, $total_price);
    $insert->execute();

    // Delete from cart
    $delete = $conn->prepare("DELETE FROM cart WHERE cart_id = ?");
    $delete->bind_param("i", $cart_id);
    $delete->execute();

    header("Location: ../index.php?checkout=success");
    exit;
} else {
    echo "Cart item not found.";
}
?>
