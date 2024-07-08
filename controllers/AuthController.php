<?php
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
