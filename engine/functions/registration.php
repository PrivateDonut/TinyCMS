<?php

class Registration
{
    private $username;
    private $email;
    private $password;
    private $password_confirmation;
    private $connection;

    public function __construct($username, $email, $password, $password_confirmation)
    {
        $database = new Database();
        $this->connection = $database->getConnection('auth');
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->password_confirmation = $password_confirmation;
    }

    public function register_checks()
    {
        $this->check_username($this->username);
        $this->check_email($this->email);
        $this->check_password($this->password);
        $this->register($this->username, $this->email, $this->password);
    }

    private function check_username($username)
    {
        $result = $this->connection->has('account', ['username' => $username]);

        if ($result) {
            $_SESSION['error'] = "Username already registered";
            header("Location: " . BASE_DIR . "/?page=register");
            exit();
        }

        if (strlen($username) < 3 || strlen($username) > 16) {
            $_SESSION['error'] = "Username must be between 3 and 16 characters long";
            header("Location: " . BASE_DIR . "/?page=register");
            exit();
        }
    }

    private function check_password($password)
    {
        if (strlen($password) < 6 ||
            !preg_match("#[0-9]+#", $password) ||
            !preg_match("#[a-z]+#", $password) ||
            !preg_match("#[A-Z]+#", $password)
        ) {
            $_SESSION['error'] = "Password must be at least 6 characters long and contain at least one number, one uppercase letter, and one lowercase letter";
            header("Location: " . BASE_DIR . "/?page=register");
            exit();
        }

        if ($password != $this->password_confirmation) {
            $_SESSION['error'] = "Passwords do not match";
            header("Location: " . BASE_DIR . "/?page=register");
            exit();
        }
    }

    private function check_email($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Invalid email address";
            header("Location: " . BASE_DIR . "/?page=register");
            exit();
        }

        $result = $this->connection->has('account', ['email' => $email]);

        if ($result) {
            $_SESSION['error'] = "Email already registered";
            header("Location: " . BASE_DIR . "/?page=register");
            exit();
        }
    }

    private function register($username, $email, $password)
    {
        $salt = random_bytes(32);
        $global = new GlobalFunctions();
        $verifier = $global->calculate_verifier($username, $password, $salt);
        $expansion = 2;

        $this->connection->insert('account', [
            'username' => $username,
            'salt' => $salt,
            'verifier' => $verifier,
            'expansion' => $expansion
        ]);

        header("Location: /?page=login");
        exit();
    }
}
?>
