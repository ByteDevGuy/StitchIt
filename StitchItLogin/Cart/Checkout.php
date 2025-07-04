<?php
session_start();
include '../dbConnection/db_connect.php';

// 🔐 Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../LoginSignup/LoginSignup.php?error=Please login first");
    exit();
}

$user_id = $_SESSION['user_id'];

// 🔎 Check if user has an address
$stmt = $conn->prepare("SELECT address FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($address);
$stmt->fetch();
$stmt->close();

if (empty(trim($address))) {
    // Output a script that triggers a JS alert and stop further processing
    echo "<script>
        alert('Please add an address before checking out.');
        window.location.href = '../Cart/Cart.php';
    </script>";
    exit();
}

// 🧠 Function to get cart item details (joined with product)
function fetchCartItem($conn, $cart_id, $user_id) {
    $query = "SELECT * FROM cart 
              JOIN product ON cart.product_id = product.product_id 
              WHERE cart.cart_id = ? AND cart.user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $cart_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $item = $result->fetch_assoc();
    $stmt->close();
    return $item;
}

// 🧠 Insert product into purchase history
function insertIntoHistory($conn, $user_id, $product_id, $quantity, $size, $pricePerUnit, $itemTotal) {
    $stmt = $conn->prepare("INSERT INTO history 
        (user_id, product_id, customize_id, quantity, type, status, size, price_per_unit, total_price, date_of_purchase) 
        VALUES (?, ?, NULL, ?, 'normal', 'PENDING', ?, ?, ?, CURDATE())");
    $stmt->bind_param("iiisdd", $user_id, $product_id, $quantity, $size, $pricePerUnit, $itemTotal);
    $stmt->execute();
    $stmt->close();
}

// 🧠 Delete item from cart
function deleteCartItem($conn, $cart_id, $user_id) {
    $stmt = $conn->prepare("DELETE FROM cart WHERE cart_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $cart_id, $user_id);
    $stmt->execute();
    $stmt->close();
}

// 🧠 Add summary to transactions table
function insertTransaction($conn, $user_id, $total_quantity, $total_price) {
    $stmt = $conn->prepare("INSERT INTO transactions (user_id, total_quantity, total_price) VALUES (?, ?, ?)");
    $stmt->bind_param("iid", $user_id, $total_quantity, $total_price);
    $stmt->execute();
    $stmt->close();
}

// 📦 When user clicks checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout']) && isset($_POST['selected_items'])) {
    $selectedItems = $_POST['selected_items'];
    $total_quantity = 0;
    $total_price = 0;

    foreach ($selectedItems as $cart_id) {
        $item = fetchCartItem($conn, $cart_id, $user_id);

        if ($item) {
            $quantity = $item['quantity'];
            $pricePerUnit = $item['price'];
            $itemTotal = $quantity * $pricePerUnit;
            $size = $item['size'];

            $total_quantity += $quantity;
            $total_price += $itemTotal;

            insertIntoHistory($conn, $user_id, $item['product_id'], $quantity, $size, $pricePerUnit, $itemTotal);
            deleteCartItem($conn, $cart_id, $user_id);
        }
    }

    insertTransaction($conn, $user_id, $total_quantity, $total_price);
    $conn->close();

    header("Location: ../Cart/Cart.php?success=Checked+out+successfully");
    exit();
} else {
    header("Location: ../Cart/Cart.php?error=No+items+selected");
    exit();
}
?>
