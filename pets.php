<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    require 'config.php';

    if ($_POST['action'] === 'add') {
        $name = $_POST['name'];
        $type = $_POST['type'];
        $stmt = $pdo->prepare("INSERT INTO pets (name, type) VALUES (?, ?)");
        $stmt->execute([$name, $type]);
        echo "Pet added successfully";
    }
    
    if ($_POST['action'] === 'edit') {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $type = $_POST['type'];
        $stmt = $pdo->prepare("UPDATE pets SET name = ?, type = ? WHERE id = ?");
        $stmt->execute([$name, $type, $id]);
        echo "Pet updated successfully";
    }
    
    if ($_POST['action'] === 'delete') {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM pets WHERE id = ?");
        $stmt->execute([$id]);
        echo "Pet deleted successfully";
    }
}

// Endpoint to fetch available pets
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['view_pets'])) {
    require 'config.php';
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM pets");
        $stmt->execute();
        $pets = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($pets)) {
            echo "No pets found in the database.";
        } else {
            header('Content-Type: application/json');
            echo json_encode($pets);
        }
    } catch (PDOException $e) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Failed to fetch pets: ' . $e->getMessage()]);
    }
}
?>