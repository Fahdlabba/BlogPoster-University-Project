CREATE DATABASE IF NOT EXISTS blog_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


USE blog_db;


CREATE TABLE IF NOT EXISTS articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    category VARCHAR(50) NOT NULL,
    date DATE NOT NULL,
    image VARCHAR(500),
    excerpt TEXT,
    reading_time VARCHAR(20),
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    article_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    text TEXT NOT NULL,
    date DATETIME NOT NULL,
    is_approved TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_article_slug ON articles(slug);
CREATE INDEX idx_article_category ON articles(category);
CREATE INDEX idx_comment_article ON comments(article_id);
CREATE INDEX idx_comment_approved ON comments(is_approved);


INSERT INTO articles (title, slug, category, date, image, excerpt, reading_time, content) VALUES
(
    'Introduction au JavaScript Moderne',
    'introduction-javascript-moderne',
    'javascript',
    '2025-11-10',
    'https://images.unsplash.com/photo-1579468118864-1b9ea3c0db4a?w=800&h=400&fit=crop',
    'Découvrez les fonctionnalités essentielles d\'ES6+ qui transforment la façon dont nous écrivons du JavaScript aujourd\'hui.',
    '5 min',
    '<p>JavaScript a considérablement évolué ces dernières années avec l\'introduction d\'ES6 (ECMAScript 2015) et des versions suivantes.</p>'
),
(
    'Les Principes du Design Minimaliste',
    'principes-design-minimaliste',
    'design',
    '2025-11-08',
    'https://images.unsplash.com/photo-1586717791821-3f44a563fa4c?w=800&h=400&fit=crop',
    'Le design minimaliste n\'est pas seulement une esthétique, c\'est une philosophie qui met l\'accent sur l\'essentiel.',
    '7 min',
    '<p>Le design minimaliste est bien plus qu\'une simple tendance esthétique.</p>'
),
(
    'Construire une API RESTful avec Node.js',
    'api-restful-nodejs',
    'web',
    '2025-11-05',
    'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=800&h=400&fit=crop',
    'Apprenez à créer une API REST robuste et scalable avec Node.js et Express.',
    '10 min',
    '<p>Les API RESTful sont devenues le standard pour la communication entre applications web.</p>'
);
