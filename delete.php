<?php
require_once 'config.php';

// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$prompt_id = isset($_POST['id']) ? (int)$_POST['id'] : null;

if (!$prompt_id) {
    $_SESSION['error_message'] = 'Prompt non spécifié';
    header('Location: index.php');
    exit;
}

try {
    $db = getDB();
    $stmt = $db->prepare("DELETE FROM prompts WHERE id = ?");
    $stmt->execute([$prompt_id]);

    $_SESSION['success_message'] = 'Prompt supprimé avec succès';
} catch (PDOException $e) {
    $_SESSION['error_message'] = 'Erreur lors de la suppression: ' . $e->getMessage();
}

header('Location: index.php');
exit;
?>
