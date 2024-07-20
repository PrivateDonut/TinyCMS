<?php

/*********************************************************************************
 * DonutCMS is free software: you can redistribute it and/or modify               *
 * it under the terms of the GNU General Public License as published by          *
 * the Free Software Foundation, either version 3 of the License, or             *
 * (at your option) any later version.                                           *
 *                                                                               *
 * DonutCMS is distributed in the hope that it will be useful,                    *
 * but WITHOUT ANY WARRANTY; without even the implied warranty of                *
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the                  *
 * GNU General Public License for more details.                                  *
 *                                                                               *
 * You should have received a copy of the GNU General Public License             *
 * along with DonutCMS. If not, see <https://www.gnu.org/licenses/>.              *
 * *******************************************************************************/

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
            $sqlFile = "sql" . DIRECTORY_SEPARATOR . "website.sql";
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
            $encryption_key = bin2hex(random_bytes(32)); // Generate a secure random encryption key
            $config = $this->generateConfig($host, $port, $username, $password, $auth, $characters, $website, $soap_username, $soap_password, $encryption_key);

            // Write configuration to file
            $configFile = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'engine' . DIRECTORY_SEPARATOR . 'configs' . DIRECTORY_SEPARATOR . 'db_config.php';

            if (file_put_contents($configFile, $config) === false) {
                throw new Exception("Unable to write to config file: $configFile");
            } else {
                error_log("Config file created successfully: $configFile");
            }

            // Create install lock file
            $lockFile = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'engine' . DIRECTORY_SEPARATOR . 'install.lock';

            if (file_put_contents($lockFile, date('Y-m-d H:i:s')) === false) {
                throw new Exception("Unable to create install lock file: $lockFile");
            } else {
                error_log("Install lock file created successfully: $lockFile");
            }

            return true; // Installation successful

        } catch (Exception $e) {
            error_log("Installation error: " . $e->getMessage());
            return "Installation failed: " . $e->getMessage();
        }
    }

    private function generateConfig($host, $port, $username, $password, $auth, $characters, $website, $soap_username, $soap_password, $encryption_key)
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
            ],
            'session' => [
                'encryption_key' => '$encryption_key'
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
