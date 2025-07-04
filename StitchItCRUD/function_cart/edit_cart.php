<?php
include '../db_connect.php';

if (!isset($_GET['id'])) {
    echo "No cart ID provided.";
    exit;
}

$cart_id = intval($_GET['id']);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantity = intval($_POST['quantity']);
    $product_id = intval($_POST['product_id']);
    $size = $_POST['size'];

    $stmt = $conn->prepare("UPDATE cart SET quantity = ?, product_id = ?, size = ? WHERE cart_id = ?");
    $stmt->bind_param("iisi", $quantity, $product_id, $size, $cart_id);

    if ($stmt->execute()) {
        header("Location: ../index.php"); // or to your cart list page
        exit;
    } else {
        echo "Error updating cart: " . $stmt->error;
    }
}

// Fetch current cart values
$stmt = $conn->prepare("SELECT quantity, product_id, size FROM cart WHERE cart_id = ?");
$stmt->bind_param("i", $cart_id);
$stmt->execute();
$stmt->bind_result($quantity, $selected_product_id, $selected_size);
if (!$stmt->fetch()) {
    echo "Cart item not found.";
    exit;
}
$stmt->close();
?>

<h2>Edit Cart Item</h2>
<form method="post">
    <label for="quantity">Quantity:</label><br>
    <input type="number" name="quantity" id="quantity" value="<?= htmlspecialchars($quantity) ?>" min="1" required><br><br>

    <label for="size">Size:</label><br>
    <select name="size" id="size" required>
        <?php
        $sizes = ['XS', 'S', 'M', 'L', 'XL'];
        foreach ($sizes as $s) {
            $selected = ($s == $selected_size) ? "selected" : "";
            echo "<option value='$s' $selected>$s</option>";
        }
        ?>
    </select><br><br>

    <label for="product_id">Product:</label><br>
    <select name="product_id" id="product_id" onchange="updatePrice()" required>
        <option value="">-- Select Product --</option>
        <?php
        $result = $conn->query("SELECT p.product_id, p.product_name, p.price, c.category 
                                FROM product p 
                                LEFT JOIN category c ON p.category_id = c.category_id");

        $product_prices = [];
        while ($row = $result->fetch_assoc()) {
            $selected = ($row['product_id'] == $selected_product_id) ? "selected" : "";
            echo "<option value='{$row['product_id']}' data-price='{$row['price']}' $selected>" .
                 htmlspecialchars($row['product_name']) . " (" . htmlspecialchars($row['category']) . ")</option>";
        }
        ?>
    </select><br><br>

    <strong>Total Price: ₱<span id="totalPrice">0.00</span></strong><br><br>

    <input type="submit" value="Update">
</form>

<script>
function updatePrice() {
    const select = document.getElementById('product_id');
    const quantity = document.getElementById('quantity').value;
    const price = parseFloat(select.options[select.selectedIndex].getAttribute('data-price')) || 0;
    const total = (price * quantity).toFixed(2);
    document.getElementById('totalPrice').innerText = total;
}

document.getElementById('quantity').addEventListener('input', updatePrice);
document.getElementById('product_id').addEventListener('change', updatePrice);

// Initialize total price on load
window.onload = updatePrice;
</script>
