<?php
require_once 'config.php';

// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$db = getDB();

$prompt_id = isset($_GET['id']) ? (int)$_GET['id'] : null;

if (!$prompt_id) {
    $_SESSION['error_message'] = 'Prompt non spécifié';
    header('Location: index.php');
    exit;
}

// Récupérer le prompt
$stmt = $db->prepare("SELECT p.*, c.name as category_name
                      FROM prompts p
                      LEFT JOIN categories c ON p.category_id = c.id
                      WHERE p.id = ?");
$stmt->execute([$prompt_id]);
$prompt = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$prompt) {
    $_SESSION['error_message'] = 'Prompt non trouvé';
    header('Location: index.php');
    exit;
}

$page_title = h($prompt['title']) . ' - Prompts Manager';
require_once 'includes/header.php';
?>

<div class="view-container">
    <div class="view-header">
        <div>
            <h2><?php echo h($prompt['title']); ?></h2>
            <?php if ($prompt['category_name']): ?>
                <span class="badge"><?php echo h($prompt['category_name']); ?></span>
            <?php endif; ?>
        </div>
        <div class="actions">
            <a href="form.php?id=<?php echo $prompt['id']; ?>" class="btn btn-primary">Modifier</a>
            <form method="POST" action="delete.php" style="display: inline;"
                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce prompt ?');">
                <input type="hidden" name="id" value="<?php echo $prompt['id']; ?>">
                <button type="submit" class="btn btn-danger">Supprimer</button>
            </form>
        </div>
    </div>

    <?php if (!empty($prompt['explanation'])): ?>
        <div class="section">
            <h3>Explication</h3>
            <p><?php echo nl2br(h($prompt['explanation'])); ?></p>
        </div>
    <?php endif; ?>

    <?php if (!empty($prompt['llm'])): ?>
        <div class="section">
            <h3>LLM spécifique</h3>
            <p><strong><?php echo h($prompt['llm']); ?></strong></p>
        </div>
    <?php endif; ?>

    <div class="section">
        <div class="section-header">
            <h3>Prompt</h3>
            <button onclick="copyCode()" class="btn btn-small btn-secondary">Copier</button>
        </div>
        <pre id="promptCode"><code><?php echo h($prompt['code']); ?></code></pre>
    </div>

    <div class="section metadata">
        <p><strong>Créé le :</strong> <?php echo date('d/m/Y H:i', strtotime($prompt['created_at'])); ?></p>
        <p><strong>Modifié le :</strong> <?php echo date('d/m/Y H:i', strtotime($prompt['updated_at'])); ?></p>
    </div>

    <div class="back-link">
        <a href="index.php" class="btn btn-secondary">← Retour à la liste</a>
    </div>
</div>

<?php
$extra_js = <<<'JAVASCRIPT'
<script>
function copyCode() {
    const code = document.getElementById('promptCode').textContent;
    navigator.clipboard.writeText(code).then(function() {
        alert('Prompt copié dans le presse-papier !');
    }, function(err) {
        alert('Erreur lors de la copie : ' + err);
    });
}
</script>
JAVASCRIPT;

require_once 'includes/footer.php';
?>
