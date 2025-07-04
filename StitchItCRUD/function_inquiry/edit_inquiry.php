<?php
include '../db_connect.php';

// Update when form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $fn = $_POST['fn'];
    $ln = $_POST['ln'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $message = $_POST['message'];

    $stmt = $conn->prepare("UPDATE inquiry SET fn=?, ln=?, email=?, phone=?, message=? WHERE inquiry_id=?");
    $stmt->bind_param("sssssi", $fn, $ln, $email, $phone, $message, $id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Inquiry updated successfully!'); window.location.href='../index.php';</script>";
    } else {
        echo "Update failed: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
    exit;
}

// Show the form when loading the page via GET
$id = $_GET['id'] ?? 0;
$stmt = $conn->prepare("SELECT * FROM inquiry WHERE inquiry_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();
?>

<h2>Edit Inquiry</h2>
<form method="post">
    <input type="hidden" name="id" value="<?= $data['inquiry_id'] ?>"> <br>
    <input type="text" name="fn" value="<?= htmlspecialchars($data['fn']) ?>" placeholder="First Name" required> <br><br>
    <input type="text" name="ln" value="<?= htmlspecialchars($data['ln']) ?>" placeholder="Last Name" required> <br><br>
    <input type="email" name="email" value="<?= htmlspecialchars($data['email']) ?>" placeholder="Email" required> <br><br>
    <input type="tel" name="phone" value="<?= htmlspecialchars($data['phone']) ?>" pattern="[0-9]{11}" placeholder="Phone" maxlength="11" required> <br><br>
    <textarea name="message" placeholder="Your Message"><?= htmlspecialchars($data['message']) ?></textarea> <br><br>
    <input type="submit" value="Update Inquiry">
</form>
