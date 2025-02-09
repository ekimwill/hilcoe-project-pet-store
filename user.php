<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    require 'config.php';
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->execute([$username, $password]);
    echo "User registered successfully";
}

// Login Route
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    require 'config.php';
    require 'vendor/autoload.php';
    use Firebase\JWT\JWT;

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch the user from the database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Generate a JWT
            $secretKey = 'your_secret_key'; // Replace with a secure key
            $payload = [
                'user_id' => $user['id'],
                'username' => $user['username'],
                'exp' => time() + 3600 // Token expires in 1 hour
            ];
            $jwt = JWT::encode($payload, $secretKey, 'HS256');

            // Send the JWT to the client
            echo json_encode([
                'message' => 'Login successful!',
                'token' => $jwt
            ]);
        } else {
            echo json_encode(['error' => 'Invalid password.']);
        }
    } else {
        echo json_encode(['error' => 'User not found.']);
    }
}

function verifyJWT($token) {
    require 'vendor/autoload.php';
    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;

    $secretKey = 'your_secret_key'; // Replace with the same key used for encoding

    try {
        $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));
        return $decoded;
    } catch (Exception $e) {
        return null; // Token is invalid or expired
    }
}
?>