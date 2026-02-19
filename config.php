<?php
// Configuration de la base de données
define('DB_PATH', __DIR__ . '/prompts.db');

// Fonction pour obtenir la connexion à la base de données
function getDB() {
    try {
        $db = new PDO('sqlite:' . DB_PATH);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL UNIQUE
    )");

    // Table des prompts
    $db->exec("CREATE TABLE IF NOT EXISTS prompts (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        explanation TEXT,
        code TEXT NOT NULL,
        llm TEXT,
        category_id INTEGER,
        suggested_by_email TEXT,
        example_link TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (category_id) REFERENCES categories(id)
    )");

    // Ajouter les colonnes si elles n'existent pas (pour les bases existantes)
    try {
        $db->exec("ALTER TABLE prompts ADD COLUMN suggested_by_email TEXT");
    } catch(PDOException $e) {
        // La colonne existe déjà, on ignore l'erreur
    }

    try {
        $db->exec("ALTER TABLE prompts ADD COLUMN example_link TEXT");
    } catch(PDOException $e) {
        // La colonne existe déjà, on ignore l'erreur
    }

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
