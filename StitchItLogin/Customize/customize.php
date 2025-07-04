<?php
session_start();
include '../dbConnection/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../LoginSignup/LoginSignup.php");
    exit();
}

$user_id = $_SESSION['user_id'];
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customize Your Crochet</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="Customize.css">
</head>
<body>

<header>
  <div class="logo">
    <img src="../Pictures/logo.png" alt="Stitch It Logo">
  </div>
  <nav>
    <a href="../index.php">Home</a>
    <a href="../Shop/Shop.php">Shop</a>
    <a href="../Customize/Customize.php" class="active">Customize</a>
    <a href="../Cart/Cart.php">Cart</a>

    <?php if (isset($_SESSION['username'])): ?>
      <a href="../Profile/userprofile.php">Profile</a>
      <a href="../LoginSignup/Logout.php" onclick="confirmLogout(event)">Logout</a>
    <?php else: ?>
      <a href="../LoginSignup/LoginSignup.php">Login / Sign Up</a>
    <?php endif; ?>
  </nav>
</header>

<div class="form-container">
    <h1>Customize Your Order</h1>
    <form method="POST" enctype="multipart/form-data" action="process_customize.php">

        <label for="product">Select Product</label>
        <select name="product" id="product" required onchange="toggleCustomNameField()">
            <option value="">-- Choose One --</option>
            <option value="T-Shirts">Crochet T-Shirts</option>
            <option value="Shirts">Crochet Shirts</option>
            <option value="Dresses">Crochet Dresses</option>
            <option value="Skirts">Crochet Mini Skirts</option>
            <option value="Beanies">Crochet Beanies</option>
            <option value="Bags">Crochet Tote Bag</option>
            <option value="Accessories">Crochet Accessories</option>
            <option value="Pants">Crochet Pants</option>
            <option value="Swimwear">Crochet Swimwear</option>
            <option value="Hats">Crochet Bucket Hat</option>
            <option value="custom">Custom Name Order</option>
        </select>

        <div id="customNameDiv" style="display: none;">
            <label for="customProduct">Custom Order Name</label>
            <input type="text" id="customProduct" name="customProduct" placeholder="e.g. Crochet Stuff">
        </div>

        <label for="size">Size</label>
        <select name="size" id="size" required>
            <option value="Extra Small">XS (Extra Small)</option>
            <option value="Small">S (Small)</option>
            <option value="Medium">M (Medium)</option>
            <option value="Large">L (Large)</option>
            <option value="Extra Large">XL (Extra Large)</option>
        </select>

        <label for="color">Preferred Color(s)</label>
        <input type="text" name="color" id="color" placeholder="e.g. pastel pink, sky blue" required>

        <label for="reference_image">Upload Reference Image (optional)</label>
        <input type="file" name="reference_image" id="reference_image" accept="image/*">
        <img id="preview" src="" alt="Image Preview">

        <label for="notes">Special Instructions</label>
        <textarea name="notes" id="notes" placeholder="Add measurements, strap style, or design requests here..."></textarea>

        <button type="submit" name="submit_request">Submit</button>
    </form>
</div>

<footer>
    &copy; 2025 Crochet Clothing Shop. Made with passion and creativity 🧶✨
</footer>


<script src="Customize.js" defer></script>
</body>
</html>
