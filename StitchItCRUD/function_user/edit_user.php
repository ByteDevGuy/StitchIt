<?php
include '../db_connect.php';

if (!isset($_GET['id'])) {
    die("User ID not specified.");
}

$user_id = intval($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fn = $_POST['fn'];
    $ln = $_POST['ln'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $number = $_POST['number'];
    $address = $_POST['address'];

    $stmt = $conn->prepare("UPDATE users SET fn=?, ln=?, email=?, username=?, number=?, address=? WHERE user_id=?");
    $stmt->bind_param("ssssssi", $fn, $ln, $email, $username, $number, $address, $user_id);

    if ($stmt->execute()) {
        header("Location: ../index.php");
        exit();
    } else {
        echo "Error updating user: " . $stmt->error;
    }
    $stmt->close();
}

$stmt = $conn->prepare("SELECT fn, ln, email, username, number, address FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($fn, $ln, $email, $username, $number, $address);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <style>
        body {
            background-color: #fff0f7;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 20px rgba(245, 196, 228, 0.4);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            color: #d772b6;
            margin-bottom: 25px;
        }

        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 10px 14px;
            margin: 10px 0;
            border: 1px solid #e8cde0;
            border-radius: 10px;
            box-sizing: border-box;
            background-color: #fff5fb;
            transition: 0.3s;
        }

        input[type="text"]:focus,
        input[type="email"]:focus {
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
            margin-top: 15px;
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
        <h2>Edit User #<?php echo $user_id; ?></h2>
        <form method="post">
            <input type="text" name="fn" value="<?php echo htmlspecialchars($fn); ?>" placeholder="First Name" required>
            <input type="text" name="ln" value="<?php echo htmlspecialchars($ln); ?>" placeholder="Last Name" required>
            <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="Email" required>
            <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" placeholder="Username" required>
            <input type="text" name="number" value="<?php echo htmlspecialchars($number); ?>" placeholder="Phone Number">
            <input type="text" name="address" value="<?php echo htmlspecialchars($address); ?>" placeholder="Address">
            <input type="submit" value="Update User">
        </form>

        <a href="../index.php" class="back-btn">← Back to User List</a>
    </div>
</body>
</html>
