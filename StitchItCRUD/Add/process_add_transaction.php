<?php
/*
include 'db_connect.php';

$cart_id = $_POST['cart_id'];
$user_id = $_POST['user_id'];

// Get product_id, quantity, price from cart and product
$sql = "SELECT c.product_id, c.quantity, p.price 
        FROM cart c 
        JOIN product p ON c.product_id = p.product_id 
        WHERE c.cart_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cart_id);
$stmt->execute();
$stmt->bind_result($product_id, $quantity, $price);
$stmt->fetch();
$stmt->close();

$total_price = $price * $quantity;

// Insert into transactions
$stmt = $conn->prepare("INSERT INTO transaction (user_id, product_id, quantity, total_price) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iiid", $user_id, $product_id, $quantity, $total_price);

if ($stmt->execute()) {
    echo "Transaction added successfully.";
    // Optionally delete from cart
    $conn->query("DELETE FROM cart WHERE cart_id = $cart_id");
} else {
    echo "Error: " . $stmt->error;
}
$stmt->close();
$conn->close();
*/
?>
