<?php

class Account
{
    private $username;
    private $auth_connection;
    private $website_connection;

    public function __construct() {
        $database = new Database();
        $this->auth_connection = $database->getConnection('auth');
        $this->website_connection = $database->getConnection('website');
    }

    private function get_account()
    {
        $account = $this->auth_connection->get('account', [
            'id', 'username', 'email', 'joindate', 'last_ip', 'last_login'
        ], [
            'username' => $this->username
        ]);

        return $account;
    }

    public function get_rank()
    {
        $account = $this->get_account();
        $rank = $this->website_connection->get('access', 'access_level', [
            'account_id' => $account['id']
        ]);

        return $rank ? $rank : 'Player';
    }

    public function get_account_currency()
    {
        $account = $this->get_account();
        $currency = $this->website_connection->get('users', [
            'vote_points', 'donor_points'
        ], [
            'account_id' => $account['id']
        ]);

        return $currency ? $currency : 0;
    }

    public function is_banned()
    {
        $account = $this->get_account();
        $banned = $this->auth_connection->get('account_banned', 'active', [
            'id' => $account['id']
        ]);

        return $banned ? 'Banned' : 'Good standing';
    }

    private function get_password_data()
    {
        $account = $this->get_account();
        return $this->auth_connection->get('account', [
            'salt', 'verifier'
        ], [
            'id' => $account['id']
        ]);
    }

    public function change_password($old_password, $new_password)
    {
        $password_data = $this->get_password_data();
        $salt = $password_data['salt'];
        $verifier = $password_data['verifier'];

        $global = new GlobalFunctions();

        $old_verifier = $global->calculate_verifier($this->username, $old_password, $salt);
        $new_verifier = $global->calculate_verifier($this->username, $new_password, $salt);

        if ($old_verifier == $verifier) {
            $this->auth_connection->update('account', [
                'verifier' => $new_verifier
            ], [
                'id' => $this->get_id()
            ]);
            return true;
        } else {
            return false;
        }
    }

    public function get_id()
    {
        $account = $this->get_account();
        return $account['id'];
    }

    public function get_username()
    {
        return $this->username;
    }

    public function get_email()
    {
        $account = $this->get_account();
        return $account['email'];
    }

    public function get_joindate()
    {
        $account = $this->get_account();
        return $account['joindate'];
    }

    public function get_last_ip()
    {
        $account = $this->get_account();
        return $account['last_ip'];
    }

    public function get_last_login()
    {
        $account = $this->get_account();
        return $account['last_login'];
    }
}
?>
