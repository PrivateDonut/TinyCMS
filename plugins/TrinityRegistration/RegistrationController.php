<?php
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
