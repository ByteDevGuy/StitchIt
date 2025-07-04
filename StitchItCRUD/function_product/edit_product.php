<?php
include '../db_connect.php';

if (!isset($_GET['id'])) {
    die("Product ID not specified.");
}

$product_id = intval($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $stock = intval($_POST['stock']);
    $price = floatval($_POST['price']);
    $category_id = intval($_POST['category_id']);

    if (!empty($_FILES['image']['tmp_name'])) {
        $imageData = file_get_contents($_FILES['image']['tmp_name']);

        $stmt = $conn->prepare("UPDATE product SET product_name=?, description=?, stock=?, price=?, category_id=?, image=? WHERE product_id=?");
        $stmt->bind_param("ssidisi", $name, $description, $stock, $price, $category_id, $imageData, $product_id);
    } else {
        $stmt = $conn->prepare("UPDATE product SET product_name=?, description=?, stock=?, price=?, category_id=? WHERE product_id=?");
        $stmt->bind_param("ssidii", $name, $description, $stock, $price, $category_id, $product_id);
    }

    if ($stmt->execute()) {
        header("Location: ../index.php");
        exit();
    } else {
        echo "Error updating product: " . $stmt->error;
    }
    $stmt->close();
}

$stmt = $conn->prepare("SELECT product_name, description, stock, price, category_id, image FROM product WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($name, $description, $stock, $price, $category_id, $image);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <style>
        body {
            background-color: #fff0f7;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .form-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 20px rgba(245, 196, 228, 0.4);
            width: 100%;
            max-width: 500px;
        }

        h2 {
            text-align: center;
            color: #d772b6;
            margin-bottom: 25px;
        }

        input[type="text"],
        input[type="number"],
        select,
        input[type="file"] {
            width: 100%;
            padding: 10px 14px;
            margin: 10px 0;
            border: 1px solid #e8cde0;
            border-radius: 10px;
            background-color: #fff5fb;
            box-sizing: border-box;
            transition: 0.3s;
        }

        input[type="text"]:focus,
        input[type="number"]:focus,
        select:focus {
            border-color: #f5c4e4;
            outline: none;
            box-shadow: 0 0 5px rgba(245, 196, 228, 0.5);
        }

        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #f5c4e4;
            border: none;
            border-radius: 12px;
            color: white;
            font-weight: bold;
            cursor: pointer;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #e3a6c9;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: 500;
            color: #b8579c;
        }

        .product-image {
            margin-top: 10px;
            display: block;
            max-width: 120px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .back-btn {
    display: inline-block;
    text-align: center;
    margin-top: 15px;
    padding: 10px;
    width: 96%;
    background-color: #f5c4e4;
    color: #fff;
    border-radius: 12px;
    text-decoration: none;
    font-weight: bold;
    transition: background-color 0.3s ease;
}

.back-btn:hover {
    background-color: #e3a6c9;
}

    </style>
</head>
<body>
    <div class="form-container">
        <h2>Edit Product "<?php echo htmlspecialchars($name) ?>"</h2>
        <form method="post" enctype="multipart/form-data">
            <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" placeholder="Product Name" required>
            <input type="text" name="description" value="<?php echo htmlspecialchars($description); ?>" placeholder="Description" required>
            <input type="number" name="stock" value="<?php echo $stock; ?>" placeholder="Stock" required>
            <input type="number" step="0.01" name="price" value="<?php echo $price; ?>" placeholder="Price" required>

            <label for="category_id">Category:</label>
            <select name="category_id" id="category_id" required>
                <option value="">-- Select Category --</option>
                <?php
                $cat_result = $conn->query("SELECT category_id, category FROM category");
                while ($cat = $cat_result->fetch_assoc()) {
                    $selected = ($cat['category_id'] == $category_id) ? "selected" : "";
                    echo "<option value='{$cat['category_id']}' $selected>" . htmlspecialchars($cat['category']) . "</option>";
                }
                ?>
            </select>

            <label>Current Image:</label>
            <?php
            if (!empty($image)) {
                $encoded = base64_encode($image);
                echo "<img src='data:image/jpeg;base64,{$encoded}' class='product-image'>";
            } else {
                echo "<p style='color:#888;'>No image uploaded.</p>";
            }
            ?>

            <label>Upload New Image:</label>
            <input type="file" name="image" accept="image/*">

            <input type="submit" value="Update Product">
        </form>

        <a href="../index.php" class="back-btn">← Back to Product List</a>

    </div>
</body>
</html>
