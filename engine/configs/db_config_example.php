<?php

class Configuration {
    private $config;

    public function __construct() {
        $this->config = [
            'db' => [
                'auth' => [
                    'database_type' => 'mysql',
                    'database_name' => 'auth',
                    'server' => 'localhost',
                    'username' => 'root',
                    'password' => 'ascent',
                    'charset' => 'utf8',
                    'collation' => 'utf8_general_ci',
                    'port' => 3306
                ],
                'website' => [
                    'database_type' => 'mysql',
                    'database_name' => 'website',
                    'server' => 'localhost',
                    'username' => 'root',
                    'password' => 'ascent',
                    'charset' => 'utf8',
                    'collation' => 'utf8_general_ci',
                    'port' => 3306
                ],
                'characters' => [
                    'database_type' => 'mysql',
                    'database_name' => 'characters',
                    'server' => 'localhost',
                    'username' => 'root',
                    'password' => 'ascent',
                    'charset' => 'utf8',
                    'collation' => 'utf8_general_ci',
                    'port' => 3306
                ]
            ]
        ];
    }

    public function get_config($key) {
        if (isset($this->config[$key])) {
            return $this->config[$key];
        } else {
            echo "Configuration key '$key' not found.";
            return null;
        }
    }
}
?>
