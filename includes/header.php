<?php
// Inclure l'authentification
include __DIR__ . '/../auth.php';

// Inclure la configuration
require_once __DIR__ . '/../config.php';

// Démarrer la session si ce n'est pas déjà fait
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? h($page_title) : 'Prompts Manager'; ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <h1>
                <a href="index.php" class="logo-link">
                    <?php if (file_exists(__DIR__ . '/../images/logo.png')): ?>
                        <img src="images/logo.png" alt="Logo" class="logo">
                    <?php elseif (file_exists(__DIR__ . '/../images/logo.jpg')): ?>
                        <img src="images/logo.jpg" alt="Logo" class="logo">
                    <?php elseif (file_exists(__DIR__ . '/../images/logo.svg')): ?>
                        <img src="images/logo.svg" alt="Logo" class="logo">
                    <?php else: ?>
                        <span class="logo-text">📝</span>
                    <?php endif; ?>
                    <span>Prompts Manager</span>
                </a>
            </h1>
            <div class="nav-links">
                <a href="index.php" class="btn btn-secondary">Liste des prompts</a>
                <a href="form.php" class="btn btn-primary">Nouveau prompt</a>
            </div>
        </div>
    </nav>

    <main class="container">
        <?php
        // Afficher les messages flash
        if (isset($_SESSION['success_message'])) {
            echo '<div class="alert alert-success">' . h($_SESSION['success_message']) . '</div>';
            unset($_SESSION['success_message']);
        }
        if (isset($_SESSION['error_message'])) {
            echo '<div class="alert alert-error">' . h($_SESSION['error_message']) . '</div>';
            unset($_SESSION['error_message']);
        }
        ?>
