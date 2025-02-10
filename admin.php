<?php
session_start();
require 'config.php';

// Get 'action' from the URL query string (e.g., ?action=login)
$action = $_GET['action'] ?? '';

// Only route: login
if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Fetch admin by username
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    // Directly compare the password from the form with the DB (no hashing)
    if ($admin && $password == $admin['password']) {
        $_SESSION['admin'] = $admin['id'];
        echo "Login successful";
    } else {
        echo "Invalid credentials";
    }
    exit;

} else {
    echo "Invalid route or method";
    exit;
}
