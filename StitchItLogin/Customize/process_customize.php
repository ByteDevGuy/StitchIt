<?php
session_start();
include '../dbConnection/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product = $_POST['product'] ?? '';
    $customProduct = $_POST['customProduct'] ?? '';
    $size = $_POST['size'] ?? '';
    $color = $_POST['color'] ?? '';
    $notes = $_POST['notes'] ?? '';
    $user_id = $_SESSION['user_id'] ?? null; // Ensure user is logged in

    if (!$user_id) {
        header("Location: ../LoginSignup/LoginSignup.php?error=not_logged_in");
        exit();
    }

    // Use custom name if selected
    if ($product === 'custom' && !empty($customProduct)) {
        $productName = $customProduct;
    } else {
        $productName = $product;
    }

    // Handle image upload
    $imageContent = null;
    if (isset($_FILES['reference_image']) && $_FILES['reference_image']['error'] === UPLOAD_ERR_OK) {
        $fileTmp = $_FILES['reference_image']['tmp_name'];
        $fileType = mime_content_type($fileTmp);
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

        if (!in_array($fileType, $allowedTypes)) {
            header("Location: Customize.php?error=invalid_type");
            exit();
        }

        if ($_FILES['reference_image']['size'] > 2000000) {
            header("Location: Customize.php?error=file_too_large");
            exit();
        }

        $imageContent = file_get_contents($fileTmp);
    }

    // Determine category_id from product name (optional if you store it)
    $stmt = $conn->prepare("SELECT category_id FROM category WHERE category = ?");
    $stmt->bind_param("s", $product);
    $stmt->execute();
    $result = $stmt->get_result();
    $category_id = $result->fetch_assoc()['category_id'] ?? null;

    // Insert into customize_transaction table
    $stmt = $conn->prepare("INSERT INTO customize_transaction (customize_name, category_id, size, preference, image, message, user_id)
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sissssi", $productName, $category_id, $size, $color, $imageContent, $notes, $user_id);

    if ($stmt->execute()) {
        header("Location: customize.php?success=1");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
