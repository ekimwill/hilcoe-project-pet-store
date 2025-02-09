<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'config.php';
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin'] = $admin['id'];
        echo "Login successful";
    } else {
        echo "Invalid credentials";
    }
}
?>