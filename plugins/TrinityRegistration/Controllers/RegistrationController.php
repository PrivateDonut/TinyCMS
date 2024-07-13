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

namespace Plugins\TrinityRegistration\Controllers;

use DonutCMS\XSSProtection;

use Twig\Environment;
use Symfony\Component\HttpFoundation\Session\Session;
use Plugins\TrinityRegistration\Models\Registration;
use Exception;
use CSRFTrait;

class RegistrationController
{
    use CSRFTrait;

    private Environment $twig;
    private Session $session;

    public function __construct(Environment $twig, Session $session)
    {
        $this->twig = $twig;
        $this->session = $session;
    }

    public function index(): string
    {
        error_log("Attempting to render register template");
        try {
            $content = $this->twig->render('@trinity_registration/register.twig', [
                'flash_messages' => $this->session->getFlashBag()->all()
            ]);
            error_log("Template rendered successfully");
            return $content;
        } catch (\Twig\Error\LoaderError $e) {
            error_log("Failed to load template: " . $e->getMessage());
            throw $e;
        }
    }

    public function submit(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->session->getFlashBag()->add('error', 'Invalid request method.');
            header("Location: /register");
            exit();
        }

        error_log("Registration form submitted");

        // Validate CSRF token
        if (!$this->validateCsrfToken($_POST['csrf_token'] ?? '')) {
            error_log("CSRF validation failed");
            $this->session->getFlashBag()->add('error', "Invalid CSRF token.");
            header("Location: /register");
            exit();
        }

        $username = XSSProtection::clean(trim($_POST['username'] ?? ''));
        $email = XSSProtection::clean(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['password_confirmation'] ?? '';

        if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
            $this->session->getFlashBag()->add('error', 'All fields are required.');
            header("Location: /register");
            exit();
        }

        $username = XSSProtection::clean(trim($_POST['username'] ?? ''));
        $password = $_POST['password'] ?? '';

        $originalUsername = trim($_POST['username'] ?? '');
        if ($username !== $originalUsername) {
            $this->session->getFlashBag()->add('error', "Your username contained invalid characters and was modified for security reasons.");
            header("Location: /register");
        }

        if (strlen($username) < 3 || strlen($username) > 32) {
            $this->session->getFlashBag()->add('error', 'Username must be between 3 and 20 characters.');
            header("Location: /register");
            exit();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->session->getFlashBag()->add('error', 'Invalid email address.');
            header("Location: /register");
            exit();
        }

        if ($password !== $confirm_password) {
            $this->session->getFlashBag()->add('error', 'Passwords do not match.');
            header("Location: /register");
            exit();
        }

        $registration = new Registration($username, $email, $password, $confirm_password, $this->session);

        try {
            $result = $registration->register();
            error_log("Registration successful for user: $username");
            $this->session->getFlashBag()->add('success', 'Registration successful! You can now log in.');
            header("Location: /login");
            exit();
        } catch (Exception $e) {
            error_log("Exception during registration: " . $e->getMessage());
            $this->session->getFlashBag()->add('error', $e->getMessage());
            header("Location: /register");
            exit();
        }
    }
}
