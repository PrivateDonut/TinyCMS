<?php 

class news_home{
    private $connection;

    public function __construct(){
        $config = new Configuration();
        $this->connection = $config->getDatabaseConnection('website');
    }

    public function get_news(){
        $stmt = $this->connection->prepare("SELECT id, title, content, author, created_at FROM news ORDER BY id DESC");
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

    public function get_news_by_id($id){
        $stmt = $this->connection->prepare("SELECT id, title, content, author, created_at FROM news WHERE id = ?");
        $stmt->bind_param("i", $id);
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

    public function get_news_by_title($title){
        $stmt = $this->connection->prepare("SELECT id, title, content, author, created_at FROM news WHERE title = ?");
        $stmt->bind_param("s", $title);
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