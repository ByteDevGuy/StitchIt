<?php include 'db_connect.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Database Interface</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>

<div class="sticky-nav">
    <button onclick="scrollToSection('user-table')">Users</button>
    <button onclick="scrollToSection('product-table')">Products</button>
    <button onclick="scrollToSection('category-table')">Categories</button>
    <button onclick="scrollToSection('cart-table')">Cart</button>
    <button onclick="scrollToSection('customize-table')">Customize</button>
    <button onclick="scrollToSection('history-table')">History</button>
    <button onclick="scrollToSection('review-table')">Reviews</button>
    <button onclick="scrollToSection('inquiry-table')">Inquiries</button>
    <button onclick="scrollToSection('add-interface')">+ Add Interface</button>
</div>


<h1>Database Access Interface</h1>

<!-- USERS -->
<h2 id="user-table">Users</h2>
<table>
<tr><th>ID</th><th>Name</th><th>Email</th><th>Username</th><th>Phone</th><th>Address</th><th>Actions</th></tr>
<?php
$result = $conn->query("SELECT user_id, fn, ln, email, username, number, address FROM users");
while($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$row['user_id']}</td>
            <td>" . htmlspecialchars($row['fn']) . " " . htmlspecialchars($row['ln']) . "</td>
            <td>" . htmlspecialchars($row['email']) . "</td>
            <td>" . htmlspecialchars($row['username']) . "</td>
            <td>" . htmlspecialchars($row['number']) . "</td>
            <td>" . htmlspecialchars($row['address']) . "</td>
            <td class='actions'>
                <a href='function_user/edit_user.php?id={$row['user_id']}' class='edit-btn'>Edit</a>
                <a href='function_user/delete_user.php?id={$row['user_id']}' class='delete-btn' onclick='return confirm(\"Are you sure?\")'>Delete</a>
            </td>
          </tr>";
}
?>
</table>

<!-- PRODUCTS -->
<h2 id="product-table">Products</h2>
<table>
<tr>
  <th>ID</th><th>Name</th><th>Description</th><th>Stock</th><th>Price</th><th>Category</th><th>Image</th><th>Actions</th>
</tr>
<?php
$sql = "SELECT p.*, c.category FROM product p LEFT JOIN category c ON p.category_id = c.category_id";
$result = $conn->query($sql);
while($row = $result->fetch_assoc()) {
    echo "<tr>
        <td>{$row['product_id']}</td>
        <td>" . htmlspecialchars($row['product_name']) . "</td>
        <td>" . htmlspecialchars($row['description']) . "</td>
        <td>{$row['stock']}</td>
        <td>₱" . number_format($row['price'], 2) . "</td>
        <td>" . htmlspecialchars($row['category']) . "</td>";

    // Show image if it exists
    if (!empty($row['image'])) {
    $imgData = base64_encode($row['image']);
    echo "<td>
            <img src='data:image/jpeg;base64,{$imgData}' 
                 width='60' height='60' 
                 style='cursor:pointer;' 
                 onclick=\"openModal('data:image/jpeg;base64,{$imgData}')\">
          </td>";
    } else {
        echo "<td>No image</td>";
    }


    echo "<td class='actions'>
            <a href='function_product/edit_product.php?id={$row['product_id']}' class='edit-btn'>Edit</a>
            <a href='function_product/delete_product.php?id={$row['product_id']}' class='delete-btn' onclick='return confirm(\"Are you sure?\")'>Delete</a>
        </td>
      </tr>";
}
?>
</table>


<!-- CATEGORY -->
<h2 id="category-table">Categories</h2>
<table>
<tr><th>ID</th><th>Category Name</th><th>Actions</th></tr>
<?php
$result = $conn->query("SELECT * FROM category ORDER BY category_id ASC");
while($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$row['category_id']}</td>
            <td>" . htmlspecialchars($row['category']) . "</td>
            <td class='actions'>
                <a href='function_category/edit_category.php?id={$row['category_id']}' class='edit-btn'>Edit</a>
                <a href='function_category/delete_category.php?id={$row['category_id']}' class='delete-btn' onclick='return confirm(\"Are you sure?\")'>Delete</a>
            </td>
          </tr>";
}
?>
</table>

