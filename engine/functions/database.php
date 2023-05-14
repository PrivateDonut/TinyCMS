<!-- 
- Made By : PrivateDonut
- Project Name : TinyCMS
- Website : https://privatedonut.com
-->

<?php
require_once __DIR__ . '/../configs/db_config.php';

class DatabaseConnection
{
    private $host;
    private $username;
    private $password;
    private $database;

    public function __construct($host, $username, $password, $database)
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
    }

    public function connect()
    {
        $connection = mysqli_connect($this->host, $this->username, $this->password, $this->database);
        if (!$connection) {
            die("Connection failed: " . mysqli_connect_error());
        }
        return $connection;
    }
}

class Configuration
{
    private $databases;

    public function __construct()
    {
        global $db_host, $db_username, $db_password, $db_auth, $db_characters, $db_website;
        $this->databases = array(
            'auth' => new DatabaseConnection($db_host, $db_username, $db_password, $db_auth),
            'website' => new DatabaseConnection($db_host, $db_username, $db_password, $db_website),
            'characters' => new DatabaseConnection($db_host, $db_username, $db_password, $db_characters)
        );
    }

    public function getDatabaseConnection($name)
    {
        if (isset($this->databases[$name])) {
            return $this->databases[$name]->connect();
        }
        return null;
    }
}
?>