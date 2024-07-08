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

class Login
{
    private $username;
    private $password;
    private $auth_connection;
    private $website_connection;
    private $session;

    public function __construct($username, $password, \Symfony\Component\HttpFoundation\Session\Session $session)
    {
        $database = new Database();
        $this->username = $username;
        $this->password = $password;
        $this->session = $session;
        $this->auth_connection = $database->getConnection('auth');
        $this->website_connection = $database->getConnection('website');
    }

    public function login_checks()
    {
        if (empty($this->username) || empty($this->password)) {
            $this->session->set('error', "Please enter a username and password.");
            header("Location: login");
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
        $account_id = $this->website_connection->get('users', 'account_id', ['account_id' => $id]);

        // If account_id doesn't exist, insert it
        if ($account_id === null) {
            $this->website_connection->insert('users', ['account_id' => $id]);
        }
    }

    public function login()
    {
        $account = $this->auth_connection->get('account', [
            'id', 'username', 'verifier', 'salt'
        ], [
            'username' => $this->username
        ]);

        if ($account) {
            $global = new GlobalFunctions();
            $check_verifier = $global->calculate_verifier($account['username'], $this->password, $account['salt']);
            if ($check_verifier == $account['verifier']) {
                $this->session->set('account_id', $account['id']);
                $this->session->set('username', $account['username']);
                $this->session->set('isAdmin', $this->get_rank($account['id']));
                $this->insert_account_id($account['id']);
                header("Location: home");
                return true;
            } else {
                $this->session->set('error', "Incorrect username or password.");
                header("Location: login");
                return false;
            }
        } else {
            $this->session->set('error', "User not found.");
            header("Location: login");
            return false;
        }
    }
}