<!-- CART LIST -->
<h2 id="cart-table">Cart Items</h2>
<table>
<tr>
  <th>ID</th>
  <th>Quantity</th>
  <th>Size</th>
  <th>User</th>
  <th>Product</th>
  <th>Image</th>
  <th>Total Price</th>
  <!--<th>Actions</th>-->
</tr>


<?php
$sql = "
    SELECT 
        c.cart_id, 
        c.quantity, 
        c.size, 
        u.username, 
        p.product_name, 
        p.price,
        p.image,
        (c.quantity * p.price) AS total_price
    FROM cart c
    LEFT JOIN users u ON c.user_id = u.user_id
    LEFT JOIN product p ON c.product_id = p.product_id
";


$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $imageTag = $row['image'] 
    ? "<img src='data:image/jpeg;base64," . base64_encode($row['image']) . "' 
           style='width:50px;height:50px;cursor:pointer;' 
           onclick=\"openModal('data:image/jpeg;base64," . base64_encode($row['image']) . "')\">"
    : "No image";


    echo "<tr>
        <td>{$row['cart_id']}</td>
        <td>{$row['quantity']}</td>
        <td>{$row['size']}</td>
        <td>" . htmlspecialchars($row['username']) . "</td>
        <td>" . htmlspecialchars($row['product_name']) . "</td>
        <td>$imageTag</td>
        <td>₱" . number_format($row['total_price'], 2) . "</td>
        <!--
        <td class='actions'>
            <a href='function_cart/edit_cart.php?id={$row['cart_id']}' class='edit-btn'>Edit</a>
            <a href='function_cart/delete_cart.php?id={$row['cart_id']}' class='delete-btn' onclick='return confirm(\"Are you sure?\")'>Delete</a>
            <a href='function_cart/checkout_cart.php?id={$row['cart_id']}' class='checkout-btn'>Checkout</a>
        </td>
      </tr>-->";
}
?>
</table>



<h2 id="customize-table">Customize Transactions</h2>

<!-- Modal HTML -->
<div id="imageModal" class="modal">
  <span class="close" onclick="closeModal()">&times;</span>
  <img class="modal-content" id="modalImage">
</div>

<table>
<tr>
  <th>ID</th>
  <th>Name</th>
  <th>Size</th>
  <th>Preference</th>
  <th>Message</th>
  <th>Image</th>
  <th>User ID</th>
  <th>Actions</th>
</tr>

<?php
$result = $conn->query("SELECT * FROM customize_transaction");
while($row = $result->fetch_assoc()) {
    $imageSrc = "view_image.php?id={$row['customize_id']}";
    echo "<tr>
            <td>{$row['customize_id']}</td>
            <td>" . htmlspecialchars($row['customize_name']) . "</td>
            <td>{$row['size']}</td>
            <td>" . htmlspecialchars($row['preference']) . "</td>
            <td>" . htmlspecialchars($row['message']) . "</td>
            <td>
                <img src='$imageSrc' alt='Image' style='width:50px;height:50px;cursor:pointer;' onclick=\"openModal('$imageSrc')\">
            </td>
            <td>{$row['user_id']}</td>
            <td class='actions'>";
                if (empty($row['status']) || $row['status'] === 'PENDING') {
                    echo "
                    <form method='post' action='function_customize/handle_customize_action.php' style='display:inline;'>
                        <input type='hidden' name='customize_id' value='{$row['customize_id']}'>
                        <input type='hidden' name='action' value='approve'>
                        <button type='submit' class='approve-btn'>Approve</button>
                    </form>
                    <form method='post' action='function_customize/handle_customize_action.php' style='display:inline;'>
                        <input type='hidden' name='customize_id' value='{$row['customize_id']}'>
                        <input type='hidden' name='action' value='decline'>
                        <button type='submit' class='decline-btn'>Decline</button>
                    </form>";
                } else {
                    echo "<em>{$row['status']}</em>";
                }
                echo "</td>";
          echo "</tr>";
}
?>
</table>

