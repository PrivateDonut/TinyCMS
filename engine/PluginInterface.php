<?php

interface PluginInterface
{
    public function register();
    public function activate();
    public function deactivate();
}
