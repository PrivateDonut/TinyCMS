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

use Symfony\Component\HttpFoundation\Session\Session;

class Login
{
    private $username;
    private $password;
    private $auth_connection;
    private $website_connection;
    private $session;
    private $max_attempts = 5;
    private $lockout_time = 300; // 5 minutes in seconds

    public function __construct($username, $password, Session $session)
    {
        $database = new Database();
        $this->username = $username;
        $this->password = $password;
        $this->auth_connection = $database->getConnection('auth');
        $this->website_connection = $database->getConnection('website');
        $this->session = $session;
    }

    private function checkRateLimit()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $attempts = $this->website_connection->get('login_attempts', [
            'attempts',
            'last_attempt'
        ], [
            'ip' => $ip
        ]);

        if ($attempts) {
            if ($attempts['attempts'] >= $this->max_attempts) {
                $time_passed = time() - $attempts['last_attempt'];
                if ($time_passed < $this->lockout_time) {
                    throw new Exception("Too many login attempts. Please try again later.");
                } else {
                    // Reset attempts after lockout time
                    $this->website_connection->update('login_attempts', [
                        'attempts' => 1,
                        'last_attempt' => time()
                    ], [
                        'ip' => $ip
                    ]);
                }
            } else {
                // Increment attempts
                $this->website_connection->update('login_attempts', [
                    'attempts[+]' => 1,
                    'last_attempt' => time()
                ], [
                    'ip' => $ip
                ]);
            }
        } else {
            // First attempt
            $this->website_connection->insert('login_attempts', [
                'ip' => $ip,
                'attempts' => 1,
                'last_attempt' => time()
            ]);
        }
    }

    public function login()
    {
        try {
            $this->checkRateLimit();

            if (empty($this->username) || empty($this->password)) {
                throw new Exception('Please fill in all fields.');
            }

            // Fetch account details
            $account = $this->auth_connection->get('account', [
                'id', 'username', 'verifier', 'salt'
            ], [
                'username' => $this->username
            ]);

            if (!$account) {
                throw new Exception('Invalid login credentials.');
            }

            $global = new GlobalFunctions();
            $check_verifier = $global->calculate_verifier($account['username'], $this->password, $account['salt']);
            $stored_verifier = hash('sha256', $account['verifier']);
            $user_input_verifier = hash('sha256', $check_verifier);

            if (!hash_equals($stored_verifier, $user_input_verifier)) {
                throw new Exception('Invalid login credentials.');
            }

            $this->session->migrate(true); // Regenerate session ID
            $this->session->set('account_id', $account['id']);
            $this->session->set('username', $account['username']);
            $this->session->set('isAdmin', $this->get_rank($account['id']));
            $this->insert_account_id($account['id']);

            // Reset login attempts on successful login
            $this->website_connection->delete('login_attempts', ['ip' => $_SERVER['REMOTE_ADDR']]);
            //header("Location: home");
            exit();
        } catch (Exception $e) {
            $this->session->getFlashBag()->add('error', $e->getMessage());
            //header("Location: login");
            exit();
        }
    }

    private function get_rank($id)
    {
        return $this->website_connection->get('access', 'access_level', ['account_id' => $id]);
    }

    private function insert_account_id($id)
    {
        $account_id = $this->website_connection->get('users', 'account_id', ['account_id' => $id]);
        if ($account_id === null) {
            $this->website_connection->insert('users', ['account_id' => $id]);
        }
    }
}
