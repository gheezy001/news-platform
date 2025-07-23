<?php
require_once 'config/db.php';

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("
    SELECT a.*, c.name AS categorie_nom 
    FROM articles a 
    LEFT JOIN categories c ON a.category_id = c.id 
    WHERE a.id = ?
");
$stmt->execute([$id]);
$article = $stmt->fetch();

if (!$article) {
    header("Location: index.php");
    exit;
}

$page_title = $article['title'];
include 'includes/header.php';
?>

<article class="card">
    <h2><?php echo htmlspecialchars($article['title']); ?></h2>
    <p>
        <small>
            Catégorie: 
            <a href="category.php?id=<?php echo $article['category_id']; ?>">
                <?php echo htmlspecialchars($article['categorie_nom']); ?>
            </a> 
            | Publié le <?php echo date('d/m/Y', strtotime($article['published_at'])); ?>
        </small>
    </p>

    <?php if (!empty($article['image'])): ?>
        <img src="uploads/<?php echo htmlspecialchars($article['image']); ?>" alt="Image de l'article" style="max-width: 100%; margin-bottom: 1rem;">
    <?php endif; ?>

    <div>
        <?php echo nl2br(htmlspecialchars($article['content'])); ?>
    </div>
</article>

<?php include 'includes/footer.php'; ?>
