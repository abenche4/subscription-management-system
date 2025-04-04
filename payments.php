<?php
session_start();
require 'php/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT s.service_name, p.amount, p.payment_date
    FROM payments p
    JOIN subscriptions s ON p.subscription_id = s.subscription_id
    WHERE p.user_id = ?
    ORDER BY p.payment_date DESC
");
$stmt->execute([$user_id]);
$payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Payment History</title>
</head>
<body>
  <h2>Payment History</h2>

  <?php if (count($payments) === 0): ?>
    <p>No payments found.</p>
  <?php else: ?>
    <table border="1" cellpadding="10">
      <tr>
        <th>Service</th>
        <th>Amount</th>
        <th>Date</th>
      </tr>
      <?php foreach ($payments as $pay): ?>
        <tr>
          <td><?php echo htmlspecialchars($pay['service_name']); ?></td>
          <td>$<?php echo $pay['amount']; ?></td>
          <td><?php echo $pay['payment_date']; ?></td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php endif; ?>
</body>
</html>
