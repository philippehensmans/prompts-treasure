<?php
$page_title = 'Liste des prompts - Prompts Manager';
require_once 'includes/header.php';

$db = getDB();

// Récupérer les filtres
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';

// Construire la requête
$sql = "SELECT p.*, c.name as category_name, c.id as category_id
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

if (!empty($where_clauses)) {
    $sql .= " WHERE " . implode(" AND ", $where_clauses);
}

$sql .= " ORDER BY c.name, p.title";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$prompts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Organiser les prompts par catégorie
$prompts_by_category = [];
foreach ($prompts as $prompt) {
    $cat_name = $prompt['category_name'] ?: 'Sans catégorie';
    if (!isset($prompts_by_category[$cat_name])) {
        $prompts_by_category[$cat_name] = [];
    }
    $prompts_by_category[$cat_name][] = $prompt;
}

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
</div>

<?php if (count($prompts) > 0): ?>
    <div class="prompts-by-category">
        <?php foreach ($prompts_by_category as $cat_name => $cat_prompts): ?>
            <div class="category-section">
                <h2 class="category-title">
                    📁 <?php echo h($cat_name); ?>
                    <span class="prompt-count">(<?php echo count($cat_prompts); ?>)</span>
                </h2>

                <div class="prompts-list">
                    <?php foreach ($cat_prompts as $prompt): ?>
                        <div class="prompt-item">
                            <div class="prompt-name">
                                <a href="view.php?id=<?php echo $prompt['id']; ?>">
                                    <?php echo h($prompt['title']); ?>
                                </a>
                                <div class="prompt-actions">
                                    <a href="form.php?id=<?php echo $prompt['id']; ?>" class="edit-icon" title="Modifier">✏️</a>
                                </div>
                            </div>

                            <!-- Popup au survol -->
                            <div class="prompt-popup">
                                <div class="popup-header">
                                    <h3><?php echo h($prompt['title']); ?></h3>
                                    <?php if ($prompt['category_name']): ?>
                                        <span class="badge"><?php echo h($prompt['category_name']); ?></span>
                                    <?php endif; ?>
                                </div>

                                <?php if (!empty($prompt['explanation'])): ?>
                                    <div class="popup-section">
                                        <strong>Explication :</strong>
                                        <p><?php echo nl2br(h($prompt['explanation'])); ?></p>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($prompt['llm'])): ?>
                                    <div class="popup-section">
                                        <strong>LLM :</strong> <?php echo h($prompt['llm']); ?>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($prompt['suggested_by_email'])): ?>
                                    <div class="popup-section">
                                        <strong>📧 Suggéré par :</strong>
                                        <a href="mailto:<?php echo h($prompt['suggested_by_email']); ?>">
                                            <?php echo h($prompt['suggested_by_email']); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($prompt['example_link'])): ?>
                                    <div class="popup-section">
                                        <strong>🔗 Exemple :</strong>
                                        <a href="<?php echo h($prompt['example_link']); ?>" target="_blank" rel="noopener noreferrer">
                                            Voir l'exemple
                                        </a>
                                    </div>
                                <?php endif; ?>

                                <div class="popup-section">
                                    <strong>Prompt :</strong>
                                    <pre class="popup-code"><?php echo h($prompt['code']); ?></pre>
                                </div>

                                <div class="popup-footer">
                                    <small>Créé le : <?php echo date('d/m/Y H:i', strtotime($prompt['created_at'])); ?></small>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="empty-state">
        <p>Aucun prompt trouvé.</p>
        <a href="form.php" class="btn btn-primary">Créer votre premier prompt</a>
    </div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>

