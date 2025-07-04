<?php
include '../db_connect.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['sub_category_id'] ?? '';
    $name = $_POST['sub_category_name'] ?? '';
    $cat = $_POST['category_id'] ?? '';

    if (!$id || !$name || !$cat) {
        die("Invalid input.");
    }

    $stmt = $conn->prepare("UPDATE sub_category SET sub_category_name = ?, category_id = ? WHERE sub_category_id = ?");
    $stmt->bind_param("sii", $name, $cat, $id);

    if ($stmt->execute()) {
        echo "<script>
            alert('Sub-Category updated successfully!');
            window.location.href = '../index.php';
        </script>";
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    exit;
}

// Display the edit form
$id = $_GET['id'] ?? '';
if (!$id) {
    die("Missing Sub-Category ID.");
}

$result = $conn->query("SELECT * FROM sub_category WHERE sub_category_id = $id");
if (!$result || $result->num_rows === 0) {
    die("Sub-Category not found.");
}
$row = $result->fetch_assoc();
?>

<h2>Edit Sub-Category</h2>
<form method="post" enctype="multipart/form-data">
    <input type="hidden" name="sub_category_id" value="<?= $row['sub_category_id'] ?>">
    
    <input type="text" name="sub_category_name" value="<?= htmlspecialchars($row['sub_category_name']) ?>" required>

    <select name="category_id" required>
        <?php
        $cats = $conn->query("SELECT * FROM category");
        while ($cat = $cats->fetch_assoc()) {
            $selected = ($cat['category_id'] == $row['category_id']) ? 'selected' : '';
            echo "<option value='{$cat['category_id']}' $selected>" . htmlspecialchars($cat['category']) . "</option>";
        }
        ?>
    </select>

    <input type="submit" value="Update Sub-Category">
</form>
