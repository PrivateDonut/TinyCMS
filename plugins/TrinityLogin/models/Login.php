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

namespace Plugins\TrinityLogin\Models;

use DonutCMS\Models\Database;
use Exception;
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

    public function login()
    {
        try {
            $this->checkRateLimit();

            $account = $this->auth_connection->get('account', [
                'id', 'username', 'verifier', 'salt'
            ], [
                'username' => $this->username
            ]);

            if (!$account) {
                throw new Exception('Invalid login credentials.');
            }

            $check_verifier = $this->calculateVerifier($account['username'], $this->password, $account['salt']);
            if ($check_verifier != $account['verifier']) {
                throw new Exception('Invalid login credentials.');
            }

            $this->session->set('account_id', $account['id']);
            $this->session->set('username', $account['username']);
            $this->session->set('isAdmin', $this->getRank($account['id']));
            $this->insertAccountId($account['id']);

            // Reset login attempts on successful login
            $this->website_connection->delete('login_attempts', ['ip' => $_SERVER['REMOTE_ADDR']]);

            return true;
        } catch (Exception $e) {
            throw $e;
        }
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


    private function calculateVerifier($username, $password, $salt)
    {
        $g = gmp_init(7);
        $N = gmp_init('894B645E89E1535BBDAD5B8B290650530801B18EBFBF5E8FAB3C82872A3E9BB7', 16);
        $h1 = sha1(strtoupper($username . ':' . $password), TRUE);
        $h2 = sha1($salt . $h1, TRUE);
        $h2 = gmp_import($h2, 1, GMP_LSW_FIRST);
        $verifier = gmp_powm($g, $h2, $N);
        $verifier = gmp_export($verifier, 1, GMP_LSW_FIRST);
        $verifier = str_pad($verifier, 32, chr(0), STR_PAD_RIGHT);
        return $verifier;
    }

    private function getRank($id)
    {
        return $this->website_connection->get('access', 'access_level', ['account_id' => $id]);
    }

    private function insertAccountId($id)
    {
        $account_id = $this->website_connection->get('users', 'account_id', ['account_id' => $id]);
        if ($account_id === null) {
            $this->website_connection->insert('users', ['account_id' => $id]);
        }
    }
}
