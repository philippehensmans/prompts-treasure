<?php
require_once 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

$name = trim($_POST['name'] ?? '');

if (empty($name)) {
    echo json_encode(['success' => false, 'message' => 'Le nom est obligatoire']);
    exit;
}

try {
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO categories (name) VALUES (?)");
    $stmt->execute([$name]);
    $category_id = $db->lastInsertId();

    echo json_encode([
        'success' => true,
        'id' => $category_id,
        'name' => $name
    ]);
} catch (PDOException $e) {
    if ($e->getCode() == 23000) { // Code d'erreur pour contrainte unique
        echo json_encode(['success' => false, 'message' => 'Cette catégorie existe déjà']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'ajout: ' . $e->getMessage()]);
    }
}
?>
