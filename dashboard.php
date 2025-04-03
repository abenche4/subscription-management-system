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
<body>
  <h2>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!</h2>
  <p>You are logged in.</p>
</body>
</html>
