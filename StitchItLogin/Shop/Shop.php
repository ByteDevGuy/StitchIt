<?php
session_start();
include '../dbConnection/db_connect.php';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? trim(strtolower($_GET['category'])) : '';

$sql = "
    SELECT p.*, c.category
    FROM product p
    JOIN category c ON p.category_id = c.category_id
";
$params = [];
$types = "";

// Add category filter if provided
if (!empty($category)) {
    $sql .= " AND LOWER(c.category) = ?";
    $types .= "s";
    $params[] = $category;
}

// Add search filter if provided
if (!empty($search)) {
    $sql .= " AND p.product_name LIKE ?";
    $types .= "s";
    $params[] = "%$search%";
}

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Home</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="Shop.css">
</head>
<body>

<header>
  <div class="logo">
    <img src="../Pictures/logo.png" alt="Stitch It Logo">
  </div>
  <nav>
    <a href="../index.php">Home</a>
    <a href="../Shop/Shop.php" class="active">Shop</a>
    <a href="../Customize/Customize.php">Customize</a>
    <a href="../Cart/Cart.php">Cart</a>

    <?php if (isset($_SESSION['username'])): ?>
      <a href="../Profile/userprofile.php">Profile</a>
      <a href="../LoginSignup/Logout.php" onclick="confirmLogout(event)">Logout</a>
    <?php else: ?>
      <a href="../LoginSignup/LoginSignup.php">Login / Sign Up</a>
    <?php endif; ?>
  </nav>
</header>

<div class="filter-buttons" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; padding: 10px;">

  <div class="category-links" style="display: flex; flex-wrap: wrap; gap: 10px;">
    <?php

      $category = isset($_GET['category']) ? strtolower($_GET['category']) : '';

      $catQuery = "SELECT category FROM category ORDER BY category_id ASC";
      $catResult = $conn->query($catQuery);

      if ($catResult && $catResult->num_rows > 0) {
        while ($row = $catResult->fetch_assoc()) {
          $cat = strtolower($row['category']);
          $label = ucwords(str_replace('-', ' ', $cat));
          $selected = ($category === $cat) ? 'selected' : '';
          $link = ($category === $cat) ? 'index.php' : "index.php?category=$cat";
          echo "<a href='$link' data-category='$cat' class='$selected' style='padding: 6px 10px; text-decoration: none; border: 1px solid #ccc; border-radius: 5px;'>$label</a>";
        }
      } else {
        echo "<p>No categories found.</p>";
      }
    ?>
  </div>

  <form action="Shop.php" method="GET" style="display: flex; gap: 5px; align-items: center;">
    <input type="text" name="search" placeholder="Search products..." style="padding: 6px 10px; border: 1px solid #ccc; border-radius: 4px;">
    <button type="submit" class="searchbtn">Search</button>
  </form>

</div>

<div class="products">
  <?php
    if ($result && $result->num_rows > 0) {
      while ($product = $result->fetch_assoc()) {
        echo "<form class='add-to-cart-form' method='POST' action='AddToCart.php'>";
        echo "<div class='product' data-category='" . strtolower($product['category']) . "'>";
        echo "<div class='product-image'>";
        echo "<img class='clickable-image' src='Display_Images.php?id={$product['product_id']}' alt='Product Image' />";
        echo "</div>";

        echo "<div class='product-details'>";
        echo "<input type='hidden' name='product_id' value='{$product['product_id']}'>";
        echo "<input type='hidden' name='price' value='{$product['price']}'>";
        echo "<input type='hidden' name='product_name' value='" . htmlspecialchars($product['product_name']) . "'>";
        echo "<input type='hidden' name='category' value='" . htmlspecialchars($product['category']) . "'>";

        echo "<div class='product-name'>" . htmlspecialchars($product['product_name']) . "</div>";
        echo "<div class='product-category'><strong>Category:</strong> " . htmlspecialchars($product['category']) . "</div>";
        echo "<div class='product-description'><strong>Description: <br></strong>" . htmlspecialchars($product['description']) . "</div>";
        echo "<div class='product-stock'><strong>Available:</strong> " . intval($product['stock']) . "</div>";

        echo "<div class='buy-section'>";
        echo "<div class='product-price'>₱" . number_format($product['price'], 2) . "</div>";

        // Size Dropdown
        echo "<div class='product-sizes'>";
        echo "<label for='size_{$product['product_id']}'>Size:</label> ";
        echo "<select name='size' id='size_{$product['product_id']}' required>";
        $sizes = ['XS', 'S', 'M', 'L', 'XL'];
        foreach ($sizes as $size) {
          echo "<option value='$size'>$size</option>";
        }
        echo "</select>";
        echo "</div>";

        // Quantity Input
        echo "<div class='product-quantity'>";
        echo "<label for='qty_{$product['product_id']}'>Quantity:</label> ";
        echo "<input type='number' name='quantity' id='qty_{$product['product_id']}' min='1' value='1' required>";
        echo "</div>";

        echo "<button type='submit' class='product-buy'>Add to Cart</button>";
        echo "</div>"; // buy-section

        echo "</div>"; // product-details
        echo "</div>"; // product
        echo "</form>";

      }
    } else {
      echo "<p>No products found</p>";
    }

    $conn->close();
  ?>
</div>

<!-- Image Modal -->
<div id="imageModal" class="modal">
  <span class="close">&times;</span>
  <img class="modal-content" id="modalImage">
</div>

<div id="cart-modal" style="
    display: none;
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.4);
    justify-content: center;
    align-items: center;
    z-index: 9999;">
  <div style="
      background: white;
      padding: 20px 30px;
      border-radius: 8px;
      font-size: 18px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.2);">
    <span id="modal-message"></span>
  </div>
</div>

<script>
// Auto-close the modal after 2 seconds
setInterval(() => {
    const modal = document.getElementById("cart-modal");
    if (modal.style.display === "flex") {
        modal.style.display = "none";
    }
}, 2000);
</script>


<script src="Image_Enlarge.js" defer></script>
<script src="Filter.js" defer></script>
<script src="prompt.js" defer></script>
</body>
</html>








<!--
<br>
<h1 id="bundles">Bundles</h1>

<div class="filters">
    <button>All</button>
    <button>Tops</button>
    <button>Skirts</button>
    <button>Bags</button>
    <button>Hats</button>
</div>
<div class="product-grid">
    <?php
    // Example placeholder items (you can later fetch from MongoDB)
    /*$products = [
        ['name' => 'Crochet Halter Top'],
        ['name' => 'Mini Skirt'],
        ['name' => 'Crochet Tote Bag'],
        ['name' => 'Bucket Hat'],
        ['name' => 'Flower Bralette'],
        ['name' => 'Summer Wrap Skirt'],
        ['name' => 'Beach Cover-up'],
        ['name' => 'Crochet Shoulder Bag'],
        ['name' => 'Custom Baby Dress'],
    ];

    foreach ($products as $product) {
        echo '
        <div class="product-card">
            <div class="product-image1"></div>
            <div class="product-name1">' . htmlspecialchars($product['name']) . '</div>
            <button class="add-to-cart">Add to Cart</button>
        </div>
        ';
    }
    */?>
</div>-->