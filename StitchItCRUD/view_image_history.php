<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $conn->prepare("SELECT product_image FROM history WHERE history_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($image);
    $stmt->fetch();
    $stmt->close();

    if ($image) {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime_type = $finfo->buffer($image);

        // Set proper content type
        header("Content-Type: $mime_type");
        echo $image;
        exit;
    }
}

http_response_code(404);
echo "Image not found.";
?>
