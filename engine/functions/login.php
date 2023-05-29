<?php

class Login
{
    private $username;
    private $password;
    private $auth_connection;
    private $website_connection;

    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
        $config = new Configuration();
        $this->auth_connection = $config->getDatabaseConnection('auth');
        $this->website_connection = $config->getDatabaseConnection('website');
    }

    public function login_checks()
    {
        if (empty($this->username) || empty($this->password)) {
            $_SESSION['error'] = "Please enter a username and password.";
            header("Location: ?page=login");
            return false;
        }
    }

    private function get_rank($id)
    {
        $stmt = $this->website_connection->prepare("SELECT access_level FROM access WHERE account_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($gm_rank);
        $stmt->fetch();
        $stmt->close();
        return $gm_rank;
    }


    // Insert account ID into the website->users table if the account ID isn't found to avoid possibly errors.
    private function insert_account_id($id)
    {
        $stmt = $this->website_connection->prepare("SELECT account_id FROM users WHERE account_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($account_id);
        $stmt->fetch();
        $stmt->close();
        if ($account_id == null) {
            $stmt = $this->website_connection->prepare("INSERT INTO users (account_id) VALUES (?)");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
        }
    }

    public function login()
    {
        $stmt = $this->auth_connection->prepare("SELECT id, username, verifier, salt FROM account WHERE username = ?");
        $stmt->bind_param("s", $this->username);
        $stmt->execute();
        $stmt->bind_result($id, $username, $verifier, $salt);
        if ($stmt->fetch()) {
            $global = new GlobalFunctions();
            $check_verifier = $global->calculate_verifier($username, $this->password, $salt);
            if ($check_verifier == $verifier) {
                $_SESSION['account_id'] = $id;
                $_SESSION['username'] = $username;
                $_SESSION['isAdmin'] = $this->get_rank($id);
                $this->insert_account_id($id); 
                header("Location: ?page=home");
                return true;
            } else {
                $_SESSION['error'] = "Incorrect username or password.";
                header("Location: ?page=login");
                return false;
            }
        }
    }
    

}