<!-- Modal Script -->
<script>
function openModal(src) {
    const modal = document.getElementById("imageModal");
    const modalImg = document.getElementById("modalImage");
    modal.style.display = "block";
    modalImg.src = src;
}
function closeModal() {
    document.getElementById("imageModal").style.display = "none";
}
</script>




<!-- HISTORY LIST -->
<h2 id="history-table">Purchase History</h2>
<table>
<tr>
  <th>ID</th>
  <th>User</th>
  <th>Type</th>
  <th>Status</th>
  <th>Date Purchased</th>
  <th>Date Arrived</th>
  <th>Quantity</th>
  <th>Price Each</th>
  <th>Total Price</th>
  <th>Item</th> <!-- This one added -->
</tr>


<?php
$sql = "
    SELECT 
        h.history_id,
        u.username,
        h.type,
        h.status,
        h.date_of_purchase,
        h.date_of_arrival,
        h.quantity,
        h.price_per_unit,
        h.total_price,
        h.product_image,
        c.customize_name
    FROM history h
    LEFT JOIN users u ON h.user_id = u.user_id
    LEFT JOIN customize_transaction c ON h.customize_id = c.customize_id
";


$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$row['history_id']}</td>
            <td>" . htmlspecialchars($row['username']) . "</td>
            <td>{$row['type']}</td>
            <td>
                <form action='function_cart/update_status.php' method='post'>
                    <input type='hidden' name='history_id' value='{$row['history_id']}'>
                    <select name='status'>
                        <option value='PENDING' " . ($row['status'] === 'PENDING' ? 'selected' : '') . ">PENDING</option>
                        <option value='DECLINED' " . ($row['status'] === 'DECLINED' ? 'selected' : '') . ">DECLINED</option>
                        <option value='APPROVED' " . ($row['status'] === 'APPROVED' ? 'selected' : '') . ">APPROVED</option>
                        <option value='SHIPPING' " . ($row['status'] === 'SHIPPING' ? 'selected' : '') . ">SHIPPING</option>
                        <option value='COMPLETED' " . ($row['status'] === 'COMPLETED' ? 'selected' : '') . ">COMPLETED</option>
                    </select>
                    <button type='submit'>Update</button>
                </form>
            </td>
            <td>{$row['date_of_purchase']}</td>
            <td>" . ($row['date_of_arrival'] ?? '-') . "</td>
            <td>{$row['quantity']}</td>
            <td>₱" . number_format($row['price_per_unit'], 2) . "</td>
            <td>₱" . number_format($row['total_price'], 2) . "</td>
            <td>";
    
    if ($row['type'] === 'customized') {
        echo "<strong>[Custom]</strong> " . htmlspecialchars($row['customize_name']) . "<br>";
        echo "<img src='view_image_history.php?id={$row['history_id']}' width='50' height='50' class='clickable-img' onclick=\"showImageModal('view_image_history.php?id={$row['history_id']}')\" style='margin-top:5px; border: 1px solid #ccc; border-radius: 5px; cursor: pointer; transition: transform 0.2s;' onmouseover='this.style.transform=\"scale(1.4)\"' onmouseout='this.style.transform=\"scale(1)\"'>";


    } else {
        echo "Store Product";
    }
    echo "</td></tr>";
}
?>
</table>

<!-- REVIEWS -->
<h2 id="review-table">Reviews</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Message</th>
        <th>Rating</th>
        <th>User</th>
        <th>Product</th>
        <th>Action</th>
    </tr>
