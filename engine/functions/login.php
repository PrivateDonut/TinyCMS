<?php

class Login
{
    private $username;
    private $password;
    private $auth_connection;
    private $website_connection;

    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
        $config = new Configuration();
        $this->auth_connection = $config->getDatabaseConnection('auth');
        $this->website_connection = $config->getDatabaseConnection('website');
    }

    public function login_checks()
    {
        if (empty($this->username) || empty($this->password)) {
            $_SESSION['error'] = "Please enter a username and password.";
            header("Location: ?page=login");
            return false;
        }
    }

    private function get_rank($id)
    {
        $stmt = $this->website_connection->prepare("SELECT access_level FROM access WHERE account_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($gm_rank);
        $stmt->fetch();
        $stmt->close();
        return $gm_rank;
    }

    public function login()
    {
        $stmt = $this->auth_connection->prepare("SELECT id, username, verifier, salt FROM account WHERE username = ?");
        $stmt->bind_param("s", $this->username);
        $stmt->execute();
        $stmt->bind_result($id, $username, $verifier, $salt);
        if ($stmt->fetch()) {
            $check_verifier = $this->calculate_verifier($username, $this->password, $salt);
            if ($check_verifier == $verifier) {
                $_SESSION['account_id'] = $id;
                $_SESSION['username'] = $username;
                $_SESSION['isAdmin'] = $this->get_rank($id);
                header("Location: ?page=home");
                return true;
            } else {
                $_SESSION['error'] = "Incorrect username or password.";
                header("Location: ?page=login");
                return false;
            }
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
