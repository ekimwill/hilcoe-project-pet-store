<?php
$host = 'localhost:3309'; 
$dbname = 'petstore';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    echo "Connection successful!";
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
