<?php
require_once 'config/db.php';
$id = (int)($_GET['id'] ?? 0);

// Récupérer le nom de la catégorie
$stmtCat = $pdo->prepare("SELECT name FROM categories WHERE id = ?");
$stmtCat->execute([$id]);
$cat = $stmtCat->fetch();

if (!$cat) {
    header("Location: index.php");
    exit;
}

// Récupérer les articles dans la catégorie
$stmt = $pdo->prepare("SELECT * FROM articles WHERE category_id = ? ORDER BY published_at DESC");
$stmt->execute([$id]);
$articles = $stmt->fetchAll();

$page_title = "Catégorie : " . $cat['name'];
include 'includes/header.php';
?>

<h2>Articles dans "<?php echo htmlspecialchars($cat['name']); ?>"</h2>
<?php foreach ($articles as $article): ?>
    <div class="card">
        <h3><a href="article.php?id=<?php echo $article['id']; ?>"><?php echo htmlspecialchars($article['title']); ?></a></h3>
        <p><?php echo nl2br(htmlspecialchars(substr($article['content'], 0, 200))) . '...'; ?></p>
    </div>
<?php endforeach; ?>

<?php include 'includes/footer.php'; ?>
