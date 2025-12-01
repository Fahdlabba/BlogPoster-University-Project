<?php
define('DB_HOST', 'localhost');     
define('DB_NAME', 'blog_db');        
define('DB_USER', 'root');           
define('DB_PASS', '');               
define('DB_CHARSET', 'utf8mb4');     

function getDbConnection() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        
        $pdo = new PDO($dsn, DB_USER, DB_PASS);
        
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        
        return $pdo;
    } catch (PDOException $e) {
        
        die("Connection failed: " . $e->getMessage());
    }
}

date_default_timezone_set('Europe/Paris');

header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');
?>
