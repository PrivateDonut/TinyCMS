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

namespace Plugins\TrinityRegistration\Models;


use DonutCMS\Models\Database;
use Exception;
use Symfony\Component\HttpFoundation\Session\Session;

class Registration
{
    private $username;
    private $email;
    private $password;
    private $password_confirmation;
    private $connection;
    private $session;

    public function __construct($username, $email, $password, $password_confirmation, $session)
    {
        $database = new Database();
        $this->connection = $database->getConnection('auth');
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->password_confirmation = $password_confirmation;
        $this->session = $session;
    }

    public function register()
    {
        try {
            $this->check_username($this->username);
            $this->check_email($this->email);
            $this->check_password($this->password);
            $this->create_account($this->username, $this->email, $this->password);
            return true;
        } catch (Exception $e) {
            error_log("Registration failed: " . $e->getMessage());
            throw $e;
        }
    }

    private function check_username($username)
    {
        $result = $this->connection->has('account', ['username' => $username]);
        if ($result) {
            throw new Exception("Username already registered");
        }
        if (strlen($username) < 3 || strlen($username) > 16) {
            throw new Exception("Username must be between 3 and 16 characters long");
        }
    }

    private function check_password($password)
    {
        if (
            strlen($password) < 6 ||
            !preg_match("#[0-9]+#", $password) ||
            !preg_match("#[a-z]+#", $password) ||
            !preg_match("#[A-Z]+#", $password)
        ) {
            throw new Exception("Password must be at least 6 characters long and contain at least one number, one uppercase letter, and one lowercase letter");
        }
        if ($password != $this->password_confirmation) {
            throw new Exception("Passwords do not match. Please try again.");
        }
    }

    private function check_email($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email address");
        }
        $result = $this->connection->has('account', ['email' => $email]);
        if ($result) {
            throw new Exception("Email already registered");
        }
    }

    private function create_account($username, $email, $password)
    {
        $salt = random_bytes(32);
        $verifier = $this->calculate_verifier($username, $password, $salt);
        $expansion = 2;

        $result = $this->connection->insert('account', [
            'username' => $username,
            'email' => $email,
            'salt' => $salt,
            'verifier' => $verifier,
            'expansion' => $expansion
        ]);

        if (!$result) {
            throw new Exception("Failed to create account");
        }
    }
    private function calculate_verifier($username, $password, $salt)
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
}
