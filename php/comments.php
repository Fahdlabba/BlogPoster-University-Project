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
    
    $articleId = isset($_GET['article_id']) ? (int)$_GET['article_id'] : null;
    $approved = isset($_GET['approved']) ? (int)$_GET['approved'] : 1; 
    
    try {
        
        $query = "SELECT * FROM comments WHERE 1=1";
        $params = [];
        
        
        if ($articleId) {
            $query .= " AND article_id = ?";
            $params[] = $articleId;
        }
        
        
        
        if ($approved !== -1) {
            $query .= " AND is_approved = ?";
            $params[] = $approved;
        }
        
        
        $query .= " ORDER BY date DESC";
        
        
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $comments = $stmt->fetchAll();
        
        echo json_encode([
            'comments' => $comments,
            'count' => count($comments)
        ]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}


function handlePostRequest($pdo) {
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    
    if (empty($data['article_id']) || empty($data['name']) || empty($data['text'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields: article_id, name, text']);
        return;
    }
    
    
    $articleId = (int)$data['article_id'];
    $name = trim($data['name']);
    $text = trim($data['text']);
    $date = date('Y-m-d H:i:s');
    
    
    if (strlen($name) < 2 || strlen($name) > 100) {
        http_response_code(400);
        echo json_encode(['error' => 'Name must be between 2 and 100 characters']);
        return;
    }
    
    
    if (strlen($text) < 10 || strlen($text) > 1000) {
        http_response_code(400);
        echo json_encode(['error' => 'Comment must be between 10 and 1000 characters']);
        return;
    }
    
    
    $stmt = $pdo->prepare("SELECT id FROM articles WHERE id = ?");
    $stmt->execute([$articleId]);
    if (!$stmt->fetch()) {
        http_response_code(404);
        echo json_encode(['error' => 'Article not found']);
        return;
    }
    
    try {
        
        
        $stmt = $pdo->prepare("
            INSERT INTO comments (article_id, name, text, date, is_approved) 
            VALUES (?, ?, ?, ?, 0)
        ");
        
        $stmt->execute([
            $articleId,
            $name,
            $text,
            $date
        ]);
        
        
        $commentId = $pdo->lastInsertId();
        
        
        $stmt = $pdo->prepare("SELECT * FROM comments WHERE id = ?");
        $stmt->execute([$commentId]);
        $comment = $stmt->fetch();
        
        http_response_code(201);
        echo json_encode([
            'message' => 'Comment submitted successfully. It will be visible after approval.',
            'comment' => $comment
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
        echo json_encode(['error' => 'Comment ID is required']);
        return;
    }
    
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    
    $isApproved = isset($data['is_approved']) ? (int)$data['is_approved'] : 1;
    
    try {
        
        $stmt = $pdo->prepare("SELECT * FROM comments WHERE id = ?");
        $stmt->execute([$id]);
        $comment = $stmt->fetch();
        
        if (!$comment) {
            http_response_code(404);
            echo json_encode(['error' => 'Comment not found']);
            return;
        }
        
        
        $stmt = $pdo->prepare("UPDATE comments SET is_approved = ? WHERE id = ?");
        $stmt->execute([$isApproved, $id]);
        
        
        $stmt = $pdo->prepare("SELECT * FROM comments WHERE id = ?");
        $stmt->execute([$id]);
        $updatedComment = $stmt->fetch();
        
        $status = $isApproved ? 'approved' : 'rejected';
        echo json_encode([
            'message' => "Comment $status successfully",
            'comment' => $updatedComment
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
        echo json_encode(['error' => 'Comment ID is required']);
        return;
    }
    
    try {
        
        $stmt = $pdo->prepare("SELECT * FROM comments WHERE id = ?");
        $stmt->execute([$id]);
        $comment = $stmt->fetch();
        
        if (!$comment) {
            http_response_code(404);
            echo json_encode(['error' => 'Comment not found']);
            return;
        }  
        
        $stmt = $pdo->prepare("DELETE FROM comments WHERE id = ?");
        $stmt->execute([$id]);
        
        echo json_encode([
            'message' => 'Comment deleted successfully',
            'deleted_comment' => $comment
        ]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}
?>
