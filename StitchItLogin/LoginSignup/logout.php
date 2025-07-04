<?php
session_start();
session_unset(); // Remove all session variables
session_destroy(); // Destroy the session

// Redirect back to home or login page
header("Location: ../index.php");
exit();
?>