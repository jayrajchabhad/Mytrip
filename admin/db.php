<?php
$servername = "127.0.0.1";   // IMPORTANT
$username   = "root";
$password   = "";            // blank (as per phpMyAdmin)
$database   = "mytrip";
$port       = 3308;

$conn = mysqli_connect($servername, $username, $password, $database, $port);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

?>
