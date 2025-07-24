<?php
require_once '../config/db.php';

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    $stmt = $pdo->prepare("SELECT id FROM articles WHERE id = ?");
    $stmt->execute([$id]);
    if ($stmt->fetch()) {
        // Supprimer l'article
        $stmt = $pdo->prepare("DELETE FROM articles WHERE id = ?");
        $stmt->execute([$id]);
    }
}

header('Location: ../index.php');
exit;
