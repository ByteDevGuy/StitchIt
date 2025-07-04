<?php
session_start();
include '../../dbConnection/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Sanitize inputs
    $first = htmlspecialchars(trim($_POST['first']));
    $last = htmlspecialchars(trim($_POST['last']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Optional user_id
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO inquiry (fn, ln, email, phone, message, user_id) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssi", $first, $last, $email, $phone, $message, $user_id);

    if ($stmt->execute()) {
        echo "<script>
            alert('Inquiry submitted successfully!');
            window.location.href = '../Contact.php';
        </script>";
    } else {
        echo "<script>
            alert('Error submitting inquiry: " . addslashes($stmt->error) . "');
            window.location.href = '../Contact.php';
        </script>";
    }

    $stmt->close();
    $conn->close();
}
?>
