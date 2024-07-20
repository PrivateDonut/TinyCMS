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
namespace DonutCMS\Models;
require_once BASE_DIR . '/vendor/autoload.php';
require_once BASE_DIR . '/engine/configs/db_config.php';

use Configuration;
use Medoo\Medoo;

class Database
{
    private $instances = [];
    private $configuration;

    public function __construct()
    {
        $this->configuration = new Configuration();
    }

    public function getConnection($name)
    {
        $dbConfig = $this->configuration->get_config('db');

        if (!isset($this->instances[$name])) {
            if (!$dbConfig) {
                throw new Exception("Configuration not loaded properly. Config is null.");
            }

            if (isset($dbConfig[$name])) {
                $this->instances[$name] = new Medoo($dbConfig[$name]);
            } else {
                throw new Exception("Database configuration for '$name' not found.");
            }
        }
        return $this->instances[$name];
    }
}
