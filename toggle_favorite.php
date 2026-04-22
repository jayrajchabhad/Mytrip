<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "login_required";
    exit();
}

$u_id = $_SESSION['user_id'];
$p_id = intval($_POST['package_id']);

$check = $conn->query("SELECT id FROM favorites WHERE user_id = $u_id AND package_id = $p_id");

if ($check->num_rows > 0) {
    $conn->query("DELETE FROM favorites WHERE user_id = $u_id AND package_id = $p_id");
    echo "removed";
} else {
    $conn->query("INSERT INTO favorites (user_id, package_id) VALUES ($u_id, $p_id)");
    echo "added";
}
?>