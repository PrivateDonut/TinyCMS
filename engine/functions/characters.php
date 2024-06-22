<?php

class Character
{
    private $character_connection;

    public function __construct()
    {
        $database = new Database();
        $this->character_connection = $database->getConnection('characters');
    }

    public function get_characters($account_id)
    {
        $characters = $this->character_connection->select('characters', [
            'guid', 'name', 'race', 'class', 'gender', 'level'
        ], [
            'ACCOUNT' => $account_id
        ]);
    
        return $characters;
    }
    
    
}

?>