<?php

$sql = "
    SELECT 
        r.review_id,
        r.message,
        r.rating,
        u.fn AS user_name,
        p.product_name
    FROM review r
    LEFT JOIN users u ON r.user_id = u.user_id
    LEFT JOIN product p ON r.product_id = p.product_id
    ORDER BY r.review_id ASC
";

$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$row['review_id']}</td>
            <td>" . htmlspecialchars($row['message']) . "</td>
            <td>{$row['rating']}</td>
            <td>" . htmlspecialchars($row['user_name']) . "</td>
            <td>" . htmlspecialchars($row['product_name']) . "</td>
            <td class='actions'>
                <a href='function_review/delete_review.php?id={$row['review_id']}' class='delete-btn' onclick='return confirm(\"Are you sure you want to delete this review?\")'>Delete</a>
            </td>
          </tr>";
}
?>
</table>


<!-- INQUIRY TABLE -->
<h2 id="inquiry-table">Inquiries</h2>
<table class="inquiry-table">
        <tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Message</th>
            <th>Action</th>
        </tr>
        <?php
        $result = $conn->query("SELECT * FROM inquiry ORDER BY inquiry_id DESC");
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['inquiry_id']}</td>
                <td>" . htmlspecialchars($row['fn'] . ' ' . $row['ln']) . "</td>
                <td>" . htmlspecialchars($row['email']) . "</td>
                <td>" . htmlspecialchars($row['phone']) . "</td>
                <td>" . nl2br(htmlspecialchars($row['message'])) . "</td>
                <td>
                    <a href='function_inquiry/delete_inquiry.php?id={$row['inquiry_id']}' class='delete-btn' onclick='return confirm(\"Are you sure you want to delete this inquiry?\")'>Delete</a>
                </td>
            </tr>";
        }
        ?>
</table>


<div id="add-interface">
        <div id="form1">
<!-- ADD USER FORM -->
<form action="Add/process_add_user.php" method="post">
    <h2>Add User:</h2>
    <input type="text" name="fn" placeholder="First Name" required>
    <input type="text" name="ln" placeholder="Last Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="passcode" placeholder="Password" required>
    <input type="text" name="number" placeholder="Phone Number" maxlength="11" pattern="\d{11}" title="Enter exactly 11 digits" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
    <textarea name="address" placeholder="Address" required></textarea>
    <input type="submit" value="Add User">
</form>

<!-- ADD PRODUCT FORM -->
<form action="Add/process_add_product.php" method="post" enctype="multipart/form-data">
    <h2>Add Product:</h2>
    <input type="text" name="product_name" placeholder="Product Name" required>
    <textarea name="description" placeholder="Description" required></textarea>
    <input type="number" name="stock" placeholder="Stock" required min="0">
    <input type="number" name="price" placeholder="Price" step="0.01" required min="0">
    <select name="category_id" required>
        <option value="" disabled selected>Select Category</option>
        <?php
            $catResult = $conn->query("SELECT category_id, category FROM category ORDER BY category ASC");
            while($catRow = $catResult->fetch_assoc()) {
                echo "<option value='{$catRow['category_id']}'>" . htmlspecialchars($catRow['category']) . "</option>";
            }
        ?>
    </select><br>
    
    <!-- Image Upload Input -->
    <input type="file" name="image" accept="image/*" required>

    <input type="submit" value="Add Product">
</form>
</div>

<div id="form2">
<!-- ADD CATEGORY FORM -->
<form action="Add/process_add_category.php" method="post">
    <h2>Add Category:</h2>
    <input type="text" name="category" placeholder="Category Name" required>
    <input type="submit" value="Add Category">
</form>

