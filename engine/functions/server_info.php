<?php

class ServerInfo
{
    private $website_connection;
    private $characters_connection;
    private $auth_connection;

    public function __construct()
    {
        $database = new Database();
        $this->auth_connection = $database->getConnection('auth');
        $this->website_connection = $database->getConnection('website');
        $this->characters_connection = $database->getConnection('characters');
    }

    public function get_online_players()
    {
        return $this->characters_connection->count('characters', [
            'online' => 1
        ]);
    }

    public function get_realm_name()
    {
        return $this->auth_connection->get('realmlist', 'name', [
            'id' => 1
        ]);
    }
}
?>
