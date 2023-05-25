<?php 

class news_home {
    private $connection;

    public function __construct() {
        $config = new Configuration();
        $this->connection = $config->getDatabaseConnection('website');
    }

    public function get_news() {
        $stmt = $this->connection->prepare("SELECT id, title, content, author, created_at FROM news ORDER BY id DESC LIMIT 4");
        $stmt->execute();
        $stmt->bind_result($id, $title, $content, $author, $created_at);
        $news = array();
        while ($stmt->fetch()) {
            $news[] = array(
                'id' => $id,
                'title' => $title,
                'content' => $content,
                'author' => $author,
                'date' => $created_at
            );
        }
        $stmt->close();
        return $news;
    }
    
}
?>