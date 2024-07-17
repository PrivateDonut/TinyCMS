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

namespace Plugins\TrinityLogin\Controllers;
use DonutCMS\Models\Database;

use DonutCMS\XSSProtection;
use Plugins\TrinityLogin\Models\Login;
use Exception;
use CSRFTrait;

class LoginController
{
    private $twig;
    private $session;

    public function __construct($twig, $session)
    {
        $this->twig = $twig;
        $this->session = $session;
    }

    public function view()
    {
        if ($this->session->get('account_id')) {
            header("Location: home");
            exit();
        }

        return $this->twig->render('@trinity_login/login.twig', [
            'flash_messages' => $this->session->getFlashBag()->all(),
            'csrf_token' => $this->session->get('_csrf/token')
        ]);
    }

    public function authenticate()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->session->getFlashBag()->add('error', "Invalid request method.");
            header("Location: /login");
            exit();
        }

        // Validate CSRF token
        if (!$this->validateCsrfToken($_POST['csrf_token'] ?? '')) {
            $this->session->getFlashBag()->add('error', "Invalid CSRF token.");
            header("Location: /login");
            exit();
        }

        $username = XSSProtection::clean(trim($_POST['username'] ?? ''));
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            $this->session->getFlashBag()->add('error', "Please fill in all fields.");
            header("Location: /login");
            exit();
        }

        try {
            $login = new Login($username, $password, $this->session);
            if ($login->login()) {
                $this->session->getFlashBag()->add('success', "Login successful!");
                header("Location: /home");
                exit();
            }
        } catch (Exception $e) {
            $this->session->getFlashBag()->add('error', $e->getMessage());
        }

        header("Location: /login");
        exit();
    }

    public function logout()
    {
        $this->session->clear();
        $this->session->getFlashBag()->add('success', "You have been logged out successfully.");
        header("Location: /login");
        exit();
    }

    private function validateCsrfToken($token)
    {
        $csrfToken = $this->session->get('_csrf/token');
        if ($csrfToken === null) {
            return false;
        }
        return hash_equals($csrfToken, $token);
    }
}
