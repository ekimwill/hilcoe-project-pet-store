<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply_adoption'])) {
    require 'config.php';
    $user_id = $_POST['user_id'];
    $pet_id = $_POST['pet_id'];
    
    $stmt = $pdo->prepare("INSERT INTO adoptions (user_id, pet_id, status) VALUES (?, ?, 'pending')");
    $stmt->execute([$user_id, $pet_id]);
    echo "Adoption request submitted";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['approve_adoption'])) {
    require 'config.php';
    $adoption_id = $_POST['adoption_id'];
    
    $stmt = $pdo->prepare("UPDATE adoptions SET status = 'approved' WHERE id = ?");
    $stmt->execute([$adoption_id]);
    echo "Adoption approved";
}
?>
