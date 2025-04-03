<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Dashboard</title>
</head>
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

require 'php/db_connect.php';

// Fetch subscriptions from database
$stmt = $conn->query("SELECT * FROM subscriptions");
$subscriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Dashboard</title>
</head>
<body>
  <h2>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!</h2>
  <p>Available Subscriptions:</p>

  <?php foreach ($subscriptions as $sub): ?>
    <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
      <strong><?php echo htmlspecialchars($sub['service_name']); ?></strong><br>
      <?php echo htmlspecialchars($sub['description']); ?><br>
      Duration: <?php echo $sub['duration_days']; ?> days<br>
      Price: $<?php echo $sub['price']; ?><br>
      <form action="php/subscribe.php" method="POST">
        <input type="hidden" name="subscription_id" value="<?php echo $sub['subscription_id']; ?>">
        <input type="submit" value="Subscribe">
      </form>
    </div>
  <?php endforeach; ?>
</body>
</html>

