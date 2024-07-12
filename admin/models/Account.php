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

class account_admin
{
    private $website_connection;
    private $auth_connection;

    public function __construct()
    {
        $database = new Database();
        $this->website_connection = $database->getConnection('website');
        $this->auth_connection = $database->getConnection('auth');
    }

    public function get_accounts()
    {
        $accounts = $this->website_connection->select('accounts', [
            'id', 'username', 'email', 'role', 'created_at'
        ], [
            'ORDER' => ['id' => 'DESC']
        ]);

        return $accounts;
    }

    public function get_account_by_id($id)
    {
        $account = $this->website_connection->get("accounts", "*", ["id" => $id]);
        return $account ? $account : null;
    }

    public function add_account($username, $password, $email, $role)
    {
        $this->website_connection->insert("accounts", [
            "username" => $username,
            "password" => $password,
            "email" => $email,
            "role" => $role,
            "created_at" => date('Y-m-d H:i:s')
        ]);
    }

    public function update_account($id, $username, $password, $email, $role)
    {
        $this->website_connection->update("accounts", [
            "username" => $username,
            "password" => $password,
            "email" => $email,
            "role" => $role
        ], [
            "id" => $id
        ]);
    }

    public function delete_account($id)
    {
        $this->website_connection->delete("accounts", ["id" => $id]);
    }
}
