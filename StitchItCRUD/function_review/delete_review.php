<?php
include '../db_connect.php';

if (!isset($_GET['id'])) {
    die("Missing review ID.");
}

$review_id = intval($_GET['id']);

// Prepare delete query
$stmt = $conn->prepare("DELETE FROM review WHERE review_id = ?");
$stmt->bind_param("i", $review_id);

if ($stmt->execute()) {
    echo "<script>
        alert('Review deleted successfully!');
        window.location.href = '../index.php'; // Adjust to your dashboard or review table location
    </script>";
} else {
    echo "Error deleting review: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
