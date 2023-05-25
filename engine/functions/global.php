<?php

class GlobalFunctions
{

    private $auth_connection;
    private $website_connection;

    public function __construct()
    {
        $config = new Configuration();
        $this->auth_connection = $config->getDatabaseConnection('auth');
        $this->website_connection = $config->getDatabaseConnection('website');
    }

    public function logout()
    {
        session_destroy();
        header("Location: /?page=home");
        exit();
    }

    public function check_logged_in()
    {
        if (!isset($_SESSION['account_id'])) {
            header("Location: ?page=login");
            exit();
        }
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
