<?php

class HookHelper
{
    private static $pluginManager;

    public static function setPluginManager(PluginManager $manager)
    {
        self::$pluginManager = $manager;
    }

    public static function addAction($hookName, $callback, $priority = 10)
    {
        // Debug message, remove in production
        //echo "Adding action: $hookName<br>";
        self::$pluginManager->addHook($hookName, $callback, $priority);
    }

    public static function addFilter($hookName, $callback, $priority = 10)
    {
        // Debug message, remove in production
        //echo "Adding filter: $hookName<br>";
        self::$pluginManager->addHook($hookName, $callback, $priority);
    }

    public static function doAction($hookName, $args = [])
    {
        // Debug message, remove in production
        //echo "Executing action: $hookName<br>";
        if (self::$pluginManager && isset(self::$pluginManager->hooks[$hookName])) {
            foreach (self::$pluginManager->hooks[$hookName] as $hook) {
                call_user_func_array($hook['callback'], $args);
            }
        }
    }

    public static function applyFilters($filterName, $value, $args = [])
    {
        // Debug message, remove in production
        //echo "Applying filter: $filterName<br>";
        if (self::$pluginManager && isset(self::$pluginManager->hooks[$filterName])) {
            foreach (self::$pluginManager->hooks[$filterName] as $hook) {
                $value = call_user_func_array($hook['callback'], array_merge([$value], $args));
            }
        }
        return $value;
    }
}
