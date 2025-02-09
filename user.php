<?php
header("Access-Control-Allow-Origin: *"); // Allow requests from all origins
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Specify allowed methods
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Specify allowed headers
require 'config.php'; // Ensure this includes the PDO connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['register'])) {
        // Register User
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->execute([$username, $password]);

        echo json_encode(["message" => "User registered successfully"]);
        exit;
    } 

    if (isset($_POST['username']) && isset($_POST['password'])) {
        // Login User
        $username = $_POST['username'];
        $password = $_POST['password'];

        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                if (password_verify($password, $user['password'])) {
                    echo json_encode(["success" => true, "message" => "Login successful! Welcome, " . htmlspecialchars($user['username']) . "!"]);
                } else {
                    echo json_encode(["success" => false, "message" => "Invalid password."]);
                }
            } else {
                echo json_encode(["success" => false, "message" => "User not found."]);
            }
        } catch (Exception $e) {
            echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
        }
        exit;
    }
}
?>
