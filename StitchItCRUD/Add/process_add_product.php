<?php
include '../db_connect.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Get form inputs
$name = $_POST['product_name'] ?? '';
$desc = $_POST['description'] ?? '';
$stock = (int)($_POST['stock'] ?? 0);
$price = (float)($_POST['price'] ?? 0);
$category_id = (int)($_POST['category_id'] ?? 0);

// Validate required fields
if (!$name || !$desc || !$stock || !$price || !$category_id) {
    die("Missing or invalid input.");
}

// Image validation
if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    die("Image upload failed.");
}

$maxSize = 20 * 1024 * 1024; // 20MB
if ($_FILES['image']['size'] > $maxSize) {
    die("Image is too large. Maximum allowed size is 20MB.");
}

// Read image content
$imageData = file_get_contents($_FILES['image']['tmp_name']);

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO product (product_name, description, stock, price, category_id, image) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssidib", $name, $desc, $stock, $price, $category_id, $null);
$null = null; // required placeholder for blob
$stmt->send_long_data(5, $imageData); // 5 = index of image

if ($stmt->execute()) {
    echo "<script>
        alert('Product added successfully!');
        window.location.href = '../index.php';
    </script>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>