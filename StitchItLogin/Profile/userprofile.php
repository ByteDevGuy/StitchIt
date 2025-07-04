<?php
session_start();
include '../dbConnection/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../LoginSignup/LoginSignup.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['delete_review_id'])) {
    $review_id = (int)$_POST['delete_review_id'];
    $user_id = $_SESSION['user_id'];

    // Ensure the review belongs to the logged-in user before deleting
    $stmt = $conn->prepare("DELETE FROM review WHERE review_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $review_id, $user_id);

    if ($stmt->execute()) {
        echo "<script>alert('Review deleted successfully.'); window.location.href='userprofile.php';</script>";
    } else {
        echo "<script>alert('Failed to delete review: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}


// Fetch user profile data
include 'fetch_user.php';

// Fetch order history
include 'load_orders.php';

// Fetch reviews
include 'load_reviews.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    $product_id = $_POST['product_id'];
    $rating = intval($_POST['rating']);
    $message = trim($_POST['message']);

    if ($rating >= 1 && $rating <= 5 && !empty($message)) {
        $stmt = $conn->prepare("INSERT INTO review (message, user_id, rating, product_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("siii", $message, $user_id, $rating, $product_id);
        $stmt->execute();
        $stmt->close();
    }

    // Reload page to update reviews
    header("Location: userprofile.php");
    exit();
}

// Handle profile or address update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    include 'update_profile.php';
}

// Handle delete selected orders
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_selected'], $_POST['delete_indexes'])) {
    foreach ($_POST['delete_indexes'] as $index) {
        if (isset($all_orders[$index])) {
            $product = $all_orders[$index]['product'];
            $stmt = $conn->prepare("DELETE FROM orders WHERE user_id = ? AND product = ?");
            $stmt->bind_param("is", $user_id, $product);
            $stmt->execute();
        }
    }
    header("Location: userprofile.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>User Profile - StitchIt</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="userprofile.css">
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
        <a href="../Cart/Cart.php">Cart</a>
        <?php if (isset($_SESSION['username'])): ?>
            <a href="../Profile/userprofile.php" class="active">Profile</a>
            <a href="../LoginSignup/Logout.php" onclick="confirmLogout(event)">Logout</a>
        <?php else: ?>
            <a href="../LoginSignup/LoginSignup.php">Login / Sign Up</a>
        <?php endif; ?>
    </nav>
</header>

<section class="profile-section">
    <div class="profile-left">
        <!-- Removed avatar image -->
        <h2><?php echo htmlspecialchars($user_data['fn'] . ' ' . $user_data['ln']); ?></h2>
        <p><?php echo htmlspecialchars($user_data['email']); ?></p>
        <p><?php echo htmlspecialchars($user_data['username']); ?></p>
        <p><?php echo htmlspecialchars($user_data['number']); ?></p>
        <p>
            <?php 
                if (!empty($user_data['address'])) {
                    echo nl2br(htmlspecialchars($user_data['address']));
                } else {
                    echo '<span style="color: #6c757d;">No address saved yet</span>';
                }
            ?>
        </p>
    </div>

    <div class="profile-right">
        <?php if (isset($_GET['edit_profile'])): ?>
            <h3>Edit Profile & Shipping Information</h3>
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="fn">First Name</label>
                    <input type="text" id="fn" name="fn" value="<?php echo htmlspecialchars($user_data['fn']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="ln">Last Name</label>
                    <input type="text" id="ln" name="ln" value="<?php echo htmlspecialchars($user_data['ln']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user_data['username']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="number">Phone Number</label>
                    <input type="tel" id="number" name="number" maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '')" value="<?php echo htmlspecialchars($user_data['number']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="address">Shipping Address</label>
                    <textarea id="address" name="address" rows="4" style="width: 100%; padding: 8px;" required><?php echo htmlspecialchars($user_data['address']); ?></textarea>
                </div>
                <div class="form-actions">
                    <button type="submit" name="update_profile" class="btn">Save Changes</button>
                    <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        <?php else: ?>
            <h3>Shipping Address</h3>
            <?php if (!empty($user_data['address'])): ?>
                <p style="font-size: 1.1rem; background: #f8f9fa; padding: 10px; border-radius: 8px;"><?php echo nl2br(htmlspecialchars($user_data['address'])); ?></p>
            <?php else: ?>
                <p style="color: #6c757d;">No address saved yet</p>
            <?php endif; ?>
            <a href="?edit_profile=1" class="btn" style="margin-top: 20px;">Edit Profile & Address</a>
        <?php endif; ?>
    </div>
</section>



<section class="card">
    <h2>Order History</h2>
    <?php if (!empty($all_orders)): ?>
        <div class="history-header">
            <div style="flex: 4;" class="box">Product Info</div>
            <div style="flex: 2;" class="box">Status</div>
            <div style="flex: 3;" class="box">Dates</div>
        </div>

        <?php foreach ($all_orders as $item): ?>
            <div class="history-row" style="display: flex; align-items: stretch; padding: 10px 0; border-bottom: 1px solid #eee;">
                <!-- Product Info -->
                <div style="flex: 4;" class="box">
                    <div style="display: flex; align-items: center;">
                        <?php if (!empty($item['image'])): ?>
                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="Product Image" class="product-image clickable-image" onclick="showImageModal(this.src)">
                        <?php else: ?>
                            <div class="product-image" style="background: #eee; display: flex; align-items: center; justify-content: center;">No Image</div>
                        <?php endif; ?>

                        <div style="display: flex; flex-direction: column; gap: 4px;">
                            <strong class="product-name" style="font-size: 1.1rem; color: var(--dusty-mauve);">
                                <?php echo htmlspecialchars($item['product']); ?>
                            </strong>
                            <small>Type: <?php echo htmlspecialchars($item['type']); ?></small>
                            <small>Quantity: <strong><?php echo htmlspecialchars($item['quantity']); ?></strong></small>
                            <small>Price per unit: ₱<?php echo number_format($item['price_per_unit'], 2); ?></small>
                            <small>Total: ₱<?php echo number_format($item['total_price'], 2); ?></small>
                        </div>

                    </div>
                </div>

                <!-- ✅ Image Modal -->
                <div id="imageModal" class="image-modal">
                    <div class="modal-box">
                        <span class="close" onclick="closeImageModal()">&times;</span>
                        <img id="modalImage" class="modal-content" src="" alt="Preview">
                    </div>
                </div>

                <!-- Status -->
                <div style="flex: 2;" class="box">
                    <?php
                    $status = strtoupper(trim($item['status'] ?? 'PENDING'));
                    $type = strtolower($item['type']);
                    $status_class = strtolower($status);
                    ?>
                    <span class="status-<?php echo $status_class; ?>"><?php echo $status; ?></span><br>

                    <?php if ($status === 'COMPLETED' && $type === 'normal'): ?>
                        <?php if (!empty($item['already_reviewed'])): ?>
                            <span class="btn-review-disabled" style="color: gray; font-size: 0.9em;">Already Reviewed</span>
                        <?php else: ?>
                            <button type="button"
                                onclick="openReviewModal('<?php echo htmlspecialchars($item['product_id'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($item['product'], ENT_QUOTES); ?>', '<?php echo $item['history_id']; ?>')"
                                class="btn btn-secondary btn-review">Review</button>
                        <?php endif; ?>
                    <?php else: ?>
                        <span class="btn-review-disabled" style="color: gray; font-size: 0.9em;">Review unavailable</span>
                    <?php endif; ?>
                </div>


                <!-- Dates -->
                <div style="flex: 3; display: flex;" class="box">
                    <div style="margin: auto 0;">
                        <div><strong>Ordered:</strong> <?php echo htmlspecialchars($item['date_of_purchase']); ?></div>
                        <div><strong>Arrival:</strong> <?php echo !empty($item['date_of_arrival']) ? htmlspecialchars($item['date_of_arrival']) : 'TBD'; ?></div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No orders or requests found.</p>
    <?php endif; ?>
</section>

<section class="card">
    <h2>My Reviews</h2>
    <?php foreach ($reviews as $review): ?>
        <div class="review">
            <img src="<?php echo htmlspecialchars($review['image']); ?>" alt="<?php echo htmlspecialchars($review['product']); ?>" class="review-product-image">

            <div class="review-header">
                <strong><?php echo htmlspecialchars($review['name']); ?></strong>
                <span style="font-size: 0.9em; color: gray;">
                    Reviewed on <?php echo date("F j, Y", strtotime($review['date'])); ?>
                </span>
                <p><?php echo str_repeat('⭐', $review['rating']); ?></p>
                <p><em>Product:</em> <?php echo htmlspecialchars($review['product']); ?></p>
            </div>

            <p>Review:</p>
            <p class="review-text"><?php echo htmlspecialchars($review['message']); ?></p>

            <!-- Delete button -->
            <form method="post" onsubmit="return confirm('Are you sure you want to delete this review?');" style="margin-top: 10px;">
                <input type="hidden" name="delete_review_id" value="<?php echo $review['review_id']; ?>">
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
    <?php endforeach; ?>
</section>


<!-- REVIEW MODAL -->
<div id="reviewModal" class="modal-overlay" style="display:none;">
    <div class="modal-content">
        <span class="close-button" onclick="closeReviewModal()">&times;</span>
        <h2>Leave a Review for <span id="modal_product_name" style="color:#d94b87;"></span></h2>
        <form id="reviewForm" method="post">
            <input type="hidden" name="product_id" id="modal_product_id">
            <input type="hidden" name="history_id" id="modal_history_id">

            <label for="rating">Rating (1–5):</label>
            <select name="rating" id="rating" required>
                <option value="">Select</option>
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <option value="<?php echo $i; ?>"><?php echo $i; ?> ⭐</option>
                <?php endfor; ?>
            </select>

            <label for="message">Comment:</label>
            <textarea name="message" id="message" rows="4" required></textarea>

            <button type="submit" name="submit_review" class="btn">Submit Review</button>
        </form>
    </div>
</div>


<script src="userprofile.js" defer></script>
</body>
</html>
