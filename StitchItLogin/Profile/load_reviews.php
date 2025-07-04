<?php
include '../dbConnection/db_connect.php';

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT 
        r.review_id,
        r.message, 
        r.rating, 
        r.review_date AS date, 
        CONCAT(u.fn, ' ', u.ln) AS name,
        p.product_name AS product,
        p.image
    FROM review r
    JOIN users u ON r.user_id = u.user_id
    JOIN product p ON r.product_id = p.product_id
    WHERE r.user_id = ?
    ORDER BY r.review_date DESC
");


$stmt->bind_param("i", $user_id);
$stmt->execute();

// Use bind_result instead of get_result
$stmt->bind_result($review_id, $message, $rating, $date, $name, $product, $image);


$reviews = [];

while ($stmt->fetch()) {
    $reviews[] = [
    'review_id' => $review_id,
    'message' => $message,
    'rating' => $rating,
    'date' => $date,
    'name' => $name,
    'product' => $product,
    'image' => 'data:image/jpeg;base64,' . base64_encode($image)
    ];
}

$stmt->close();
?>
