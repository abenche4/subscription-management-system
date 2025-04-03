<?php
session_start();
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_sub_id = $_POST['user_sub_id'];
    $user_id = $_SESSION['user_id'];
    $price = $_POST['price'];

    try {
        $conn->beginTransaction();

        // Get subscription_id and current end_date
        $stmt = $conn->prepare("
            SELECT us.subscription_id, us.end_date, s.duration_days
            FROM user_subscriptions us
            JOIN subscriptions s ON us.subscription_id = s.subscription_id
            WHERE us.user_sub_id = ? AND us.user_id = ?
        ");
        $stmt->execute([$user_sub_id, $user_id]);
        $data = $stmt->fetch();

        if (!$data) {
            throw new Exception("Subscription not found.");
        }

        $new_end_date = date('Y-m-d', strtotime($data['end_date'] . " +{$data['duration_days']} days"));

        // Update subscription end date
        $stmt = $conn->prepare("UPDATE user_subscriptions SET end_date = ? WHERE user_sub_id = ?");
        $stmt->execute([$new_end_date, $user_sub_id]);

        // Log payment
        $stmt = $conn->prepare("INSERT INTO payments (user_id, subscription_id, amount) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $data['subscription_id'], $price]);

        $conn->commit();
        header("Location: ../my_subscriptions.php");
        exit();
    } catch (Exception $e) {
        $conn->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
?>
