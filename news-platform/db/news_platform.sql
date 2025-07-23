-- Créer la base de données
CREATE DATABASE IF NOT EXISTS news_platform CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE news_platform;

-- Table des catégories
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

-- Table des articles
CREATE TABLE IF NOT EXISTS articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    image VARCHAR(255),
    published_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    category_id INT,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Données de test pour les catégories
INSERT INTO categories (name) VALUES
('Politique'),
('Technologie'),
('Économie'),
('Sport'),
('Santé');

-- Données de test pour les articles
INSERT INTO articles (title, content, image, category_id) VALUES
('Le gouvernement annonce une réforme', 'Le gouvernement a annoncé une nouvelle réforme pour moderniser le système éducatif...', 'education.jpg', 1),
('L\'IA transforme les entreprises', 'Les entreprises adoptent l’intelligence artificielle à grande échelle pour automatiser...', 'ai.jpg', 2),
('Croissance économique en 2025', 'Les analystes prévoient une croissance de 3.5% pour l’année 2025...', 'eco.jpg', 3),
('Victoire du Sénégal à la CAN', 'Le Sénégal remporte la finale de la Coupe d\'Afrique des Nations...', 'can.jpg', 4),
('Nouvelle campagne de vaccination', 'Le ministère de la Santé lance une campagne de vaccination contre la grippe...', 'sante.jpg', 5);
