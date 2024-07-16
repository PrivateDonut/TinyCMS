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

require_once __DIR__ . '/../../engine/controllers/BaseController.php';

class HomeController extends BaseController
{
    public function handle($action, $params)
    {
        switch ($action) {
            case 'index':
            default:
                return $this->index();
        }
    }

    private function index()
    {
        $data = [
            'title' => 'Admin Dashboard',
            'total_users' => $this->getTotalUsers(),
            'total_posts' => $this->getTotalPosts(),
        ];

        return $this->render('home.twig', $data);
    }

    private function getTotalUsers()
    {
        $database = new Database();
        $authConnection = $database->getConnection('auth');
        return $authConnection->count('account');
    }

    private function getTotalPosts()
    {
        $database = new Database();
        $websiteConnection = $database->getConnection('website');
        return $websiteConnection->count('news');
    }
}
