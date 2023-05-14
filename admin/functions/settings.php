<?php

class Settings {
    private $config;

    public function __construct() {
        $this->config = json_decode(file_get_contents('../engine/configs/gen_config.json'), true);
    }

    public function get_config() {
        return $this->config;
    }

    public function update_config($config) {
        file_put_contents('../engine/configs/gen_config.json', json_encode($config, JSON_PRETTY_PRINT));
    }
}
?>
