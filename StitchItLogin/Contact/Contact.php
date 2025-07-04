<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Us</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="Contact.css">
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
      <a href="../Profile/userprofile.php">Profile</a>
      <a href="../LoginSignup/Logout.php" onclick="confirmLogout(event)">Logout</a>
    <?php else: ?>
      <a href="../LoginSignup/LoginSignup.php">Login / Sign Up</a>
    <?php endif; ?>
  </nav>
</header>

<div class="container">
    <div class="left">
        <h1>Contact Us</h1>
        <p>Feel free to reach out with questions, suggestions, or feedback. We’d love to hear from you!</p>

        <div class="info">
            <div class="icon">
                <i class='bx bx-phone'></i>
                <p>094-6754-2957</p>
            </div>
            <div class="icon">
                <i class='bx bx-envelope'></i>
                <p>email@example.com</p>
            </div>
             <div class="icon">
                <i class='bx bx-map'></i>
                <p>69 Tequila St. Brgy. Toni Fowler, Tahimik Lang Sa Umpisa</p>
            </div>
        </div>
    </div>

    <div class="right">
        <form method="POST" action="Submit/Inquiry.php">
            <div class="name-fields">
                <input type="text" name="first" placeholder="First Name" required>
                <input type="text" name="last" placeholder="Last Name" required>
            </div>
            <div class="form-group">
                <input type="email" name="email" placeholder="example@email.com" required>
            </div>
            <div class="form-group">
                <input type="tel" name="phone" placeholder="09XX-XXX-XXXX (Optional)" maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
            </div>
            <div class="form-group">
                <textarea name="message" rows="5" placeholder="Type your message..." maxlength="500" required></textarea>
            </div>
            <button type="submit" name="submit">Send Message</button>
        </form>
    </div>
</div>

<footer>
    &copy; 2025 Your Website. All rights reserved.
</footer>

</body>
</html>