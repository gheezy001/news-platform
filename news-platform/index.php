<?php
require_once 'config/db.php';

$page_title = "Accueil";
$limit = 5;
$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;

$stmt = $pdo->prepare("
    SELECT a.*, c.name as categorie_nom 
    FROM articles a 
    LEFT JOIN categories c ON a.category_id = c.id 
    ORDER BY a.published_at DESC 
    LIMIT :offset, :limit
");
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->execute();
$articles = $stmt->fetchAll();

$total_stmt = $pdo->query("SELECT COUNT(*) FROM articles");
$total_articles = $total_stmt->fetchColumn();
$total_pages = ceil($total_articles / $limit);

include 'includes/header.php';
?>

<h2>Derniers articles</h2>
<?php foreach ($articles as $article): ?>
    <div class="card">
        <h3><a href="article.php?id=<?php echo $article['id']; ?>"><?php echo htmlspecialchars($article['title']); ?></a></h3>
        <p><small>Catégorie: <a href="category.php?id=<?php echo $article['category_id']; ?>"><?php echo htmlspecialchars($article['categorie_nom']); ?></a> | Publié le <?php echo date('d/m/Y', strtotime($article['published_at'])); ?></small></p>
        <p><?php echo nl2br(htmlspecialchars(substr($article['content'], 0, 200))) . '...'; ?></p>
    </div>
<?php endforeach; ?>

<!-- Pagination -->
<div style="text-align:center; margin-top: 2rem;">
    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a class="btn" href="?page=<?php echo $i; ?>" style="margin: 0 5px;<?php if ($i == $page) echo ' background:#555;'; ?>">
            <?php echo $i; ?>
        </a>
    <?php endfor; ?>
</div>

<?php include 'includes/footer.php'; ?>
