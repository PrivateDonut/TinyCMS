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

namespace Core;

use Medoo\Medoo;

class Database
{
    private static $instances = [];
    private $config;

    private function __construct()
    {
        $this->config = require __DIR__ . '/../Config/config.php';
    }

    public static function getInstance(): self
    {
        if (!isset(self::$instances['database'])) {
            self::$instances['database'] = new self();
        }
        return self::$instances['database'];
    }

    public function getConnection(string $name = 'website'): Medoo
    {
        if (!isset(self::$instances[$name])) {
            if (!isset($this->config['db'][$name])) {
                throw new \Exception("Database configuration for '{$name}' not found.");
            }

            $dbConfig = $this->config['db'][$name];
            $medooConfig = [
                'type' => $dbConfig['type'],
                'host' => $dbConfig['host'],
                'database' => $dbConfig['database'],
                'username' => $dbConfig['username'],
                'password' => $dbConfig['password'],
                'charset' => $dbConfig['charset'],
                'collation' => $dbConfig['collation'],
                'port' => $dbConfig['port'],
            ];

            self::$instances[$name] = new Medoo($medooConfig);
        }

        return self::$instances[$name];
    }

    // Prevent cloning of the instance
    private function __clone() {}

    // Prevent unserializing of the instance
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize singleton");
    }
}