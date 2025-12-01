<?php

require_once 'config.php';


header('Content-Type: application/json; charset=utf-8');

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$pdo = getDbConnection();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        handleGetRequest($pdo);
        break;
    case 'POST':
        handlePostRequest($pdo);
        break;
    case 'PUT':
        handlePutRequest($pdo);
        break;
    case 'DELETE':
        handleDeleteRequest($pdo);
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}

function handleGetRequest($pdo) {
    
    $slug = isset($_GET['slug']) ? $_GET['slug'] : null;
    $category = isset($_GET['category']) ? $_GET['category'] : null;
    $search = isset($_GET['search']) ? $_GET['search'] : null;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    
    
    $offset = ($page - 1) * $limit;
    
    try {
        
        if ($slug) {
            $stmt = $pdo->prepare("SELECT * FROM articles WHERE slug = ?");
            $stmt->execute([$slug]);
            $article = $stmt->fetch();
            
            if ($article) {
                echo json_encode($article);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Article not found']);
            }
            return;
        }
        
        
        $query = "SELECT * FROM articles WHERE 1=1";
        $params = [];
        
        
        if ($category && $category !== 'all') {
            $query .= " AND category = ?";
            $params[] = $category;
        }
        
        
        if ($search) {
            $query .= " AND (title LIKE ? OR excerpt LIKE ? OR content LIKE ?)";
            $searchTerm = "%$search%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        
        $countQuery = "SELECT COUNT(*) as total FROM articles WHERE 1=1";
        if ($category && $category !== 'all') {
            $countQuery .= " AND category = ?";
        }
        if ($search) {
            $countQuery .= " AND (title LIKE ? OR excerpt LIKE ? OR content LIKE ?)";
        }
        
        $countStmt = $pdo->prepare($countQuery);
        $countStmt->execute($params);
        $totalArticles = $countStmt->fetch()['total'];
        
        
        $query .= " ORDER BY date DESC LIMIT :limit OFFSET :offset";
        
        
        $stmt = $pdo->prepare($query);
        
        
        foreach ($params as $index => $value) {
            $stmt->bindValue($index + 1, $value);
        }
        
        
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        
        $stmt->execute();
        $articles = $stmt->fetchAll();
        
        
        $response = [
            'articles' => $articles,
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => $totalArticles,
                'totalPages' => ceil($totalArticles / $limit)
            ]
        ];
        
        echo json_encode($response);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}


function handlePostRequest($pdo) {
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    
    if (empty($data['title']) || empty($data['content']) || empty($data['category'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields: title, content, category']);
        return;
    }
    
    
    $slug = isset($data['slug']) ? $data['slug'] : generateSlug($data['title']);
    
    
    $title = trim($data['title']);
    $category = trim($data['category']);
    $content = $data['content'];
    $excerpt = isset($data['excerpt']) ? trim($data['excerpt']) : substr(strip_tags($content), 0, 150) . '...';
    $image = isset($data['image']) ? trim($data['image']) : null;
    $reading_time = isset($data['reading_time']) ? trim($data['reading_time']) : estimateReadingTime($content);
    $date = isset($data['date']) ? $data['date'] : date('Y-m-d');
    
    try {
        
        $stmt = $pdo->prepare("
            INSERT INTO articles (title, slug, category, date, image, excerpt, reading_time, content) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $title,
            $slug,
            $category,
            $date,
            $image,
            $excerpt,
            $reading_time,
            $content
        ]);
        
        
        $articleId = $pdo->lastInsertId();
        
        
        $stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
        $stmt->execute([$articleId]);
        $article = $stmt->fetch();
        
        http_response_code(201);
        echo json_encode([
            'message' => 'Article created successfully',
            'article' => $article
        ]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}


function handlePutRequest($pdo) {
    
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'Article ID is required']);
        return;
    }
    
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    
    $stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
    $stmt->execute([$id]);
    $existingArticle = $stmt->fetch();
    
    if (!$existingArticle) {
        http_response_code(404);
        echo json_encode(['error' => 'Article not found']);
        return;
    }
    
    
    $title = isset($data['title']) ? trim($data['title']) : $existingArticle['title'];
    $slug = isset($data['slug']) ? $data['slug'] : $existingArticle['slug'];
    $category = isset($data['category']) ? trim($data['category']) : $existingArticle['category'];
    $content = isset($data['content']) ? $data['content'] : $existingArticle['content'];
    $excerpt = isset($data['excerpt']) ? trim($data['excerpt']) : $existingArticle['excerpt'];
    $image = isset($data['image']) ? trim($data['image']) : $existingArticle['image'];
    $reading_time = isset($data['reading_time']) ? trim($data['reading_time']) : $existingArticle['reading_time'];
    $date = isset($data['date']) ? $data['date'] : $existingArticle['date'];
    
    try {
        
        $stmt = $pdo->prepare("
            UPDATE articles 
            SET title = ?, slug = ?, category = ?, date = ?, image = ?, excerpt = ?, reading_time = ?, content = ?
            WHERE id = ?
        ");
        
        $stmt->execute([
            $title,
            $slug,
            $category,
            $date,
            $image,
            $excerpt,
            $reading_time,
            $content,
            $id
        ]);
        
        
        $stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
        $stmt->execute([$id]);
        $article = $stmt->fetch();
        
        echo json_encode([
            'message' => 'Article updated successfully',
            'article' => $article
        ]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}


function handleDeleteRequest($pdo) {
    
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'Article ID is required']);
        return;
    }
    
    try {
        
        $stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
        $stmt->execute([$id]);
        $article = $stmt->fetch();
        
        if (!$article) {
            http_response_code(404);
            echo json_encode(['error' => 'Article not found']);
            return;
        }
        
        
        $stmt = $pdo->prepare("DELETE FROM articles WHERE id = ?");
        $stmt->execute([$id]);
        
        echo json_encode([
            'message' => 'Article deleted successfully',
            'deleted_article' => $article
        ]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}


function generateSlug($text) {
    
    $text = strtolower($text);
    
    
    $text = str_replace(
        ['é', 'è', 'ê', 'ë', 'à', 'â', 'ä', 'î', 'ï', 'ô', 'ö', 'ù', 'û', 'ü', 'ç'],
        ['e', 'e', 'e', 'e', 'a', 'a', 'a', 'i', 'i', 'o', 'o', 'u', 'u', 'u', 'c'],
        $text
    );
    
    
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    
    
    $text = trim($text, '-');
    
    return $text;
}


function estimateReadingTime($content) {
    
    $text = strip_tags($content);
    
    
    $wordCount = str_word_count($text);
    
    
    $minutes = ceil($wordCount / 200);
    
    return $minutes . ' min';
}
?>
