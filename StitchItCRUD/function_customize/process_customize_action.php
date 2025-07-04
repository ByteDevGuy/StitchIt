<?php
include '../../dbConnection/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customize_id = $_POST['customize_id'];
    $action = $_POST['action']; // Expected: "Approve" or "Decline"

    // Fetch from customize_transaction
    $stmt = $conn->prepare("SELECT customize_name, size, preference, message, image, user_id FROM customize_transaction WHERE customize_id = ?");
    $stmt->bind_param("i", $customize_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if ($data) {
        $imageData = $data['image'];
        $status = ($action === "Approve") ? "Approved" : "Declined";
        $type = "customized";

        // Insert into history
        $insert = $conn->prepare("
            INSERT INTO history (product_name, size, message, product_image, status, type, user_id)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $insert->bind_param(
            "ssssssi",
            $data['customize_name'],
            $data['size'],
            $data['message'],
            $imageData,
            $status,
            $type,
            $data['user_id']
        );
        $insert->execute();

        // Optionally, delete from customize_transaction
        $delete = $conn->prepare("DELETE FROM customize_transaction WHERE customize_id = ?");
        $delete->bind_param("i", $customize_id);
        $delete->execute();

        header("Location: ../admin_dashboard.php?success=true");
        exit;
    }
}
?>