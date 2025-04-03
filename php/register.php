<?php

echo "Script reached<br>";

require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $hashed_password]);
        echo "Registration successful!";
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            echo "This email is already registered.";
        } else {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>
