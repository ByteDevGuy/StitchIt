<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT image FROM customize_transaction WHERE customize_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($imageData);
        $stmt->fetch();

        // Detect MIME type
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->buffer($imageData);

        header("Content-Type: $mimeType");
        echo $imageData;
    } else {
        http_response_code(404);
        echo "Image not found.";
    }

    $stmt->close();
} else {
    http_response_code(400);
    echo "No image ID specified.";
}
?>
