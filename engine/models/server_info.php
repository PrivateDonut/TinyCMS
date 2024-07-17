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

use DonutCMS\Models\Database;

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
