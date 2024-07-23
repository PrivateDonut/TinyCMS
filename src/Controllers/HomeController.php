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

namespace Controllers;

use Core\Database;
use Models\Home;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class HomeController
{
    private $db;
    private $twig;

    public function __construct()
    {
        $database = Database::getInstance();
        $this->db = $database->getConnection();

        $loader = new FilesystemLoader(__DIR__ . '/../Views/default');
        $this->twig = new Environment($loader);
    }

    public function index()
    {
        $home = new Home();
        $message = $home->getMessage();
        echo $this->twig->render('home.twig', ['title' => 'Welcome', 'message' => $message]);
    }
}