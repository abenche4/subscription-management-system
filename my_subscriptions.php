<?php
session_start();
require 'php/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT us.user_sub_id, s.service_name, us.start_date, us.end_date, us.status, s.price
    FROM user_subscriptions us
    JOIN subscriptions s ON us.subscription_id = s.subscription_id
    WHERE us.user_id = ?
");
$stmt->execute([$user_id]);
$subs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
  <title>My Subscriptions</title>
</head>
<body>
  <h2>Your Subscriptions</h2>

  <?php foreach ($subs as $sub): ?>
    <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
      <strong><?php echo htmlspecialchars($sub['service_name']); ?></strong><br>
      Status: <?php echo $sub['status']; ?><br>
      Start: <?php echo $sub['start_date']; ?><br>
      End: <?php echo $sub['end_date']; ?><br>
      Price: $<?php echo $sub['price']; ?><br>

      <?php if ($sub['status'] === 'active'): ?>
        <form action="php/renew.php" method="POST" style="display:inline;">
          <input type="hidden" name="user_sub_id" value="<?php echo $sub['user_sub_id']; ?>">
          <input type="hidden" name="price" value="<?php echo $sub['price']; ?>">
          <input type="submit" value="Renew">
        </form>

        <form action="php/cancel.php" method="POST" style="display:inline;">
          <input type="hidden" name="user_sub_id" value="<?php echo $sub['user_sub_id']; ?>">
          <input type="submit" value="Cancel">
        </form>
      <?php else: ?>
        <em>(Cancelled)</em>
      <?php endif; ?>
    </div>
  <?php endforeach; ?>
</body>
</html>
