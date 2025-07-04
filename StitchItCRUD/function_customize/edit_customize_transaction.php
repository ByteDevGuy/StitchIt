<?php
include '../db_connect.php';

if (!isset($_GET['id'])) {
    die("Customize Transaction ID not specified.");
}

$customize_id = intval($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $_POST['message'];
    $user_id = intval($_POST['user_id']);

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $imageData = file_get_contents($_FILES['image']['tmp_name']);

    $stmt = $conn->prepare("UPDATE customize_transaction SET message = ?, user_id = ?, image = ? WHERE customize_id = ?");
    $null = NULL;
    $stmt->bind_param("sibi", $message, $user_id, $null, $customize_id);
    $stmt->send_long_data(2, $imageData);
    } else {
    $stmt = $conn->prepare("UPDATE customize_transaction SET message = ?, user_id = ? WHERE customize_id = ?");
    $stmt->bind_param("sii", $message, $user_id, $customize_id);
    }


    if ($stmt->execute()) {
        header("Location: ../index.php");
        exit();
    } else {
        echo "Error updating customize transaction: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch current data
$stmt = $conn->prepare("SELECT message, user_id FROM customize_transaction WHERE customize_id = ?");
$stmt->bind_param("i", $customize_id);
$stmt->execute();
$stmt->bind_result($message, $user_id);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head><title>Edit Customize Transaction</title></head>
<body>
<h2>Edit Customize Transaction #<?php echo $customize_id; ?></h2>
<form method="post" enctype="multipart/form-data">
    <textarea name="message" rows="4" cols="40"><?php echo htmlspecialchars($message); ?></textarea><br>

    <label for="user_id">User:</label><br>
    <select name="user_id" id="user_id" required>
        <option value="">-- Select User --</option>
        <?php
        $user_result = $conn->query("SELECT user_id, username FROM users");
        while ($user = $user_result->fetch_assoc()) {
            $selected = ($user['user_id'] == $user_id) ? "selected" : "";
            echo "<option value='{$user['user_id']}' $selected>" . htmlspecialchars($user['username']) . "</option>";
        }
        ?>
    </select><br>

    <label for="image">Replace Image (optional):</label><br>
    <input type="file" name="image" accept="image/*"><br><br>

    <input type="submit" value="Update Customize Transaction">
</form>
</body>
</html>
