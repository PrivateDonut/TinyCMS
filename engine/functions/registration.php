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
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->password_confirmation = $password_confirmation;
        $this->connection = (new Configuration())->getDatabaseConnection('auth');
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
        $stmt = $this->connection->prepare("SELECT username FROM account WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $_SESSION['error'] = "Username already registered";
            header("Location: /?page=register");
            exit();
        }
    
        if (strlen($username) < 3 || strlen($username) > 16) {
            $_SESSION['error'] = "Username must be between 3 and 16 characters long";
            header("Location: /?page=register");
            exit();
        }
        $stmt->close();
    }
    
 
    private function check_password($password)
    {
        if (strlen($password) < 6 ||
            !preg_match("#[0-9]+#", $password) ||
            !preg_match("#[a-z]+#", $password) ||
            !preg_match("#[A-Z]+#", $password)
        ) {
            $_SESSION['error'] = "Password must be at least 6 characters long and contain at least one number, one uppercase letter and one lowercase letter";
            header("Location: /?page=register");
            exit();
        }
    
        if ($password != $this->password_confirmation) {
            $_SESSION['error'] = "Passwords do not match";
            header("Location: /?page=register");
            exit();
        }
    }

    private function check_email($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Invalid email address";
            header("Location: /?page=register");
            exit();
        }

        $stmt = $this->connection->prepare("SELECT email FROM account WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $_SESSION['error'] = "Email already registered";
            header("Location: /?page=register");
            exit();
        }
        $stmt->close();
    }


    private function register($username, $email, $password)
    {
        $stmt = $this->connection->prepare("INSERT INTO account (username, salt, verifier, expansion) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $salt, $verifier, $expansion);
        $username = $this->username;
        $email = $this->email;
        $password = $this->password;
        $salt = random_bytes(32);
        $global = new GlobalFunctions();
        $verifier = $global->calculate_verifier($username, $password, $salt);
        $expansion = 2;
        $stmt->execute();
        header("Location: /?page=login");
        $stmt->close();
        $this->connection->close();
    }
}
