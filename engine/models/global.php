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

namespace DonutCMS\Models;

use DonutCMS\Models\Database;
use Symfony\Component\HttpFoundation\Session\Session;

class GlobalFunctions
{
    private $auth_connection;
    private $website_connection;
    private $session;

    public function __construct(Database $database, Session $session)
    {
        $this->auth_connection = $database->getConnection('auth');
        $this->website_connection = $database->getConnection('website');
        $this->session = $session;
    }

    public function logout()
    {
        session_destroy();
        header("Location: /home");
        exit();
    }

    public function check_logged_in()
    {
        if ($this->session->get('account_id')) {
            return true;
        } else {
            header("Location: /login");
            exit();
        }
    }

    public function check_is_admin()
    {
        if (!$this->session->has('account_id') || $this->get_role() !== 'Admin') {
            $this->session->getFlashBag()->add('error', 'You are not authorized to access the admin panel.');
            $this->session->save();
            $this->session->migrate(true);
            header("Location: /login");
            exit();
        }
    }

    private function get_role()
    {
        $account_id = $this->session->get('account_id');
        if (!$account_id) {
            return 'Guest';
        }

        $role = $this->auth_connection->get("account_access", "SecurityLevel", [
            "AccountID" => $account_id
        ]);

        return $role ? ($role == 3 ? 'Admin' : 'Player') : 'Player';
    }

    public function calculate_verifier($username, $password, $salt)
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
