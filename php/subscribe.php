<?php
session_start();
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "Step 1: Form received<br>";

    $user_id = $_SESSION['user_id'];
    $subscription_id = $_POST['subscription_id'];

    echo "User ID: $user_id<br>";
    echo "Subscription ID: $subscription_id<br>";

    // Step 2: Check for existing subscription
    $stmt = $conn->prepare("SELECT * FROM user_subscriptions WHERE user_id = ? AND subscription_id = ? AND status = 'active'");
    $stmt->execute([$user_id, $subscription_id]);
    $existing = $stmt->fetch();

    if ($existing) {
        echo "You are already subscribed to this service.";
        exit();
    }

    // Step 3: Get subscription duration
    $stmt = $conn->prepare("SELECT duration_days FROM subscriptions WHERE subscription_id = ?");
    $stmt->execute([$subscription_id]);
    $subscription = $stmt->fetch();

    if (!$subscription) {
        echo "Subscription not found.";
        exit();
    }

    $duration = $subscription['duration_days'];
    echo "Duration: $duration days<br>";

    $start_date = date('Y-m-d');
    $end_date = date('Y-m-d', strtotime("+$duration days"));

    echo "Start: $start_date | End: $end_date<br>";

    // Step 4: Insert into user_subscriptions
    try {
        $stmt = $conn->prepare("INSERT INTO user_subscriptions (user_id, subscription_id, start_date, end_date, status)
                                VALUES (?, ?, ?, ?, 'active')");
        $stmt->execute([$user_id, $subscription_id, $start_date, $end_date]);

        echo "Subscription added!";
    } catch (PDOException $e) {
        echo "Insert error: " . $e->getMessage();
    }

    exit(); // Prevent redirect while debugging
}
?>

