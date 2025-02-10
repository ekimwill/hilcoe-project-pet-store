<?php
session_start();
require 'config.php';

// Get 'action' from the URL query string (e.g., ?action=login or ?action=add_admin)
$action = $_GET['action'] ?? '';

if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // ROUTE 1: LOGIN
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Fetch admin by username
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    // Validate password
    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin'] = $admin['id'];
        echo "Login successful";
    } else {
        echo "Invalid credentials";
    }
    exit;

} elseif ($action === 'add_admin' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // ROUTE 2: ADD ADMIN
    $newUsername = $_POST['username'] ?? '';
    $newPassword = $_POST['password'] ?? '';

    if (empty($newUsername) || empty($newPassword)) {
        echo "Username and password are required";
        exit;
    }

    // Check if username already exists
    $checkStmt = $pdo->prepare("SELECT id FROM admins WHERE username = ?");
    $checkStmt->execute([$newUsername]);
    $existing = $checkStmt->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        echo "Username already exists";
        exit;
    }

    // Hash the password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Insert new admin
    $insertStmt = $pdo->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
    if ($insertStmt->execute([$newUsername, $hashedPassword])) {
        echo "Admin added successfully";
    } else {
        echo "Failed to add admin";
    }
    exit;

} else {
    // If none of the above routes match, show an error or do nothing
    echo "Invalid route or method";
    exit;
}
