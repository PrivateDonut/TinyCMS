<!-- 
- Made By : PrivateDonut
- Project Name : TinyCMS
- Website : https://wowemulation.com
-->

<?php
require_once BASE_DIR . '/vendor/autoload.php';
require_once BASE_DIR . '/engine/configs/db_config.php';

use Medoo\Medoo;

class Database {
    private $instances = [];
    private $configuration;

    public function __construct() {
        $this->configuration = new Configuration();
    }

    public function getConnection($name) {
        $dbConfig = $this->configuration->get_config('db');
        // echo "Config array keys: " . implode(", ", array_keys($dbConfig)) . "<br>"; // Debug statement

        if (!isset($this->instances[$name])) {
            if (!$dbConfig) {
                throw new Exception("Configuration not loaded properly. Config is null.");
            }
            // echo "Loading configuration for '$name'...Config array keys: " . implode(", ", array_keys($dbConfig)) . "<br>"; // Debug statement
            if (isset($dbConfig[$name])) {
                $this->instances[$name] = new Medoo($dbConfig[$name]);
            } else {
                throw new Exception("Database configuration for '$name' not found.");
            }
        }
        return $this->instances[$name];
    }
}
?>




