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

require_once __DIR__ . '/BaseController.php';

class LoginController extends BaseController
{
    private $session;

    public function __construct($twig, $global, $config, $session)
    {
        parent::__construct($twig, $global, $config);
        $this->session = $session;
    }

    public function handle($action, $params)
    {
        switch ($action) {
            case 'view':
                return $this->viewLogin();
            case 'authenticate':
                return $this->authenticate();
            case 'logout':
                return $this->logout();
            default:
                return $this->viewLogin();
        }
    }

    private function viewLogin()
    {
        return $this->render('login.twig', [
            'session' => $this->session->all()
        ]);
    }

    private function authenticate()
    {
        $username = $_POST['username'] ?? null;
        $password = $_POST['password'] ?? null;

        if ($username && $password) {
            $login = new Login($username, $password, $this->session);
            if ($login->login()) {
                header("Location: home");
                exit();
            }
        }

        $this->session->set('error', "Invalid username or password.");
        header("Location: login");
        exit();
    }

    private function logout()
    {
        $this->session->clear();
        header("Location: login");
        exit();
    }
}