<?php
require_once '../config/db.php';

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    header('Location: ../index.php');
    exit;
}

// Récupérer l'article à modifier
$stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
$stmt->execute([$id]);
$article = $stmt->fetch();

if (!$article) {
    header('Location: ../index.php');
    exit;
}

// Récupérer les catégories
$stmtCat = $pdo->query("SELECT * FROM categories ORDER BY libelle");
$categories = $stmtCat->fetchAll();

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $contenu = trim($_POST['contenu'] ?? '');
    $categorie = (int)($_POST['categorie'] ?? 0);

    if (!$titre) $errors[] = "Le titre est obligatoire.";
    if (!$contenu) $errors[] = "Le contenu est obligatoire.";
    if ($categorie <= 0) $errors[] = "La catégorie est obligatoire.";

    // Gestion de l'image uploadée
    if (!empty($_FILES['image']['name'])) {
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        $file_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $file_tmp = $_FILES['image']['tmp_name'];

        if (!in_array($file_ext, $allowed_ext)) {
            $errors[] = "Format d'image non supporté. Formats autorisés : jpg, jpeg, png, gif.";
        } elseif ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "Erreur lors du téléchargement de l'image.";
        } else {
            // Générer un nom unique
            $new_filename = uniqid('img_') . '.' . $file_ext;
            $destination = __DIR__ . '/../uploads/' . $new_filename;

            if (!move_uploaded_file($file_tmp, $destination)) {
                $errors[] = "Impossible d'enregistrer l'image.";
            }
        }
    }

    if (empty($errors)) {
        if (isset($new_filename)) {
            // Mise à jour avec image
            $stmtUpdate = $pdo->prepare("UPDATE articles SET titre = ?, contenu = ?, categorie = ?, image = ? WHERE id = ?");
            $stmtUpdate->execute([$titre, $contenu, $categorie, $new_filename, $id]);
        } else {
            // Mise à jour sans changer l'image
            $stmtUpdate = $pdo->prepare("UPDATE articles SET titre = ?, contenu = ?, categorie = ? WHERE id = ?");
            $stmtUpdate->execute([$titre, $contenu, $categorie, $id]);
        }
        header('Location: ../article.php?id=' . $id);
        exit;
    }
} else {
    // Préremplir le formulaire avec les données existantes
    $titre = $article['titre'];
    $contenu = $article['contenu'];
    $categorie = $article['categorie'];
}

$page_title = "Modifier l'article";
include '../includes/header.php';
?>

<h2>Modifier l'article</h2>

<?php if ($errors): ?>
<div style="color: red;">
    <ul>
        <?php foreach ($errors as $e): ?>
        <li><?php echo htmlspecialchars($e); ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<form method="post" action="" enctype="multipart/form-data">
    <label for="titre">Titre :</label><br>
    <input type="text" id="titre" name="titre" value="<?php echo htmlspecialchars($titre); ?>" required><br><br>

    <label for="contenu">Contenu :</label><br>
    <textarea id="contenu" name="contenu" rows="10" required><?php echo htmlspecialchars($contenu); ?></textarea><br><br>

    <label for="categorie">Catégorie :</label><br>
    <select id="categorie" name="categorie" required>
        <option value="">-- Sélectionnez --</option>
        <?php foreach ($categories as $cat): ?>
            <option value="<?php echo $cat['id']; ?>" <?php if ($categorie == $cat['id']) echo 'selected'; ?>>
                <?php echo htmlspecialchars($cat['libelle']); ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <label for="image">Image :</label><br>
    <?php if (!empty($article['image'])): ?>
        <img src="../uploads/<?php echo htmlspecialchars($article['image']); ?>" alt="Image article" style="max-width:200px; margin-bottom: 1rem;"><br>
    <?php endif; ?>
    <input type="file" id="image" name="image" accept="image/*"><br><br>

    <button type="submit" class="btn">Enregistrer</button>
</form>

<p><a href="../article.php?id=<?php echo $id; ?>" class="btn" style="margin-top: 1rem;">← Retour à l'article</a></p>

<?php include '../includes/footer.php'; ?>
