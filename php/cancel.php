<?php
session_start();
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_sub_id = $_POST['user_sub_id'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("UPDATE user_subscriptions SET status = 'cancelled' WHERE user_sub_id = ? AND user_id = ?");
    $stmt->execute([$user_sub_id, $user_id]);

    header("Location: ../my_subscriptions.php");
    exit();
}
?>
