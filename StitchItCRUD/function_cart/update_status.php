<?php
include dirname(__DIR__) . '/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $history_id = $_POST['history_id'];
    $new_status = $_POST['status'];

    if (!$conn) {
        die("❌ Database connection failed.");
    }

    // Step 1: Get the history details
    $stmt = $conn->prepare("
        SELECT status, stock_deducted, type, product_id, quantity 
        FROM history 
        WHERE history_id = ?
    ");
    $stmt->bind_param("i", $history_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $history = $result->fetch_assoc();
    $stmt->close();

    if ($history) {
        $stock_deducted = $history['stock_deducted'];
        $type = $history['type'];
        $product_id = $history['product_id'];
        $quantity = $history['quantity'];

        // Step 2: Build the base update query
        if ($new_status === 'COMPLETED') {
            // Include date and time
            $update = $conn->prepare("UPDATE history SET status = ?, date_of_arrival = NOW() WHERE history_id = ?");
            $update->bind_param("si", $new_status, $history_id);
        } else {
            $update = $conn->prepare("UPDATE history SET status = ? WHERE history_id = ?");
            $update->bind_param("si", $new_status, $history_id);
        }

        if (!$update->execute()) {
            die("❌ Status update failed: " . $update->error);
        }
        $update->close();

        // Step 3: Deduct stock (only once)
        if (
            in_array($new_status, ['SHIPPING', 'COMPLETED']) &&
            $stock_deducted == 0 &&
            $type === 'normal' &&
            $product_id !== null
        ) {
            // Check current stock
            $check_stock = $conn->prepare("SELECT stock FROM product WHERE product_id = ?");
            $check_stock->bind_param("i", $product_id);
            $check_stock->execute();
            $stock_result = $check_stock->get_result();
            $current_stock = $stock_result->fetch_assoc()['stock'] ?? 0;
            $check_stock->close();

            if ($current_stock >= $quantity) {
                // Deduct stock
                $deduct = $conn->prepare("UPDATE product SET stock = stock - ? WHERE product_id = ?");
                $deduct->bind_param("ii", $quantity, $product_id);
                $deduct->execute();
                $deduct->close();

                // Mark stock as deducted
                $mark = $conn->prepare("UPDATE history SET stock_deducted = 1 WHERE history_id = ?");
                $mark->bind_param("i", $history_id);
                $mark->execute();
                $mark->close();
            } else {
                error_log("❗ Not enough stock for product_id: $product_id");
            }
        }

        // ✅ Redirect after successful update
        header("Location: ../index.php");
        exit;

    } else {
        die("❌ History record not found.");
    }
}
?>
