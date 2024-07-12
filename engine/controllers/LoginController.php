<?php

require_once __DIR__ . '/BaseController.php';

class LoginController extends BaseController
{
    use CSRFTrait;

    public function __construct($twig, $global, $config, $session, $pluginManager)
    {
        parent::__construct($twig, $global, $config, $session, $pluginManager);
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
        if ($this->session->get('account_id')) {
            // User is already logged in, redirect to home
            header("Location: home");
            exit();
        }

        return $this->render('login.twig', [
            'flash_messages' => $this->session->getFlashBag()->all()
        ]);
    }

    private function authenticate()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->session->getFlashBag()->add('error', "Invalid request method.");
            header("Location: login");
            exit();
        }
        // Validate CSRF token
        if (!$this->validateCsrfToken($_POST['csrf_token'] ?? '')) {
            error_log("CSRF validation failed");
            $this->session->getFlashBag()->add('error', "Invalid CSRF token.");
            header("Location: login");
            exit();
        }

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            $this->session->getFlashBag()->add('error', "Please fill in all fields.");
            header("Location: login");
            exit();
        }

        try {
            $login = new Login($username, $password, $this->session);
            if ($login->login()) {
                // Login successful
                $this->session->getFlashBag()->add('success', "Login successful!");
                error_log("Redirecting to home after successful login");
                header("Location: home");
                exit();
            } else {
                header("Location: login");
                $this->session->getFlashBag()->add('error', "Invalid username or password.");
                exit();
            }
        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            $this->session->getFlashBag()->add('error', $e->getMessage());
            header("Location: login");
            exit();
        }

        error_log("Redirecting back to login page");
        header("Location: login");
        exit();
    }

    private function logout()
    {
        $this->session->clear();
        $this->session->getFlashBag()->add('success', "You have been logged out successfully.");
        header("Location: login");
        exit();
    }
}
