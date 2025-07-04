<?php
include '../db_connect.php';

if (!isset($_GET['id'])) {
    die("Category ID not specified.");
}

$category_id = intval($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = $_POST['category'];

    $stmt = $conn->prepare("UPDATE category SET category = ? WHERE category_id = ?");
    $stmt->bind_param("si", $category, $category_id);

    if ($stmt->execute()) {
        header("Location: ../index.php");
        exit();
    } else {
        echo "Error updating category: " . $stmt->error;
    }
    $stmt->close();
}

$stmt = $conn->prepare("SELECT category FROM category WHERE category_id = ?");
$stmt->bind_param("i", $category_id);
$stmt->execute();
$stmt->bind_result($category);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Category</title>
    <style>
        body {
            background-color: #fff0f7;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .form-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 20px rgba(245, 196, 228, 0.3);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            color: #d772b6;
            margin-bottom: 25px;
        }

        input[type="text"] {
            width: 100%;
            padding: 12px 15px;
            margin: 15px 0;
            border: 1px solid #eac6db;
            border-radius: 10px;
            background-color: #fff5fb;
            box-sizing: border-box;
            transition: 0.3s;
        }

        input[type="text"]:focus {
            border-color: #f5c4e4;
            outline: none;
            box-shadow: 0 0 6px rgba(245, 196, 228, 0.4);
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
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #e3a6c9;
        }

        .back-btn {
    display: inline-block;
    text-align: center;
    margin-top: 15px;
    padding: 10px;
    width: 95%;
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
        <h2>Edit Category #<?php echo $category_id; ?></h2>
        <form method="post">
            <input type="text" name="category" value="<?php echo htmlspecialchars($category); ?>" required placeholder="Category Name">
            <input type="submit" value="Update Category">
        </form>

        <a href="../index.php" class="back-btn">← Back to Categories</a>
    </div>
</body>
</html>
