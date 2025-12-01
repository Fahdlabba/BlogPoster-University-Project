<?php

require_once 'config.php';

header('Content-Type: application/xml; charset=utf-8');

$pdo = getDbConnection();

try {
    
    $stmt = $pdo->prepare("SELECT * FROM articles ORDER BY date DESC LIMIT 20");
    $stmt->execute();
    $articles = $stmt->fetchAll();
    
    
    $rss = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $rss .= '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">' . "\n";
    $rss .= '<channel>' . "\n";
    
    
    $rss .= '  <title>Blog Minimaliste</title>' . "\n";
    $rss .= '  <link>http://localhost/BlogPoster-University-Project/</link>' . "\n";
    $rss .= '  <description>Articles et réflexions sur le développement web, le design et la technologie</description>' . "\n";
    $rss .= '  <language>fr-FR</language>' . "\n";
    $rss .= '  <lastBuildDate>' . date(DATE_RSS) . '</lastBuildDate>' . "\n";
    $rss .= '  <atom:link href="http://localhost/BlogPoster-University-Project/php/rss.php" rel="self" type="application/rss+xml" />' . "\n";
    
    
    foreach ($articles as $article) {
        $rss .= '  <item>' . "\n";
        $rss .= '    <title>' . htmlspecialchars($article['title']) . '</title>' . "\n";
        $rss .= '    <link>http://localhost/BlogPoster-University-Project/article.html?slug=' . urlencode($article['slug']) . '</link>' . "\n";
        $rss .= '    <guid>http://localhost/BlogPoster-University-Project/article.html?slug=' . urlencode($article['slug']) . '</guid>' . "\n";
        $rss .= '    <description>' . htmlspecialchars($article['excerpt']) . '</description>' . "\n";
        $rss .= '    <category>' . htmlspecialchars($article['category']) . '</category>' . "\n";
        $rss .= '    <pubDate>' . date(DATE_RSS, strtotime($article['date'])) . '</pubDate>' . "\n";
        
        
        if (!empty($article['image'])) {
            $rss .= '    <enclosure url="' . htmlspecialchars($article['image']) . '" type="image/jpeg" />' . "\n";
        }
  
        $rss .= '  </item>' . "\n";
    }
    
    $rss .= '</channel>' . "\n";
    $rss .= '</rss>';
    
    
    echo $rss;
    
} catch (PDOException $e) {
    
    header('Content-Type: text/plain; charset=utf-8');
    echo 'Error generating RSS feed: ' . $e->getMessage();
}
?>
