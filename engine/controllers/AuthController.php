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

require_once __DIR__ . '/BaseController.php';

class AccountController extends BaseController
{
    private $session;

    public function __construct($twig, $global, $config, $session)
    {
        parent::__construct($twig, $global, $config);
        $this->session = $session;
    }

    public function handle($action, $params)
    {
        switch ($action) {
            case 'view':
                return $this->viewAccount();
            case 'changePassword':
                return $this->changePassword();
            default:
                return $this->viewAccount();
        }
    }

    private function viewAccount()
    {
        $this->global->check_logged_in();
        $account = new Account($this->session->get('username'));
        $character = new Character();
        $characters = $character->get_characters($this->session->get('account_id'));

        return $this->render('account.twig', [
            'account' => $account,
            'characters' => $characters
        ]);
    }

    private function changePassword()
    {
        $this->global->check_logged_in();
        $account = new Account($this->session->get('username'));

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($_POST['new_password'] == $_POST['confirm_password']) {
                $change_password_status = $account->change_password($_POST['old_password'], $_POST['new_password']);
                return $this->render('changepassword.twig', [
                    'change_password_status' => $change_password_status
                ]);
            } else {
                return $this->render('changepassword.twig', [
                    'password_mismatch' => true
                ]);
            }
        }

        return $this->render('changepassword.twig');
    }
}
