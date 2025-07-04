<?php
include '../dbConnection/db_connect.php';

$user_id = $_SESSION['user_id'];

$sql = "SELECT 
            h.*,
            COALESCE(p.product_name, c.customize_name) AS product,
            COALESCE(p.image, c.image) AS image,
            h.price_per_unit,
            h.total_price,
            (SELECT r.review_id FROM review r WHERE r.history_id = h.history_id LIMIT 1) AS already_reviewed
        FROM history h
        LEFT JOIN product p ON h.product_id = p.product_id
        LEFT JOIN customize_transaction c ON h.customize_id = c.customize_id
        WHERE h.user_id = ?
        ORDER BY h.date_of_purchase DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$all_orders = [];

while ($row = $result->fetch_assoc()) {
    // 🖼 Convert image to base64 if image is binary (LONGBLOB)
    if (!empty($row['image'])) {
        $row['image'] = 'data:image/jpeg;base64,' . base64_encode($row['image']);
    } else {
        $row['image'] = 'assets/default-product.jpg';
    }

    // ✅ Check if the user already reviewed this product
    $review_count = 0;
    if (!empty($row['product_id'])) {
        $checkStmt = $conn->prepare("SELECT COUNT(*) FROM review WHERE user_id = ? AND product_id = ?");
        $checkStmt->bind_param("ii", $user_id, $row['product_id']);
        $checkStmt->execute();
        $checkStmt->bind_result($review_count);
        $checkStmt->fetch();
        $checkStmt->close();
    }

    $row['already_reviewed'] = $review_count > 0;

    // Add the updated row to the final list
    $all_orders[] = $row;
}

$stmt->close();
?>
