<?php
include '../db_connect.php';

$id = $_GET['id'] ?? '';
if (!$id) {
    die("Missing ID");
}

$stmt = $conn->prepare("DELETE FROM sub_category WHERE sub_category_id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "<script>
        alert('Sub-Category deleted!');
        window.location.href = '../index.php';
    </script>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
