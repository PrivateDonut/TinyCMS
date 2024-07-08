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

class RegistrationController
{
    private $twig;
    private $session;

    public function __construct($twig, $session)
    {
        $this->twig = $twig;
        $this->session = $session;
    }

    public function index()
    {
        return $this->twig->render('@trinity_registration/register.twig', [
            'flash_messages' => $this->session->getFlashBag()->all()
        ]);
    }

    public function submit()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            $registration = new Registration($username, $email, $password, $confirm_password, $this->session);

            try {
                $registration->register();
                $this->session->getFlashBag()->add('success', 'Registration successful! You can now log in.');
                header("Location: /login");
                exit();
            } catch (Exception $e) {
                $this->session->getFlashBag()->add('error', $e->getMessage());
                header("Location: /register");
                exit();
            }
        }
    }
}