<!-- ADD CART FORM -->
<form action="Add/process_add_cart.php" method="post">
    <h2>Add Cart Item:</h2>
    <input type="number" name="quantity" placeholder="Quantity" required min="1">
    
    <select name="size" required>
        <option value="" disabled selected>Select Size</option>
        <option value="XS">XS</option>
        <option value="S">S</option>
        <option value="M">M</option>
        <option value="L">L</option>
        <option value="XL">XL</option>
    </select>

    <select name="user_id" required>
        <option value="" disabled selected>Select User</option>
        <?php
        $userResult = $conn->query("SELECT user_id, username FROM users ORDER BY username ASC");
        while ($userRow = $userResult->fetch_assoc()) {
            echo "<option value='{$userRow['user_id']}'>" . htmlspecialchars($userRow['username']) . "</option>";
        }
        ?>
    </select>

    <select name="product_id" required>
        <option value="" disabled selected>Select Product</option>
        <?php
        $prodResult = $conn->query("SELECT product_id, product_name FROM product ORDER BY product_name ASC");
        while ($prodRow = $prodResult->fetch_assoc()) {
            echo "<option value='{$prodRow['product_id']}'>" . htmlspecialchars($prodRow['product_name']) . "</option>";
        }
        ?>
    </select>

    <input type="submit" value="Add Cart Item">
</form>

<!-- ADD INQUIRY FORM -->
<form action="Add/process_add_inquiry.php" method="post">
    <h2>Submit Inquiry</h2>
    <input type="text" name="fn" placeholder="First Name" required>
    <input type="text" name="ln" placeholder="Last Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="phone" placeholder="Phone Number" maxlength="11" pattern="\d{11}" title="Enter exactly 11 digits" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
    <textarea name="message" placeholder="Your Message" maxlength="500"></textarea>
    <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
    <input type="submit" value="Send Inquiry">
</form>
</div>


<div id="imageModal" class="modal">
  <span class="close" onclick="closeModal()">&times;</span>
  <img class="modal-content" id="modalImage">
</div>

</div>

<script>
function showImageModal(imageSrc) {
  const modal = document.getElementById('imageModal');
  const modalImg = document.getElementById('modalImage');
  modalImg.src = imageSrc;
  modal.style.display = "block";
}

function closeImageModal() {
  document.getElementById('imageModal').style.display = "none";
}
</script>


<!-- Image Modal -->
<div id="imageModal" class="image-modal">
  <div class="modal-content-box">
    <span class="modal-close" onclick="closeImageModal()">&times;</span>
    <img id="modalImage" src="" alt="Preview" class="modal-image">
  </div>
</div>

<script src="index.js" defer></script>

<script>
function scrollToSection(id) {
    const el = document.getElementById(id);
    if (el) {
        el.scrollIntoView({ behavior: 'smooth' });
    }
}
</script>

</body>
</html>









<!-- SUB CATEGORIES -->
<!--<h2>Sub-Categories</h2>
<table>
<tr>
  <th>ID</th>
  <th>Sub-Category Name</th>
  <th>Main Category</th>
  <th>Actions</th>
</tr>
<?php
/*$sql = "
  SELECT s.sub_category_id, s.sub_category_name, c.category 
  FROM sub_category s 
  LEFT JOIN category c ON s.category_id = c.category_id
";
$result = $conn->query($sql);
while($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$row['sub_category_id']}</td>
            <td>" . htmlspecialchars($row['sub_category_name']) . "</td>
            <td>" . htmlspecialchars($row['category']) . "</td>
            <td class='actions'>
                <a href='function_sub_category/edit_sub_category.php?id={$row['sub_category_id']}' class='edit-btn'>Edit</a>
                <a href='function_sub_category/delete_sub_category.php?id={$row['sub_category_id']}' class='delete-btn' onclick='return confirm(\"Are you sure?\")'>Delete</a>
            </td>
          </tr>";
}*/
?>
</table>-->

