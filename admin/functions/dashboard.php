<?php 

class Dashboard{

    private $connection;
    private $characters;

    public function __construct() {
        $config = new Configuration();
        $this->connection = $config->getDatabaseConnection('auth');
        $this->characters = $config->getDatabaseConnection('characters');
    }   

    public function total_accounts() {
        $stmt = $this->connection->prepare("SELECT COUNT(*) FROM account");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $total = $row['COUNT(*)'];
        $stmt->close();
        return $total;
    }

    public function total_characters() {
        $stmt = $this->characters->prepare("SELECT COUNT(*) FROM characters");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $total = $row['COUNT(*)'];
        $stmt->close();
        return $total;
    }

    public function total_tickets() {
        $stmt = $this->characters->prepare("SELECT COUNT(*) FROM gm_ticket");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $total = $row['COUNT(*)'];
        $stmt->close();
        return $total;
    }

    public function total_arena_teams() {
        $stmt = $this->characters->prepare("SELECT COUNT(*) FROM arena_team");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $total = $row['COUNT(*)'];
        $stmt->close();
        return $total;
    }
}

?>