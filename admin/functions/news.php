<?php 

class News{
    private $connection;

    public function __construct() {
        $config = new Configuration();
        $this->connection = $config->getDatabaseConnection('website');
    }

    public function get_news() {
        $stmt = $this->connection->prepare("SELECT * FROM news ORDER BY id DESC");
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result;
    }

    public function get_total_news() {
        $stmt = $this->connection->prepare("SELECT COUNT(*) FROM news");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $total = $row['COUNT(*)'];
        $stmt->close();
        return $total;
    }

    public function get_news_by_id($id) {
        $stmt = $this->connection->prepare("SELECT * FROM news WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result;
    }

    public function add_news($title, $content, $author) {
        $stmt = $this->connection->prepare("INSERT INTO news (title, content, author) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $title, $content, $author);
        $stmt->execute();
        $stmt->close();
    }

    public function update_news($id, $title, $content) {
        $stmt = $this->connection->prepare("UPDATE news SET title = ?, content = ? WHERE id = ?");
        $stmt->bind_param("ssi", $title, $content, $id);
        $stmt->execute();
        $stmt->close();
    }
    
}


?>