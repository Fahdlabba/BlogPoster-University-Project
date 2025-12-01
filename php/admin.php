<?php

require_once 'config.php';


$admin_password = 'admin123'; 

session_start();
$isLoggedIn = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
    if ($_POST['password'] === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
        $isLoggedIn = true;
    } else {
        $loginError = 'Mot de passe incorrect';
    }
}


if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin.php');
    exit();
}


if (!$isLoggedIn) {
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Login - Blog</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body {
                font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
                background: #f8fafc;
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
            }
            .login-container {
                background: white;
                padding: 2.5rem;
                border-radius: 0.75rem;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
                width: 100%;
                max-width: 400px;
                border: 1px solid #e2e8f0;
            }
            h1 { 
                margin-bottom: 1.5rem; 
                text-align: center; 
                color: #1e293b;
                font-weight: 600;
                font-size: 1.5rem;
            }
            .form-group { margin-bottom: 1rem; }
            label { 
                display: block; 
                margin-bottom: 0.5rem; 
                color: #64748b; 
                font-weight: 500;
                font-size: 0.875rem;
            }
            input[type="password"] {
                width: 100%;
                padding: 0.75rem;
                border: 2px solid #e2e8f0;
                border-radius: 0.5rem;
                font-size: 1rem;
                font-family: inherit;
                transition: border-color 0.2s ease;
            }
            input[type="password"]:focus {
                outline: none;
                border-color: #2563eb;
            }
            button {
                width: 100%;
                padding: 0.75rem;
                background: #2563eb;
                color: white;
                border: none;
                border-radius: 0.5rem;
                font-size: 1rem;
                font-weight: 600;
                cursor: pointer;
                transition: background-color 0.2s ease;
                font-family: inherit;
            }
            button:hover { background: #1e40af; }
            .error {
                background: #fee2e2;
                color: #991b1b;
                padding: 0.75rem;
                border-radius: 0.5rem;
                margin-bottom: 1rem;
                text-align: center;
                font-size: 0.875rem;
            }
        </style>
    </head>
    <body>
        <div class="login-container">
            <h1>Connexion Admin</h1>
            <?php if (isset($loginError)): ?>
                <div class="error"><?php echo $loginError; ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit">Se connecter</button>
            </form>
        </div>
    </body>
    </html>
    <?php
    exit();
}


$pdo = getDbConnection();


$currentTab = isset($_GET['tab']) ? $_GET['tab'] : 'comments';

// Get article ID for editing if provided
$editArticleId = isset($_GET['edit']) ? (int)$_GET['edit'] : null;
$editArticle = null;


$stmt = $pdo->query("
    SELECT c.*, a.title as article_title, a.slug as article_slug 
    FROM comments c 
    JOIN articles a ON c.article_id = a.id 
    ORDER BY c.date DESC
");
$comments = $stmt->fetchAll();


$pendingCount = count(array_filter($comments, function($c) { return $c['is_approved'] == 0; }));


$stmt = $pdo->query("SELECT * FROM articles ORDER BY date DESC");
$articles = $stmt->fetchAll();

// If editing, fetch the specific article
if ($editArticleId && $currentTab === 'edit-article') {
    $stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
    $stmt->execute([$editArticleId]);
    $editArticle = $stmt->fetch();
    
    if (!$editArticle) {
        header('Location: admin.php?tab=articles');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Gestion du Blog</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f8fafc;
            color: #1e293b;
        }
        .header {
            background: white;
            color: #1e293b;
            padding: 1.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid #e2e8f0;
        }
        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 { 
            font-size: 1.5rem;
            font-weight: 600;
            color: #1e293b;
        }
        .logout-btn {
            background: #f1f5f9;
            color: #64748b;
            padding: 0.5rem 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            text-decoration: none;
            transition: all 0.2s ease;
            font-weight: 500;
            font-size: 0.875rem;
        }
        .logout-btn:hover { 
            background: #e2e8f0; 
            color: #1e293b;
        }
        
        .tabs {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
            background: white;
        }
        .tab {
            background: transparent;
            color: #64748b;
            padding: 0.75rem 1.5rem;
            border: none;
            border-bottom: 2px solid transparent;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
            font-weight: 500;
            font-size: 0.875rem;
        }
        .tab:hover { 
            color: #2563eb;
            background: #f8fafc;
        }
        .tab.active { 
            color: #2563eb;
            border-bottom-color: #2563eb;
        }
        
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
        }
        .stat-card h3 { 
            color: #64748b; 
            font-size: 0.875rem; 
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        .stat-card .number { 
            font-size: 2rem; 
            font-weight: 700; 
            color: #2563eb;
        }
        .comments-list {
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }
        .comment-item {
            padding: 1.5rem;
            border-bottom: 1px solid #e2e8f0;
        }
        .comment-item:last-child { border-bottom: none; }
        .comment-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.75rem;
        }
        .comment-info h4 { 
            color: #1e293b; 
            margin-bottom: 0.25rem;
            font-weight: 600;
        }
        .comment-meta { 
            color: #94a3b8; 
            font-size: 0.875rem;
        }
        .comment-article {
            color: #2563eb;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
            text-decoration: none;
        }
        .comment-article:hover {
            color: #1e40af;
        }
        .comment-text {
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 1rem;
        }
        .comment-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            font-weight: 500;
            font-family: inherit;
        }
        .btn:hover { 
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .btn-approve { 
            background: #10b981; 
            color: white;
        }
        .btn-approve:hover {
            background: #059669;
        }
        .btn-reject { 
            background: #f59e0b; 
            color: white;
        }
        .btn-reject:hover {
            background: #d97706;
        }
        .btn-delete { 
            background: #ef4444; 
            color: white;
        }
        .btn-delete:hover {
            background: #dc2626;
        }
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }
        .status-approved {
            background: #d1fae5;
            color: #065f46;
        }
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #94a3b8;
        }
        .empty-state svg {
            width: 80px;
            height: 80px;
            margin-bottom: 1rem;
            opacity: 0.3;
        }
        
        /* Article Form Styles */
        .form-container {
            background: white;
            border-radius: 0.75rem;
            padding: 2rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
        }
        .form-container h2 {
            margin-bottom: 1.5rem;
            color: #1e293b;
            font-weight: 600;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #64748b;
            font-weight: 500;
            font-size: 0.875rem;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e2e8f0;
            border-radius: 0.5rem;
            font-size: 1rem;
            font-family: inherit;
            transition: border-color 0.2s ease;
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #2563eb;
        }
        .form-group textarea {
            min-height: 300px;
            resize: vertical;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        .btn-primary {
            background: #2563eb;
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 0.5rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s ease;
            font-family: inherit;
        }
        .btn-primary:hover {
            background: #1e40af;
        }
        .success-message {
            background: #d1fae5;
            color: #065f46;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            font-size: 0.875rem;
        }
        .error-message {
            background: #fee2e2;
            color: #991b1b;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            font-size: 0.875rem;
        }
        
        /* Articles List Styles */
        .articles-grid {
            display: grid;
            gap: 1rem;
        }
        .article-card {
            background: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            display: flex;
            gap: 1rem;
            border: 1px solid #e2e8f0;
        }
        .article-card img {
            width: 150px;
            height: 100px;
            object-fit: cover;
            border-radius: 0.5rem;
        }
        .article-card-content {
            flex: 1;
        }
        .article-card h3 {
            color: #1e293b;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        .article-card .meta {
            color: #94a3b8;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }
        .article-card .excerpt {
            color: #64748b;
            margin-bottom: 1rem;
            line-height: 1.5;
        }
        .article-card-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            .article-card {
                flex-direction: column;
            }
            .article-card img {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <h1>Panneau d'Administration</h1>
            <a href="?logout=1" class="logout-btn">Déconnexion</a>
        </div>
        <div class="tabs">
            <a href="?tab=comments" class="tab <?php echo $currentTab === 'comments' ? 'active' : ''; ?>">
                Commentaires <?php if($pendingCount > 0) echo "($pendingCount)"; ?>
            </a>
            <a href="?tab=articles" class="tab <?php echo $currentTab === 'articles' ? 'active' : ''; ?>">
                Articles
            </a>
            <a href="?tab=new-article" class="tab <?php echo $currentTab === 'new-article' ? 'active' : ''; ?>">
                Nouvel Article
            </a>
        </div>
    </div>

    <div class="container">
        <?php if ($currentTab === 'comments'): ?>
            <!-- Comments Section -->
            <!-- Statistics -->
            <div class="stats">
            <div class="stat-card">
                <h3>Total Commentaires</h3>
                <div class="number"><?php echo count($comments); ?></div>
            </div>
            <div class="stat-card">
                <h3>En attente</h3>
                <div class="number"><?php echo $pendingCount; ?></div>
            </div>
            <div class="stat-card">
                <h3>Approuvés</h3>
                <div class="number"><?php echo count($comments) - $pendingCount; ?></div>
            </div>
        </div>

        <!-- Comments List -->
        <div class="comments-list">
            <?php if (empty($comments)): ?>
                <div class="empty-state">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <p>Aucun commentaire pour le moment</p>
                </div>
            <?php else: ?>
                <?php foreach ($comments as $comment): ?>
                    <div class="comment-item" id="comment-<?php echo $comment['id']; ?>">
                        <div class="comment-header">
                            <div class="comment-info">
                                <h4><?php echo htmlspecialchars($comment['name']); ?></h4>
                                <div class="comment-meta">
                                    <?php echo date('d/m/Y à H:i', strtotime($comment['date'])); ?>
                                </div>
                            </div>
                            <span class="status-badge status-<?php echo $comment['is_approved'] ? 'approved' : 'pending'; ?>">
                                <?php echo $comment['is_approved'] ? 'Approuvé' : 'En attente'; ?>
                            </span>
                        </div>
                        <a href="../article.html?slug=<?php echo $comment['article_slug']; ?>" class="comment-article" target="_blank">
                            <?php echo htmlspecialchars($comment['article_title']); ?>
                        </a>
                        <div class="comment-text">
                            <?php echo nl2br(htmlspecialchars($comment['text'])); ?>
                        </div>
                        <div class="comment-actions">
                            <?php if (!$comment['is_approved']): ?>
                                <button class="btn btn-approve" onclick="approveComment(<?php echo $comment['id']; ?>)">
                                    Approuver
                                </button>
                            <?php else: ?>
                                <button class="btn btn-reject" onclick="rejectComment(<?php echo $comment['id']; ?>)">
                                    Retirer l'approbation
                                </button>
                            <?php endif; ?>
                            <button class="btn btn-delete" onclick="deleteComment(<?php echo $comment['id']; ?>)">
                                Supprimer
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <?php elseif ($currentTab === 'articles'): ?>
            <!-- Articles Management Section -->
            <h2 style="margin-bottom: 1.5rem; font-weight: 600;">Gestion des Articles</h2>
            <div class="articles-grid">
                <?php foreach ($articles as $article): ?>
                    <div class="article-card">
                        <?php if ($article['image']): ?>
                            <img src="<?php echo htmlspecialchars($article['image']); ?>" alt="<?php echo htmlspecialchars($article['title']); ?>">
                        <?php endif; ?>
                        <div class="article-card-content">
                            <h3><?php echo htmlspecialchars($article['title']); ?></h3>
                            <div class="meta">
                                <?php echo htmlspecialchars($article['category']); ?> | 
                                <?php echo date('d/m/Y', strtotime($article['date'])); ?> | 
                                <?php echo htmlspecialchars($article['reading_time']); ?>
                            </div>
                            <div class="excerpt"><?php echo htmlspecialchars($article['excerpt']); ?></div>
                            <div class="article-card-actions">
                                <button class="btn btn-reject" onclick="editArticle(<?php echo $article['id']; ?>)">
                                    Modifier
                                </button>
                                <button class="btn btn-delete" onclick="deleteArticle(<?php echo $article['id']; ?>)">
                                    Supprimer
                                </button>
                                <a href="../article.html?slug=<?php echo $article['slug']; ?>" target="_blank" class="btn" style="background: #2563eb; color: white; text-decoration: none; display: inline-flex; align-items: center;">
                                    Voir
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
        <?php elseif ($currentTab === 'new-article'): ?>
            <!-- New Article Form -->
            <div class="form-container">
                <h2>Créer un Nouvel Article</h2>
                <div id="formMessage"></div>
                <form id="articleForm">
                    <div class="form-group">
                        <label for="title">Titre *</label>
                        <input type="text" id="title" name="title" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="category">Catégorie *</label>
                            <select id="category" name="category" required>
                                <option value="">Sélectionner une catégorie</option>
                                <option value="Technologie">Technologie</option>
                                <option value="Design">Design</option>
                                <option value="Développement">Développement</option>
                                <option value="Business">Business</option>
                                <option value="Lifestyle">Lifestyle</option>
                                <option value="Marketing">Marketing</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="date">Date</label>
                            <input type="date" id="date" name="date" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="image">URL de l'image</label>
                        <input type="url" id="image" name="image" placeholder="https://example.com/image.jpg">
                    </div>
                    
                    <div class="form-group">
                        <label for="excerpt">Extrait</label>
                        <textarea id="excerpt" name="excerpt" rows="3" placeholder="Un bref résumé de l'article..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="content">Contenu * (HTML supporté)</label>
                        <textarea id="content" name="content" required placeholder="Écrivez votre article ici. Vous pouvez utiliser des balises HTML comme <p>, <h2>, <strong>, etc."></textarea>
                    </div>
                    
                    <button type="submit" class="btn-primary">Publier l'Article</button>
                </form>
            </div>
            
        <?php elseif ($currentTab === 'edit-article' && $editArticle): ?>
            <!-- Edit Article Form -->
            <div class="form-container">
                <h2>Modifier l'Article</h2>
                <div id="formMessage"></div>
                <form id="editArticleForm" data-article-id="<?php echo $editArticle['id']; ?>">
                    <div class="form-group">
                        <label for="edit-title">Titre *</label>
                        <input type="text" id="edit-title" name="title" value="<?php echo htmlspecialchars($editArticle['title']); ?>" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit-category">Catégorie *</label>
                            <select id="edit-category" name="category" required>
                                <option value="">Sélectionner une catégorie</option>
                                <option value="Technologie" <?php echo $editArticle['category'] === 'Technologie' ? 'selected' : ''; ?>>Technologie</option>
                                <option value="Design" <?php echo $editArticle['category'] === 'Design' ? 'selected' : ''; ?>>Design</option>
                                <option value="Développement" <?php echo $editArticle['category'] === 'Développement' ? 'selected' : ''; ?>>Développement</option>
                                <option value="Business" <?php echo $editArticle['category'] === 'Business' ? 'selected' : ''; ?>>Business</option>
                                <option value="Lifestyle" <?php echo $editArticle['category'] === 'Lifestyle' ? 'selected' : ''; ?>>Lifestyle</option>
                                <option value="Marketing" <?php echo $editArticle['category'] === 'Marketing' ? 'selected' : ''; ?>>Marketing</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit-date">Date</label>
                            <input type="date" id="edit-date" name="date" value="<?php echo $editArticle['date']; ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit-image">URL de l'image</label>
                        <input type="url" id="edit-image" name="image" value="<?php echo htmlspecialchars($editArticle['image']); ?>" placeholder="https://example.com/image.jpg">
                    </div>
                    
                    <div class="form-group">
                        <label for="edit-excerpt">Extrait</label>
                        <textarea id="edit-excerpt" name="excerpt" rows="3" placeholder="Un bref résumé de l'article..."><?php echo htmlspecialchars($editArticle['excerpt']); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit-content">Contenu * (HTML supporté)</label>
                        <textarea id="edit-content" name="content" required placeholder="Écrivez votre article ici. Vous pouvez utiliser des balises HTML comme <p>, <h2>, <strong>, etc."><?php echo htmlspecialchars($editArticle['content']); ?></textarea>
                    </div>
                    
                    <div style="display: flex; gap: 1rem;">
                        <button type="submit" class="btn-primary">Mettre à Jour l'Article</button>
                        <a href="?tab=articles" class="btn-primary" style="background: #64748b; text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">
                            Annuler
                        </a>
                    </div>
                </form>
            </div>
        <?php endif; ?>
    </div>

    <script>
        
        function approveComment(commentId) {
            if (!confirm('Approuver ce commentaire ?')) return;
            
            fetch('comments.php?id=' + commentId, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ is_approved: 1 })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                location.reload(); 
            })
            .catch(error => {
                alert('Erreur: ' + error);
            });
        }

        
        function rejectComment(commentId) {
            if (!confirm('Retirer l\'approbation de ce commentaire ?')) return;
            
            fetch('comments.php?id=' + commentId, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ is_approved: 0 })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                location.reload(); 
            })
            .catch(error => {
                alert('Erreur: ' + error);
            });
        }

        
        function deleteComment(commentId) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ? Cette action est irréversible.')) return;
            
            fetch('comments.php?id=' + commentId, {
                method: 'DELETE'
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                location.reload(); 
            })
            .catch(error => {
                alert('Erreur: ' + error);
            });
        }
        
        
        const articleForm = document.getElementById('articleForm');
        if (articleForm) {
            articleForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = {
                    title: document.getElementById('title').value,
                    category: document.getElementById('category').value,
                    date: document.getElementById('date').value,
                    image: document.getElementById('image').value,
                    excerpt: document.getElementById('excerpt').value,
                    content: document.getElementById('content').value
                };
                
                const messageDiv = document.getElementById('formMessage');
                messageDiv.innerHTML = '<div style="padding: 1rem; background: #dbeafe; color: #1e40af; border-radius: 0.5rem; font-size: 0.875rem;">Publication en cours...</div>';
                
                fetch('articles.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(formData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        messageDiv.innerHTML = '<div class="error-message">' + data.error + '</div>';
                    } else {
                        messageDiv.innerHTML = '<div class="success-message">' + data.message + '</div>';
                        articleForm.reset();
                        
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                        
                        
                        setTimeout(() => {
                            window.location.href = '?tab=articles';
                        }, 2000);
                    }
                })
                .catch(error => {
                    messageDiv.innerHTML = '<div class="error-message">Erreur: ' + error + '</div>';
                });
            });
        }
        
        
        function deleteArticle(articleId) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cet article ? Cette action est irréversible.')) return;
            
            fetch('articles.php?id=' + articleId, {
                method: 'DELETE'
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                location.reload();
            })
            .catch(error => {
                alert('Erreur: ' + error);
            });
        }
        
        // Function to edit an article (placeholder - can be implemented later)
        function editArticle(articleId) {
            // Redirect to edit form with article ID
            window.location.href = '?tab=edit-article&edit=' + articleId;
        }
        
        // Function to handle edit article form submission
        const editArticleForm = document.getElementById('editArticleForm');
        if (editArticleForm) {
            editArticleForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const articleId = editArticleForm.getAttribute('data-article-id');
                const formData = {
                    title: document.getElementById('edit-title').value,
                    category: document.getElementById('edit-category').value,
                    date: document.getElementById('edit-date').value,
                    image: document.getElementById('edit-image').value,
                    excerpt: document.getElementById('edit-excerpt').value,
                    content: document.getElementById('edit-content').value
                };
                
                const messageDiv = document.getElementById('formMessage');
                messageDiv.innerHTML = '<div style="padding: 1rem; background: #dbeafe; color: #1e40af; border-radius: 0.5rem; font-size: 0.875rem;">Mise à jour en cours...</div>';
                
                fetch('articles.php?id=' + articleId, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(formData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        messageDiv.innerHTML = '<div class="error-message">' + data.error + '</div>';
                    } else {
                        messageDiv.innerHTML = '<div class="success-message">' + data.message + '</div>';
                        // Scroll to top to see message
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                        
                        // Redirect to articles tab after 2 seconds
                        setTimeout(() => {
                            window.location.href = '?tab=articles';
                        }, 2000);
                    }
                })
                .catch(error => {
                    messageDiv.innerHTML = '<div class="error-message">Erreur: ' + error + '</div>';
                });
            });
        }
    </script>
</body>
</html>
