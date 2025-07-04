<?php
/*
include '../db_connect.php';

$sub_category_name = $_POST['sub_category_name'] ?? '';
$category_id = $_POST['category_id'] ?? '';

if (!$sub_category_name || !$category_id) {
    die("Missing input.");
}

$stmt = $conn->prepare("INSERT INTO sub_category (sub_category_name, category_id) VALUES (?, ?)");
$stmt->bind_param("si", $sub_category_name, $category_id);

if ($stmt->execute()) {
    echo "<script>
        alert('Sub-Category added successfully!');
        window.location.href = '../index.php';
    </script>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
*/