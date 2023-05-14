<?php 
class gen_config {
    private $config;

    public function __construct() {
        $this->config = json_decode(file_get_contents(__DIR__ . '/../configs/gen_config.json'), true);
    }

    public function get_config() {
        return $this->config;
    }
}


?>