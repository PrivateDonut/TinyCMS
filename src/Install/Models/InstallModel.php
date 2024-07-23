<?php
/*********************************************************************************
 * DonutCMS is free software: you can redistribute it and/or modify              *
 * it under the terms of the GNU General Public License as published by          *
 * the Free Software Foundation, either version 3 of the License, or             *
 * (at your option) any later version.                                           *
 *                                                                               *
 * DonutCMS is distributed in the hope that it will be useful,                   *
 * but WITHOUT ANY WARRANTY; without even the implied warranty of                *
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the                  *
 * GNU General Public License for more details.                                  *
 *                                                                               *
 * You should have received a copy of the GNU General Public License             *
 * along with DonutCMS. If not, see <https://www.gnu.org/licenses/>.             *
 * *******************************************************************************/

namespace Install\Models;

use Medoo\Medoo;

class InstallModel
{
    public function isInstalled()
    {
        $lockFile = $this->getInstallLockFilePath();
        return file_exists($lockFile);
    }

    public function install($userInput)
    {
        try {
            $this->validateUserInput($userInput);
            $this->createDatabaseIfNotExists($userInput);
            $this->importSqlFile($userInput);
            $this->generateConfigFile($userInput);
            $this->createInstallLockFile();

            return true;
        } catch (\Exception $e) {
            error_log("Installation error: " . $e->getMessage());
            return "Installation failed: " . $e->getMessage();
        }
    }

    private function validateUserInput($userInput)
    {
        $requiredFields = [
            'website_db_host', 'website_db_port', 'website_db_username', 'website_db_password', 'website_db_name',
            'auth_db_host', 'auth_db_port', 'auth_db_username', 'auth_db_password', 'auth_db_name',
            'char_db_host', 'char_db_port', 'char_db_username', 'char_db_password', 'char_db_name'
        ];
        foreach ($requiredFields as $field) {
            if (empty($userInput[$field])) {
                throw new \Exception("Missing required field: $field");
            }
        }

        // Validate ports
        $ports = ['website_db_port', 'auth_db_port', 'char_db_port'];
        foreach ($ports as $port) {
            if (!filter_var($userInput[$port], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 65535]])) {
                throw new \Exception("Invalid port number for $port");
            }
        }
    }

    private function createDatabaseIfNotExists($userInput)
    {
        $db = new Medoo([
            'type' => 'mysql',
            'host' => $userInput['website_db_host'],
            'username' => $userInput['website_db_username'],
            'password' => $userInput['website_db_password'],
            'port' => $userInput['website_db_port'],
        ]);

        $db->query("CREATE DATABASE IF NOT EXISTS `{$userInput['website_db_name']}`");
    }

    private function importSqlFile($userInput)
    {
        $db = new Medoo([
            'type' => 'mysql',
            'host' => $userInput['website_db_host'],
            'database' => $userInput['website_db_name'],
            'username' => $userInput['website_db_username'],
            'password' => $userInput['website_db_password'],
            'port' => $userInput['website_db_port'],
        ]);

        $sqlFile = __DIR__ . "/../SQL/website.sql";
        if (!file_exists($sqlFile)) {
            throw new \Exception("SQL file not found: $sqlFile");
        }
        $sql = file_get_contents($sqlFile);
        if ($sql === false) {
            throw new \Exception("Unable to read SQL file: $sqlFile");
        }
        $queries = explode(';', $sql);
        foreach ($queries as $query) {
            $query = trim($query);
            if (!empty($query)) {
                try {
                    $db->query($query);
                } catch (\PDOException $e) {
                    throw new \Exception("SQL Error: " . $e->getMessage() . "\nIn query: " . $query);
                }
            }
        }
    }

    private function generateConfigFile($userInput)
    {
        $encryptionKey = $this->generateEncryptionKey();
        $configContent = $this->getConfigFileContent($userInput, $encryptionKey);
        $configFile = $this->getConfigFilePath();
        if (file_put_contents($configFile, $configContent) === false) {
            throw new \Exception("Unable to write to config file: $configFile");
        }
    }

    private function getConfigFileContent($userInput, $encryptionKey)
    {
        $config = [
            'db' => [
                'website' => [
                    'type' => 'mysql',
                    'host' => $userInput['website_db_host'],
                    'database' => $userInput['website_db_name'],
                    'username' => $userInput['website_db_username'],
                    'password' => $userInput['website_db_password'],
                    'charset' => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                    'port' => $userInput['website_db_port']
                ],
                'auth' => [
                    'type' => 'mysql',
                    'host' => $userInput['auth_db_host'],
                    'database' => $userInput['auth_db_name'],
                    'username' => $userInput['auth_db_username'],
                    'password' => $userInput['auth_db_password'],
                    'charset' => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                    'port' => $userInput['auth_db_port']
                ],
                'characters' => [
                    'type' => 'mysql',
                    'host' => $userInput['char_db_host'],
                    'database' => $userInput['char_db_name'],
                    'username' => $userInput['char_db_username'],
                    'password' => $userInput['char_db_password'],
                    'charset' => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                    'port' => $userInput['char_db_port']
                ]
            ],
            'encryption_key' => $encryptionKey
        ];

        return "<?php\n\nreturn " . var_export($config, true) . ";\n";
    }

    private function createInstallLockFile()
    {
        $lockFile = $this->getInstallLockFilePath();
        if (file_put_contents($lockFile, date('Y-m-d H:i:s')) === false) {
            throw new \Exception("Unable to create install lock file: $lockFile");
        }
    }

    private function getConfigFilePath()
    {
        return __DIR__ . '/../../Config/config.php';
    }

    private function getInstallLockFilePath()
    {
        return __DIR__ . '/../../install.lock';
    }

    private function generateEncryptionKey($length = 32)
    {
        return bin2hex(random_bytes($length));
    }
}