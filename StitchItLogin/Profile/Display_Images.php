<?php
include '../dbConnection/db_connect.php';

if (!isset($_GET['id'])) {
    http_response_code(400);
    exit('Missing product ID');
}

$product_id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT image FROM product WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($image);
    $stmt->fetch();

    // Detect image type (optional fallback to JPEG)
    $finfo = finfo_open();
    $mime = finfo_buffer($finfo, $image, FILEINFO_MIME_TYPE);
    finfo_close($finfo);

    header("Content-Type: $mime");
    echo $image;
} else {
    http_response_code(404);
    exit('Image not found');
}

$stmt->close();
$conn->close();
?>