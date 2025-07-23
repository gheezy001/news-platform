<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php echo htmlspecialchars($page_title ?? "News Platform"); ?></title>
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>
    <header>
        <h1><a href="index.php">📰 News Platform</a></h1>
        <nav>
            <a href="index.php">Accueil</a>
            <a href="categories.php">Catégories</a>
            <a href="admin/">Admin</a> <!-- optionnel -->
        </nav>
    </header>
    <main>
