<?php
$host = 'localhost';
$dbname = 'news_platform';
$username = 'root';
$password = ''; // Laisser vide si tu es sur XAMPP et tu n’as pas mis de mot de passe MySQL

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données: " . $e->getMessage());
}
