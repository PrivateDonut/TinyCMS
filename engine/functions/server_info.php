<?php

class ServerInfo
{
    private $website_connection;
    private $characters_connection;
    private $auth_connection;

    public function __construct()
    {
        $config = new Configuration();
        $this->website_connection = $config->getDatabaseConnection('website');
        $this->characters_connection = $config->getDatabaseConnection('characters');
        $this->auth_connection = $config->getDatabaseConnection('auth');

    }

    public function get_online_players()
    {
        $stmt = $this->characters_connection->prepare("SELECT COUNT(*) FROM characters WHERE online = 1");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        return $row['COUNT(*)'];
    }
    
    public function get_realm_name()
    {
        $stmt = $this->auth_connection->prepare("SELECT name FROM realmlist WHERE id = 1");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        return $row['name'];
    }

    
}

?>