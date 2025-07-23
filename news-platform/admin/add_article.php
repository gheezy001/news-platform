<?php
require_once '../config/db.php';

$page_title = "Ajouter un article";

// Récupérer les catégories pour le select
$stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
$categories = $stmt->fetchAll();

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $category_id = (int)($_POST['category_id'] ?? 0);

    if (!$title) {
        $errors[] = "Le titre est obligatoire.";
    }
    if (!$content) {
        $errors[] = "Le contenu est obligatoire.";
    }
    if ($category_id <= 0) {
        $errors[] = "La catégorie est obligatoire.";
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO articles (title, content, category_id, published_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$title, $content, $category_id]);
        header("Location: ../index.php");
        exit;
    }
}

include '../includes/header.php';
?>

<h2>Ajouter un article</h2>

<?php if ($errors): ?>
    <div style="color: red;">
        <ul>
            <?php foreach ($errors as $e): ?>
                <li><?php echo htmlspecialchars($e); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post" action="">
    <label for="title">Titre :</label><br>
    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($_POST['title'] ?? '') ?>" required><br><br>

    <label for="content">Contenu :</label><br>
    <textarea id="content" name="content" rows="10" required><?php echo htmlspecialchars($_POST['content'] ?? '') ?></textarea><br><br>

    <label for="category_id">Catégorie :</label><br>
    <select id="category_id" name="category_id" required>
        <option value="">-- Sélectionnez --</option>
        <?php foreach ($categories as $cat): ?>
            <option value="<?php echo $cat['id']; ?>" <?php if (isset($_POST['category_id']) && $_POST['category_id'] == $cat['id']) echo 'selected'; ?>>
                <?php echo htmlspecialchars($cat['name']); ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <button type="submit" class="btn">Publier</button>
</form>

<p><a href="../index.php" class="btn" style="margin-top: 1rem;">← Retour à l'accueil</a></p>

<?php include '../includes/footer.php'; ?>
