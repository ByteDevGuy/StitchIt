<?php
include '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customize_id = (int)$_POST['customize_id'];
    $action = $_POST['action'];
    $status = strtoupper($action); // APPROVED or DECLINED
    $type = 'customized';

    // 1. Fetch customize_transaction
    $stmt = $conn->prepare("SELECT * FROM customize_transaction WHERE customize_id = ? AND (status IS NULL OR status = 'PENDING')");
    if (!$stmt) die("Prepare failed: " . $conn->error);
    $stmt->bind_param("i", $customize_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $customize = $result->fetch_assoc();
    $stmt->close();

    if ($customize) {
        $user_id = $customize['user_id'];
        $image = $customize['image']; // This must be raw image data (BLOB)
        $price = 0.00;
        $quantity = 1;
        $total_price = $price * $quantity;

        // 2. Insert into history
        $insert = $conn->prepare("INSERT INTO history (
            user_id, customize_id, product_id, product_image,
            price_per_unit, quantity, total_price, date_of_purchase,
            status, type
        ) VALUES (?, ?, NULL, ?, ?, ?, ?, NOW(), ?, ?)");

        if (!$insert) die("Insert prepare failed: " . $conn->error);

        $null_blob = null;
        $insert->bind_param("iibddsss",
            $user_id,
            $customize_id,
            $null_blob,
            $price,
            $quantity,
            $total_price,
            $status,
            $type
        );
        $insert->send_long_data(2, $image); // Index 2 = product_image (3rd bind param)
        $insert->execute();
        $insert->close();

        // 3. Update customize_transaction status
        $update = $conn->prepare("UPDATE customize_transaction SET status = ? WHERE customize_id = ?");
        if (!$update) die("Update prepare failed: " . $conn->error);
        $update->bind_param("si", $status, $customize_id);
        $update->execute();
        $update->close();

        // 4. Redirect
        header("Location: ../index.php");
        exit;

    } else {
        // Entry not found or already processed
        die("❌ Already processed");
    }
}
?>
