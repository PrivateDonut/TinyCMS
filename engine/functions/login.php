<?php

class Login
{
    private $username;
    private $password;
    private $auth_connection;
    private $website_connection;

    public function __construct($username, $password)
    {
        $database = new Database();
        $this->username = $username;
        $this->password = $password;
        $this->auth_connection = $database->getConnection('auth');
        $this->website_connection = $database->getConnection('website');
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
        $gm_rank = $this->website_connection->get('access', 'access_level', ['account_id' => $id]);
        return $gm_rank;
    }
    
    // Insert account ID into the website->users table if the account ID isn't found to avoid possibly errors.
    private function insert_account_id($id)
    {
        // Check if account_id exists
        $account_id = $this->website_connection->get('users', 'account_id', ['account_id' => $id]);
    
        // If account_id doesn't exist, insert it
        if ($account_id === null) {
            $this->website_connection->insert('users', ['account_id' => $id]);
        }
    }
    
    public function login()
    {
        // Fetch account details
        $account = $this->auth_connection->get('account', [
            'id', 'username', 'verifier', 'salt'
        ], [
            'username' => $this->username
        ]);
    
        if ($account) {
            $global = new GlobalFunctions();
            $check_verifier = $global->calculate_verifier($account['username'], $this->password, $account['salt']);
            if ($check_verifier == $account['verifier']) {
                $_SESSION['account_id'] = $account['id'];
                $_SESSION['username'] = $account['username'];
                $_SESSION['isAdmin'] = $this->get_rank($account['id']);
                $this->insert_account_id($account['id']); 
                header("Location: ?page=home");
                return true;
            } else {
                $_SESSION['error'] = "Incorrect username or password.";
                header("Location: ?page=login");
                return false;
            }
        } else {
            $_SESSION['error'] = "User not found.";
            header("Location: ?page=login");
            return false;
        }
    }
    
    

}
