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

namespace Core;

class CSRFProtection
{
    private $session;

    public function __construct(SessionManager $session)
    {
        $this->session = $session;
    }

    public function generateToken()
    {
        $token = bin2hex(random_bytes(32));
        $this->session->set('csrf_token', $token);
        return $token;
    }

    public function validateToken($token)
    {
        $storedToken = $this->session->get('csrf_token');
        return hash_equals($storedToken, $token);
    }
}