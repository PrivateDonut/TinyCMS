<!-- 
- Made By : PrivateDonut
- Project Name : TinyCMS
- Website : https://privatedonut.com
-->
<?php 

class Account {
    private $username;
    private $connection;
    private $website;

    public function __construct($username) {
        $this->username = $username;
        $config = new Configuration();
        $this->connection = $config->getDatabaseConnection('auth');
        $this->website = $config->getDatabaseConnection('website');
    }   

    private function get_account() {
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

    public function get_rank() {
        $account = $this->get_account();
        $stmt = $this->website->prepare("SELECT access_level FROM access WHERE account_id = ?");
        $stmt->bind_param("i", $account['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $rank = $row['access_level'];
        $stmt->close();
        return $rank;
    }
    

    public function get_id() {
       $account = $this->get_account();
       return $account['id'];
    }

    public function get_username() {
        return $this->username;
    }

    public function get_email() {
        $account = $this->get_account();
        return $account['email'];
    }

    public function get_joindate() {
        $account = $this->get_account();
        return $account['joindate'];
    }

    public function get_last_ip() {
        $account = $this->get_account();
        return $account['last_ip'];
    }

    public function get_last_login() {
        $account = $this->get_account();
        return $account['last_login'];
    }
}
?>