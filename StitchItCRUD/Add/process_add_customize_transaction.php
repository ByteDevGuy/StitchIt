<?php
/*include '../db_connect.php';

if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
    $message = $_POST['message'];
    $user_id = $_POST['user_id'];
    $imageData = file_get_contents($_FILES['image']['tmp_name']);

    $stmt = $conn->prepare("INSERT INTO customize_transaction (message, image, user_id) VALUES (?, ?, ?)");
    $stmt->bind_param("sbi", $message, $null, $user_id); // 'b' for blob

    $stmt->send_long_data(1, $imageData);

    if ($stmt->execute()) {
        echo    "<script>
                    alert('Product added to cart successfully!');
                    window.location.href = '../index.php';
            </script>";
        exit();
    }
    else echo "Error: " . $stmt->error;

    $stmt->close();
} else {
    echo "Image upload failed.";
}

$conn->close();*/
?>
