<?php
require_once 'config/db.php';
$page_title = "Catégories";
include 'includes/header.php';

$stmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
$categories = $stmt->fetchAll();
?>

<h2>Catégories</h2>
<ul>
<?php foreach ($categories as $cat): ?>
    <li>
        <a href="category.php?id=<?php echo $cat['id']; ?>">
            <?php echo htmlspecialchars($cat['name']); ?>
        </a>
    </li>
<?php endforeach; ?>
</ul>

<?php include 'includes/footer.php'; ?>
