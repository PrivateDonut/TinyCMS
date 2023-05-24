<?php

class Account
{
    private $username;
    private $connection;
    private $website;

    public function __construct($username)
    {
        $this->username = $username;
        $config = new Configuration();
        $this->connection = $config->getDatabaseConnection('auth');
        $this->website = $config->getDatabaseConnection('website');
    }

    private function get_account()
    {
        $stmt = $this->connection->prepare("SELECT id, username, email, joindate, last_ip, last_login  FROM account WHERE username = ?");
        $stmt->bind_param("s", $this->username);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $account = array(
                "id" => $row['id'],
                "username" => $row['username'],
                "email" => $row['email'],
                "joindate" => $row['joindate'],
                "last_ip" => $row['last_ip'],
                "last_login" => $row['last_login']
            );

            return $account;
        }
        $stmt->close();
    }

    public function get_rank()
    {
        $account = $this->get_account();
        $stmt = $this->website->prepare("SELECT access_level FROM access WHERE account_id = ?");
        $stmt->bind_param("i", $account['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        if ($row) {
            return $row['access_level'];
        } else {
            // return 0 if no rank is found
            return 'Player';
        }
    }

    public function get_donor_points()
    {
        $account = $this->get_account();
        $stmt = $this->website->prepare("SELECT donor_points FROM users WHERE account_id = ?");
        $stmt->bind_param("i", $account['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        if ($row) {
            return $row['donor_points'];
        } else {
            return 0;
        }
    }

    public function get_vote_points()
    {
        $account = $this->get_account();
        $stmt = $this->website->prepare("SELECT vote_points FROM users WHERE account_id = ?");
        $stmt->bind_param("i", $account['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        if ($row) {
            return $row['vote_points'];
        } else {
            return 0;
        }
    }

    public function is_banned()
    {
        $account = $this->get_account();
        $stmt = $this->connection->prepare("SELECT `active` FROM account_banned WHERE id = ?");
        $stmt->bind_param("i", $account['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        if ($row) {
            return "Banned";
        } else {
            return "Good standing";
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

    private function get_salt()
    {
        $account = $this->get_account();
        $stmt = $this->connection->prepare("SELECT `salt` FROM account WHERE id = ?");
        $stmt->bind_param("i", $account['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        return $row['salt'];
    }

    private function get_verifier()
    {
        $account = $this->get_account();
        $stmt = $this->connection->prepare("SELECT `verifier` FROM account WHERE id = ?");
        $stmt->bind_param("i", $account['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        return $row['verifier'];
    }

    public function change_password($old_password, $new_password)
    {
        $salt = $this->get_salt();
        $verifier = $this->get_verifier();
        $old_verifier = $this->calculate_verifier($this->username, $old_password, $salt);
        $new_verifier = $this->calculate_verifier($this->username, $new_password, $salt);

        if ($old_verifier == $verifier) {
            $stmt = $this->connection->prepare("UPDATE account SET verifier = ? WHERE id = ?");
            $stmt->bind_param("si", $new_verifier, $this->get_id());
            $stmt->execute();
            $stmt->close();
            return true;
        } else {
            return false;
        }
    }
    

    public function get_id()
    {
        $account = $this->get_account();
        return $account['id'];
    }

    public function get_username()
    {
        return $this->username;
    }

    public function get_email()
    {
        $account = $this->get_account();
        return $account['email'];
    }

    public function get_joindate()
    {
        $account = $this->get_account();
        return $account['joindate'];
    }

    public function get_last_ip()
    {
        $account = $this->get_account();
        return $account['last_ip'];
    }

    public function get_last_login()
    {
        $account = $this->get_account();
        return $account['last_login'];
    }
}
?>