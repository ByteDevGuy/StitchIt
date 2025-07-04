<?php
session_start();
include '../dbConnection/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../LoginSignup/LoginSignup.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT cart.cart_id, product.product_name, cart.quantity, cart.size, product.price, product.image 
        FROM cart 
        JOIN product ON cart.product_id = product.product_id 
        WHERE cart.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Cart</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="Cart.css">
</head>
<body>

<header>
  <div class="logo">
    <img src="../Pictures/logo.png" alt="Stitch It Logo">
  </div>
  <nav>
    <a href="../index.php">Home</a>
    <a href="../Shop/Shop.php">Shop</a>
    <a href="../Customize/Customize.php">Customize</a>
    <a href="../Cart/Cart.php" class="active">Cart</a>

    <?php if (isset($_SESSION['username'])): ?>
      <a href="../Profile/userprofile.php">Profile</a>
      <a href="../LoginSignup/Logout.php" onclick="confirmLogout(event)">Logout</a>
    <?php else: ?>
      <a href="../LoginSignup/LoginSignup.php">Login / Sign Up</a>
    <?php endif; ?>
  </nav>
</header>

<div class="container">
    <h2>My Cart</h2>

    <?php if ($result->num_rows > 0): ?>
    <form method="post" action="Checkout.php">
        <table>
            <thead>
                <tr>
                    <th>Select</th>
                    <th>Product</th>
                    <th>Image</th>
                    <th>Quantity</th>
                    <th>Size</th>
                    <th>Price per Unit (₱)</th>
                    <th>Total Price (₱)</th>
                </tr>
            </thead>

            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <?php $item_total = $row['price'] * $row['quantity']; ?>
                    <tr>
                        <td><input type="checkbox" name="selected_items[]" value="<?= $row['cart_id'] ?>"></td>
                        <td><?= htmlspecialchars($row['product_name']) ?></td>
                        <td>
                            <?php if (!empty($row['image'])): ?>
                                <img src="data:image/jpeg;base64,<?= base64_encode($row['image']) ?>" 
                                    style="width:50px;height:50px;cursor:pointer;"
                                    onclick="openModal('data:image/jpeg;base64,<?= base64_encode($row['image']) ?>')">
                            <?php else: ?>
                                No image
                            <?php endif; ?>
                        </td>

                        <td><?= $row['quantity'] ?></td>
                        <td><?= $row['size'] ?></td>
                        <td><?= number_format($row['price'], 2) ?></td>
                        <td><?= number_format($item_total, 2) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <button class="checkout-btn" type="submit" name="checkout">Checkout</button>
    </form>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>

    <?php
        if (isset($_GET['success'])) echo "<p style='color:green'>{$_GET['success']}</p>";
        if (isset($_GET['error'])) echo "<p style='color:red'>{$_GET['error']}</p>";
    ?>
</div>

<div id="imageModal" class="modal">
  <span class="close" onclick="closeModal()">&times;</span>
  <img class="modal-content" id="modalImage">
</div>

<script src="image.js" defer></script>
</body>
</html>