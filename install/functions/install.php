<?php

class InstallTinyCMS
{
    public function checkExtension($extensionName)
    {
        if (extension_loaded($extensionName)) {
            return "<span style='color: green; font-weight: bold;'>Enabled</span>";
        } else {
            return "<span style='color: red; font-weight: bold;'>Disabled</span>";
        }
    }

    public function install($host, $port, $username, $password, $auth, $characters, $website, $soap_username, $soap_password)
    {
        try {
            // Create mysqli connection
            $db = new mysqli($host, $username, $password, '', $port);
            if ($db->connect_error) {
                throw new Exception('Connect Error (' . $db->connect_errno . ') ' . $db->connect_error);
            }
    
            // Create website database if it doesn't exist
            $db_query = "CREATE DATABASE IF NOT EXISTS `$website`";
            if (!$db->query($db_query)) {
                throw new Exception("Error creating database: " . $db->error);
            }
    
            $db->select_db($website);
    
            // Import SQL file
            $sqlFile = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'SQL' . DIRECTORY_SEPARATOR . 'website.sql';
            if (!file_exists($sqlFile)) {
                throw new Exception("SQL file not found: $sqlFile");
            }
            $sql = file_get_contents($sqlFile);
            if ($sql === false) {
                throw new Exception("Unable to read SQL file: $sqlFile");
            }
            if (!$db->multi_query($sql)) {
                throw new Exception("Error executing SQL script: " . $db->error);
            }
    
            // Clear results
            do {
                if ($result = $db->store_result()) {
                    $result->free();
                }
            } while ($db->more_results() && $db->next_result());
    
            $db->close();
    
            // Generate configuration
            $config = $this->generateConfig($host, $port, $username, $password, $auth, $characters, $website, $soap_username, $soap_password);
    
            // Write configuration to file
            $configFile = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'engine' . DIRECTORY_SEPARATOR . 'configs' . DIRECTORY_SEPARATOR . 'db_config.php';
            if (file_put_contents($configFile, $config) === false) {
                throw new Exception("Unable to write to config file: $configFile");
            }
    
            // Create install lock file
            $lockFile = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'engine' . DIRECTORY_SEPARATOR . 'install.lock';
            if (file_put_contents($lockFile, date('Y-m-d H:i:s')) === false) {
                throw new Exception("Unable to create install lock file: $lockFile");
            }
    
            return true; // Installation successful
    
        } catch (Exception $e) {
            return "Installation failed: " . $e->getMessage();
        }
    }

    private function generateConfig($host, $port, $username, $password, $auth, $characters, $website, $soap_username, $soap_password)
    {
        $config = <<<EOT
<?php

class Configuration {
    private \$config;

    public function __construct() {
        \$this->config = [
            'db' => [
                'auth' => [
                    'database_type' => 'mysql',
                    'database_name' => '$auth',
                    'server' => '$host',
                    'username' => '$username',
                    'password' => '$password',
                    'charset' => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                    'port' => $port
                ],
                'website' => [
                    'database_type' => 'mysql',
                    'database_name' => '$website',
                    'server' => '$host',
                    'username' => '$username',
                    'password' => '$password',
                    'charset' => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                    'port' => $port
                ],
                'characters' => [
                    'database_type' => 'mysql',
                    'database_name' => '$characters',
                    'server' => '$host',
                    'username' => '$username',
                    'password' => '$password',
                    'charset' => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                    'port' => $port
                ]
            ],
            'soap' => [
                'username' => '$soap_username',
                'password' => '$soap_password'
            ]
        ];
    }

    public function get_config(\$key) {
        if (isset(\$this->config[\$key])) {
            return \$this->config[\$key];
        } else {
            throw new Exception("Configuration key '\$key' not found.");
        }
    }
}
EOT;

        return $config;
    }
}