<?php
$page_title = 'Liste des prompts - Prompts Manager';
require_once 'includes/header.php';

$db = getDB();

// Récupérer les filtres
$category_id = isset($_GET['category']) ? (int)$_GET['category'] : null;
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';

// Construire la requête
$sql = "SELECT p.*, c.name as category_name
        FROM prompts p
        LEFT JOIN categories c ON p.category_id = c.id";

$params = [];
$where_clauses = [];

if (!empty($search_query)) {
    $where_clauses[] = "(p.title LIKE ? OR p.explanation LIKE ? OR p.code LIKE ?)";
    $search_param = '%' . $search_query . '%';
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
}

if ($category_id) {
    $where_clauses[] = "p.category_id = ?";
    $params[] = $category_id;
}

if (!empty($where_clauses)) {
    $sql .= " WHERE " . implode(" AND ", $where_clauses);
}

$sql .= " ORDER BY p.created_at DESC";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$prompts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer toutes les catégories
$categories = $db->query("SELECT * FROM categories ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="filters-section">
    <div class="search-box">
        <form method="GET" action="index.php">
            <input type="text" name="search" placeholder="Rechercher un prompt..." value="<?php echo h($search_query); ?>">
            <button type="submit" class="btn btn-primary">Rechercher</button>
            <?php if (!empty($search_query)): ?>
                <a href="index.php" class="btn btn-secondary">Effacer</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="category-filter">
        <label>Filtrer par catégorie :</label>
        <a href="index.php" class="category-tag <?php echo !$category_id ? 'active' : ''; ?>">
            Toutes
        </a>
        <?php foreach ($categories as $category): ?>
            <a href="index.php?category=<?php echo $category['id']; ?>"
               class="category-tag <?php echo $category_id == $category['id'] ? 'active' : ''; ?>">
                <?php echo h($category['name']); ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<div class="prompts-grid">
    <?php if (count($prompts) > 0): ?>
        <?php foreach ($prompts as $prompt): ?>
            <div class="prompt-card">
                <div class="prompt-header">
                    <h3><?php echo h($prompt['title']); ?></h3>
                    <?php if ($prompt['category_name']): ?>
                        <span class="badge"><?php echo h($prompt['category_name']); ?></span>
                    <?php endif; ?>
                </div>

                <?php if (!empty($prompt['explanation'])): ?>
                    <p class="prompt-explanation">
                        <?php
                        $explanation = h($prompt['explanation']);
                        echo mb_strlen($explanation) > 150 ? mb_substr($explanation, 0, 150) . '...' : $explanation;
                        ?>
                    </p>
                <?php endif; ?>

                <?php if (!empty($prompt['llm'])): ?>
                    <div class="llm-info">
                        <strong>LLM :</strong> <?php echo h($prompt['llm']); ?>
                    </div>
                <?php endif; ?>

                <div class="code-preview">
                    <code>
                        <?php
                        $code = h($prompt['code']);
                        echo mb_strlen($code) > 100 ? mb_substr($code, 0, 100) . '...' : $code;
                        ?>
                    </code>
                </div>

                <div class="prompt-footer">
                    <small>Créé le : <?php echo date('d/m/Y H:i', strtotime($prompt['created_at'])); ?></small>
                    <div class="actions">
                        <a href="view.php?id=<?php echo $prompt['id']; ?>" class="btn btn-small btn-primary">Voir</a>
                        <a href="form.php?id=<?php echo $prompt['id']; ?>" class="btn btn-small btn-secondary">Modifier</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="empty-state">
            <p>Aucun prompt trouvé.</p>
            <a href="form.php" class="btn btn-primary">Créer votre premier prompt</a>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
