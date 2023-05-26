<?php

class Character
{
    private $character_connection;

    public function __construct()
    {
        $config = new Configuration();
        $this->character_connection = $config->getDatabaseConnection('characters');
    }

    public function get_characters($account_id)
    {
        $stmt = $this->character_connection->prepare("SELECT `guid`, `name`, `race`, `class`, `gender`, `level` FROM characters WHERE ACCOUNT=?");
        $stmt->bind_param("i", $account_id);
        $stmt->execute();
        $stmt->bind_result($guid, $name, $race, $class, $gender, $level);
        
        $characters = array();
        
        while ($stmt->fetch()) {
            $character = array(
                'guid' => $guid,
                'name' => $name,
                'race' => $race,
                'class' => $class,
                'gender' => $gender,
                'level' => $level
            );
            
            $characters[] = $character;
        }
        
        return $characters;
    }
    
}

?>