<!-- ADD SUB-CATEGORY FORM -->
<!--<form action="Add/process_add_sub_category.php" method="POST">
    <h2>Add Sub-Category</h2>
    <input type="text" name="sub_category_name" placeholder="Sub-Category Name" required>

    <select name="category_id" required>
        <option value="" disabled selected>Select Main Category</option>
        <?php
        /*$result = $conn->query("SELECT category_id, category FROM category");
        while ($row = $result->fetch_assoc()) {
            echo "<option value='{$row['category_id']}'>" . htmlspecialchars($row['category']) . "</option>";
        }*/
        ?>
    </select>

    <input type="submit" value="Add Sub-Category">
</form>-->

<!-- TRANSACTIONS -->
<!--<h2>Transactions</h2>
<table border="1" cellpadding="8" cellspacing="0">
<tr>
    <th>Transaction ID</th>
    <th>User</th>
    <th>Product</th>
    <th>Quantity</th>
    <th>Price</th>
    <th>Total</th>
</tr>
<?php
/*$result = $conn->query("
    SELECT c.cart_id, u.user_id, CONCAT(u.fn, ' ', u.ln) AS full_name,
           p.product_name, c.quantity, p.price
    FROM cart c
    JOIN users u ON c.user_id = u.user_id
    JOIN product p ON c.product_id = p.product_id
");

while ($row = $result->fetch_assoc()) {
    $total = $row['quantity'] * $row['price'];
    echo "<tr>
            <td>{$row['cart_id']}</td>
            <td>" . htmlspecialchars($row['full_name']) . "</td>
            <td>" . htmlspecialchars($row['product_name']) . "</td>
            <td>{$row['quantity']}</td>
            <td>₱" . number_format($row['price'], 2) . "</td>
            <td>₱" . number_format($total, 2) . "</td>
          </tr>";
}*/
?>
</table>-->

<!-- ADD CUSTOMIZE TRANSACTION FORM -->
<!--<form action="Add/process_add_customize_transaction.php" method="post" enctype="multipart/form-data">
    <h2>Add Customize Transaction:</h2>
    <textarea name="message" placeholder="Message" required></textarea>
    <input type="file" name="image" accept="image/*" required>
    <select name="user_id" required>
        <option value="" disabled selected>Select User</option>
        <?php
            /*$userResult = $conn->query("SELECT user_id, username FROM users ORDER BY username ASC");
            while($userRow = $userResult->fetch_assoc()) {
                echo "<option value='{$userRow['user_id']}'>" . htmlspecialchars($userRow['username']) . "</option>";
            }*/
        ?>
    </select>
    <input type="submit" value="Add Customize Transaction">
</form>-->

<!-- ADD TRANSACTION FORM -->
<!--<form action="process_add_transaction.php" method="post">
    <h2>Add Transaction</h2>
    <label for="user_id">Select User:</label>
    <select name="user_id" required>
        <?php
        //include 'db_connect.php';
        //$users = $conn->query("SELECT DISTINCT u.user_id, CONCAT(u.fn, ' ', u.ln) AS full_name
        //                    FROM cart c
        //                    JOIN users u ON c.user_id = u.user_id"
        //                    );
        //while ($row = $users->fetch_assoc()) {
        //    echo "<option value='{$row['user_id']}'>{$row['full_name']}</option>";
        //}
        ?>
    </select><br><br>

    <label for="cart_id">Select Cart Item:</label>
    <select name="cart_id" required>
        //<?php
        //$cart = $conn->query("
        //    SELECT c.cart_id, p.product_name, c.quantity, p.price
        //    FROM cart c
        //    JOIN product p ON c.product_id = p.product_id
        //");
        //while ($row = $cart->fetch_assoc()) {
        //    $total = $row['quantity'] * $row['price'];
        //    echo "<option value='{$row['cart_id']}'>
        //            {$row['product_name']} - Qty: {$row['quantity']} - ₱{$total}
        //          </option>";
        //}
        ?>
    </select><br><br>

    <input type="submit" value="Add Transaction">
</form>-->