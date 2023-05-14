<?php

class adminAccounts
{

    private $auth;
    private $characters;

    public function __construct()
    {
        $config = new Configuration();
        $this->auth = $config->getDatabaseConnection('auth');
        $this->characters = $config->getDatabaseConnection('characters');
    }

    public function get_total_accounts() {
        $stmt = $this->auth->prepare("SELECT COUNT(*) as total FROM account");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row['total'];
    }

    public function get_accounts($currentPage, $perPage) {
        $offset = ($currentPage - 1) * $perPage;
        $stmt = $this->auth->prepare("SELECT id, username, email, joindate, last_ip, last_login FROM account LIMIT ?, ?");
        $stmt->bind_param("ii", $offset, $perPage);
        $stmt->execute();
        $result = $stmt->get_result();
        $accounts = array();
        while ($row = $result->fetch_assoc()) {
            $accounts[] = array(
                "id" => $row['id'],
                "username" => $row['username'],
                "email" => $row['email'],
                "joindate" => $row['joindate'],
                "last_ip" => $row['last_ip'],
                "last_login" => $row['last_login']
            );
        }
        $stmt->close();
        return $accounts;
    }
}
