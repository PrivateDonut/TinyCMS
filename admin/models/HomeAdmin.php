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

class HomeAdmin
{
    private $websiteConnection;
    private $authConnection;
    private $charConnection;

    public function __construct()
    {
        $database = new Database();
        $this->websiteConnection = $database->getConnection('website');
        $this->authConnection = $database->getConnection('auth');
        $this->charConnection = $database->getConnection('characters');
    }

    public function getOnlinePlayers()
    {
        return $this->authConnection->count('account', ['online' => 1]);
    }

    public function getTotalCharacters()
    {
        return $this->charConnection->count('characters');
    }

    public function getTotalAccounts()
    {
        return $this->authConnection->count('account');
    }

    public function getTotalPosts()
    {
        return $this->websiteConnection->count('news');
    }
}