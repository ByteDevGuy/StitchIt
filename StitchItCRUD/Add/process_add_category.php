<?php
include '../db_connect.php';

$category = $_POST['category'];
$stmt = $conn->prepare("INSERT INTO category (category) VALUES (?)");
$stmt->bind_param("s", $category);

if ($stmt->execute()) {
    echo    "<script>
                alert('Product added to cart successfully!');
                window.location.href = '../index.php';
            </script>";
        exit();
}
else echo "Error: " . $stmt->error;

$stmt->close();
$conn->close();
?>