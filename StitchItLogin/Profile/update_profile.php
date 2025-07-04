<?php
if (isset($_POST['update_profile'])) {
    $fn = $_POST['fn'];
    $ln = $_POST['ln'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $number = $_POST['number'];
    $address = $_POST['address'];

    $stmt = $conn->prepare("UPDATE users SET fn = ?, ln = ?, email = ?, username = ?, number = ?, address = ? WHERE user_id = ?");
    $stmt->bind_param("ssssssi", $fn, $ln, $email, $username, $number, $address, $user_id);
    $stmt->execute();
    $stmt->close();

    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

?>
