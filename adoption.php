<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply_adoption'])) {
    require 'config.php';

    $user_id = $_POST['user_id'];
    $pet_id = $_POST['pet_id'];
    $name = $_POST['name'];
    $age = $_POST['age'];
    $description = $_POST['description'];
    $image = $_POST['image'];

    // Insert adoption request
    $stmt = $pdo->prepare("INSERT INTO adoptions (user_id, pet_id, name, age, description, image, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')");
    $stmt->execute([$user_id, $pet_id, $name, $age, $description, $image]);

    echo json_encode(["message" => "Adoption request submitted successfully"]);
    exit;
}

// Approve adoption
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['approve_adoption'])) {
    require 'config.php';
    $adoption_id = $_POST['adoption_id'];

    $stmt = $pdo->prepare("UPDATE adoptions SET status = 'approved' WHERE id = ?");
    $stmt->execute([$adoption_id]);

    echo json_encode(["message" => "Adoption approved successfully"]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['view_adoptions'])) {
    require 'config.php';
    $stmt = $pdo->query("SELECT * FROM adoptions");
    $adoptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    header('Content-Type: application/json');
    echo json_encode($adoptions);
    exit;
}

?>
