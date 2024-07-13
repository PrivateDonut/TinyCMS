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

use DonutCMS\XSSProtection;

class AccountController extends BaseController
{
    public function handle($action, $params)
    {
        $this->global->check_logged_in();

        switch ($action) {
            case 'view':
                return $this->viewAccount();
            case 'changepassword':
                return $this->changePassword();
            default:
                return $this->viewAccount();
        }
    }

    private function viewAccount()
    {
        $account = new Account($this->session->get('username'));
        $character = new Character();
        $characters = $character->get_characters($this->session->get('account_id'));

        $accountData = [
            'username' => $account->get_username(),
            'email' => $account->get_email(),
            'last_login' => $account->get_last_login(),
            'status' => $account->is_banned(),
            'currency' => $account->get_account_currency(),
        ];

        return $this->render('account.twig', [
            'account' => $accountData,
            'characters' => $characters
        ]);
    }

    private function changePassword()
    {
        $account = new Account($this->session->get('username'));

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate CSRF token
            if (!$this->validateCsrfToken($_POST['csrf_token'] ?? '')) {
                $this->session->getFlashBag()->add('error', "Invalid CSRF token.");
                return $this->render('changepassword.twig');
            }

            $oldPassword = XSSProtection::clean($_POST['old_password'] ?? '');
            $newPassword = XSSProtection::clean($_POST['new_password'] ?? '');
            $confirmPassword = XSSProtection::clean($_POST['confirm_password'] ?? '');

            if (empty($oldPassword) || empty($newPassword) || empty($confirmPassword)) {
                $this->session->getFlashBag()->add('error', "All fields are required.");
                return $this->render('changepassword.twig');
            }

            if ($newPassword !== $confirmPassword) {
                $this->session->getFlashBag()->add('error', "New passwords do not match.");
                return $this->render('changepassword.twig');
            }

            $change_password_status = $account->change_password($oldPassword, $newPassword);

            if ($change_password_status) {
                $this->session->getFlashBag()->add('success', "Password changed successfully.");
            } else {
                $this->session->getFlashBag()->add('error', "Failed to change password. Please check your old password.");
            }
        }

        return $this->render('changepassword.twig');
    }
}
