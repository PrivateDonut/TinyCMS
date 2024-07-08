<?php

/*********************************************************************************
 * DonutCMS is free software: you can redistribute it and/or modify              *        
 * it under the terms of the GNU General Public License as published by          *      
 * the Free Software Foundation, either version 3 of the License, or             *
 * (at your option) any later version.                                           *
 *                                                                               *
 * DonutCMS is distributed in the hope that it will be useful,                   *
 * but WITHOUT ANY WARRANTY; without even the implied warranty of                *
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the                  *
 * GNU General Public License for more details.                                  *
 *                                                                               *
 * You should have received a copy of the GNU General Public License             *
 * along with DonutCMS. If not, see <https://www.gnu.org/licenses/>.             *
 * *******************************************************************************/

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
