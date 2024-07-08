<?php
require_once __DIR__ . '/PluginInterface.php';

abstract class BasePlugin implements PluginInterface
{
    public function register()
    {
        // Registration logic
    }

    public function activate()
    {
        // Activation logic
    }

    public function deactivate()
    {
        // Deactivation logic
    }

    protected function addAction($hookName, $callback, $priority = 10)
    {
        HookHelper::addAction($hookName, $callback, $priority);
    }

    protected function addFilter($filterName, $callback, $priority = 10)
    {
        HookHelper::addFilter($filterName, $callback, $priority);
    }
}
