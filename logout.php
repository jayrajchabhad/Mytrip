<?php
session_start(); // Find the existing session
session_unset(); // Remove all session variables
session_destroy(); // Destroy the session completely

// Redirect to the home page or login page
header("Location: index.php");
exit();
?>