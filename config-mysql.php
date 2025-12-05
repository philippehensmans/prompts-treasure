<?php
// Configuration MySQL pour Prompts Manager
// À personnaliser selon votre serveur

// Configuration de la base de données MySQL
define('DB_HOST', 'localhost');
define('DB_NAME', 'prompts_db');  // Nom de votre base de données
define('DB_USER', 'your_username'); // Votre nom d'utilisateur MySQL
define('DB_PASS', 'your_password'); // Votre mot de passe MySQL
define('DB_CHARSET', 'utf8mb4');

// Fonction pour obtenir la connexion à la base de données
function getDB() {
    try {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $db = new PDO($dsn, DB_USER, DB_PASS, $options);
        return $db;
    } catch(PDOException $e) {
        die("Erreur de connexion à la base de données: " . $e->getMessage());
    }
}

// Initialiser la base de données
function initDB() {
    $db = getDB();

    // Table des catégories
    $db->exec("CREATE TABLE IF NOT EXISTS categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL UNIQUE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    // Table des prompts
    $db->exec("CREATE TABLE IF NOT EXISTS prompts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(500) NOT NULL,
        explanation TEXT,
        code TEXT NOT NULL,
        llm VARCHAR(255),
        category_id INT,
        suggested_by_email VARCHAR(255),
        example_link VARCHAR(1000),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
        INDEX idx_category (category_id),
        INDEX idx_title (title(100))
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    // Vérifier si des catégories existent
    $stmt = $db->query("SELECT COUNT(*) as count FROM categories");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result['count'] == 0) {
        // Ajouter des catégories par défaut
        $default_categories = [
            'Génération de code',
            'Analyse de données',
            'Rédaction',
            'Traduction',
            'Débogage',
            'Documentation',
            'Autre'
        ];

        $stmt = $db->prepare("INSERT INTO categories (name) VALUES (?)");
        foreach ($default_categories as $category) {
            $stmt->execute([$category]);
        }
    }
}

// Initialiser la base de données au chargement
initDB();

// Fonction pour échapper le HTML
function h($text) {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}
?>
