<?php
require_once 'config.php';

// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$db = getDB();

// Vérifier si c'est une modification
$prompt_id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$prompt = null;

if ($prompt_id) {
    $stmt = $db->prepare("SELECT * FROM prompts WHERE id = ?");
    $stmt->execute([$prompt_id]);
    $prompt = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$prompt) {
        $_SESSION['error_message'] = 'Prompt non trouvé';
        header('Location: index.php');
        exit;
    }
    $page_title = 'Modifier le prompt - Prompts Manager';
} else {
    $page_title = 'Nouveau prompt - Prompts Manager';
}

// Traiter le formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $explanation = trim($_POST['explanation'] ?? '');
    $code = trim($_POST['code'] ?? '');
    $llm = trim($_POST['llm'] ?? '');
    $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
    $suggested_by_email = trim($_POST['suggested_by_email'] ?? '');
    $example_link = trim($_POST['example_link'] ?? '');

    // Validation
    if (empty($title) || empty($code)) {
        $_SESSION['error_message'] = 'Le titre et le code sont obligatoires';
    } else {
        try {
            if ($prompt_id) {
                // Mise à jour
                $stmt = $db->prepare("UPDATE prompts
                                     SET title = ?, explanation = ?, code = ?, llm = ?,
                                         category_id = ?, suggested_by_email = ?, example_link = ?,
                                         updated_at = CURRENT_TIMESTAMP
                                     WHERE id = ?");
                $stmt->execute([$title, $explanation, $code, $llm, $category_id, $suggested_by_email, $example_link, $prompt_id]);
                $_SESSION['success_message'] = 'Prompt modifié avec succès';
                header('Location: view.php?id=' . $prompt_id);
            } else {
                // Création
                $stmt = $db->prepare("INSERT INTO prompts (title, explanation, code, llm, category_id, suggested_by_email, example_link)
                                     VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$title, $explanation, $code, $llm, $category_id, $suggested_by_email, $example_link]);
                $_SESSION['success_message'] = 'Prompt créé avec succès';
                header('Location: index.php');
            }
            exit;
        } catch (PDOException $e) {
            $_SESSION['error_message'] = 'Erreur lors de l\'enregistrement: ' . $e->getMessage();
        }
    }
}

// Récupérer les catégories
$categories = $db->query("SELECT * FROM categories ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

require_once 'includes/header.php';
?>

<div class="form-container">
    <h2><?php echo $prompt ? 'Modifier le prompt' : 'Nouveau prompt'; ?></h2>

    <form method="POST" class="prompt-form">
        <div class="form-group">
            <label for="title">Titre *</label>
            <input type="text" id="title" name="title" required
                   value="<?php echo $prompt ? h($prompt['title']) : ''; ?>"
                   placeholder="Ex: Prompt pour générer du code Python">
        </div>

        <div class="form-group">
            <label for="explanation">Explication</label>
            <textarea id="explanation" name="explanation" rows="4"
                      placeholder="Décrivez l'utilité de ce prompt et dans quel contexte l'utiliser"><?php echo $prompt ? h($prompt['explanation']) : ''; ?></textarea>
        </div>

        <div class="form-group">
            <label for="code">Code (Prompt) *</label>
            <textarea id="code" name="code" rows="10" required
                      placeholder="Collez votre prompt ici..."><?php echo $prompt ? h($prompt['code']) : ''; ?></textarea>
        </div>

        <div class="form-group">
            <label for="llm">LLM spécifique</label>
            <input type="text" id="llm" name="llm"
                   value="<?php echo $prompt ? h($prompt['llm']) : ''; ?>"
                   placeholder="Ex: GPT-4, Claude 3, Gemini, etc.">
            <small>Laissez vide si le prompt fonctionne avec tous les LLMs</small>
        </div>

        <div class="form-group">
            <label for="suggested_by_email">Email du suggéreur</label>
            <input type="email" id="suggested_by_email" name="suggested_by_email"
                   value="<?php echo $prompt ? h($prompt['suggested_by_email']) : ''; ?>"
                   placeholder="Ex: nom@exemple.com">
            <small>Email de la personne qui a suggéré ce prompt (optionnel)</small>
        </div>

        <div class="form-group">
            <label for="example_link">Lien vers un exemple</label>
            <input type="url" id="example_link" name="example_link"
                   value="<?php echo $prompt ? h($prompt['example_link']) : ''; ?>"
                   placeholder="Ex: https://exemple.com/demo">
            <small>URL vers un exemple d'utilisation de ce prompt (optionnel)</small>
        </div>

        <div class="form-group">
            <label for="category_id">Catégorie</label>
            <select id="category_id" name="category_id">
                <option value="">-- Aucune catégorie --</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['id']; ?>"
                            <?php echo ($prompt && $prompt['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                        <?php echo h($category['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="button" class="btn btn-small btn-secondary" onclick="addNewCategory()">
                Nouvelle catégorie
            </button>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <?php echo $prompt ? 'Mettre à jour' : 'Créer'; ?>
            </button>
            <a href="index.php" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<!-- Modal pour nouvelle catégorie -->
<div id="categoryModal" class="modal">
    <div class="modal-content">
        <h3>Nouvelle catégorie</h3>
        <form id="newCategoryForm">
            <div class="form-group">
                <label for="new_category_name">Nom de la catégorie</label>
                <input type="text" id="new_category_name" name="name" required>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Ajouter</button>
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Annuler</button>
            </div>
        </form>
    </div>
</div>

<?php
$extra_js = <<<'JAVASCRIPT'
<script>
function addNewCategory() {
    document.getElementById('categoryModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('categoryModal').style.display = 'none';
    document.getElementById('newCategoryForm').reset();
}

document.getElementById('newCategoryForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    try {
        const response = await fetch('add_category.php', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            // Ajouter la nouvelle catégorie au select
            const select = document.getElementById('category_id');
            const option = new Option(data.name, data.id);
            select.add(option);
            select.value = data.id;

            closeModal();
            alert('Catégorie ajoutée avec succès !');
        } else {
            alert('Erreur : ' + data.message);
        }
    } catch (error) {
        alert('Erreur lors de l\'ajout de la catégorie');
    }
});

// Fermer le modal en cliquant en dehors
window.onclick = function(event) {
    const modal = document.getElementById('categoryModal');
    if (event.target == modal) {
        closeModal();
    }
}
</script>
JAVASCRIPT;

require_once 'includes/footer.php';
?>
